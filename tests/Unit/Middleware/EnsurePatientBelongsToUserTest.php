<?php

declare(strict_types=1);

namespace Tests\Unit\Middleware;

use App\Http\Middleware\EnsurePatientBelongsToUser;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use PHPUnit\Framework\MockObject\MockObject;

class EnsurePatientBelongsToUserTest extends TestCase
{
    private EnsurePatientBelongsToUser $middleware;

    private Request&MockObject $request;

    protected function setUp(): void
    {
        parent::setUp();
        $this->middleware = new EnsurePatientBelongsToUser;
        $this->request = $this->createMock(Request::class);
    }

    public function test_allows_access_when_patient_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $patient = new Patient(['user_id' => $user->id]);

        $this->request->expects($this->once())
            ->method('route')
            ->with('patient')
            ->willReturn($patient);

        $this->request->expects($this->once())
            ->method('user')
            ->willReturn($user);

        $response = $this->middleware->handle(
            $this->request,
            fn ($req) => new Response('OK', Response::HTTP_ACCEPTED)
        );

        $this->assertEquals(Response::HTTP_ACCEPTED, $response->getStatusCode());
        $this->assertEquals('OK', $response->getContent());
    }

    public function test_blocks_access_when_patient_does_not_belong_to_user(): void
    {
        $user = new User(['id' => 1]);
        $patient = new Patient(['user_id' => 1]);

        $this->request->expects($this->once())
            ->method('route')
            ->with('patient')
            ->willReturn($patient);

        $this->request->expects($this->once())
            ->method('user')
            ->willReturn($user);

        $response = $this->middleware->handle(
            $this->request,
            fn ($req) => new Response('OK', Response::HTTP_ACCEPTED)
        );

        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
        $this->assertEquals(json_encode(['message' => 'Unauthorized']), $response->getContent());
    }
}
