<?php

declare(strict_types=1);

namespace Tests\Unit\Service;

use App\Jobs\StoreDocument;
use App\Models\Patient;
use App\Services\DocumentService;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;

class DocumentServiceTest extends TestCase
{
    private DocumentService $documentService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->documentService = new DocumentService;
    }

    public function test_stores_file_and_creates_document(): void
    {
        Queue::fake([StoreDocument::class]);

        /**
         * @var Patient $patient
         */
        $patient = Patient::factory()->create();

        $file = $this->createMock(UploadedFile::class);
        $file->expects($this->once())
            ->method('store')
            ->with('documents')
            ->willReturn('documents/'.$file->hashName());

        $this->documentService->storeDocument($patient, $file);

        Queue::assertPushed(StoreDocument::class, function ($job) use ($patient, $file) {
            /** @var StoreDocument $job */
            return $job->patientId === $patient->id && $job->path === 'documents/'.$file->hashName();
        });
    }

    public function test_fails_to_store_file_and_does_not_create_document(): void
    {
        Queue::fake([StoreDocument::class]);

        /**
         * @var Patient $patient
         */
        $patient = Patient::factory()->create();

        $file = $this->createMock(UploadedFile::class);
        $file->expects($this->once())
            ->method('store')
            ->with('documents')
            ->willThrowException(new \Exception('Failed to store document'));

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Failed to store document');

        $this->documentService->storeDocument($patient, $file);

        Queue::assertNotPushed(StoreDocument::class);
    }
}
