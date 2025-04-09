<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StoringDocumentTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_store_document_successfully(): void
    {
        Storage::fake('local');

        /**
         * @var Patient $patient
         */
        $patient = Patient::factory()->create();

        $file = UploadedFile::fake()->create('document.pdf', 1000, 'application/pdf');

        $response = $this->actingAs($patient->user)->postJson(route('patients.documents.store', $patient), [
            'document' => $file,
        ]);

        $response->assertStatus(Response::HTTP_ACCEPTED);
        $response->assertJson(['message' => 'Document upload initiated']);

        Storage::disk('local')->assertExists('documents/'.$file->hashName());
    }

    public function test_store_document_validation_error_if_document_is_not_a_file(): void
    {
        /**
         * @var Patient $patient
         */
        $patient = Patient::factory()->create();

        $response = $this->actingAs($patient->user)->postJson(route('patients.documents.store', $patient), [
            'document' => 'not-a-file',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('document');
    }

    public function test_store_document_fails_if_not_pdf(): void
    {
        Storage::fake('local');

        /**
         * @var Patient $patient
         */
        $patient = Patient::factory()->create();

        $file = UploadedFile::fake()->create('document.txt', 1000, 'text/plain');

        $response = $this->actingAs($patient->user)->postJson(route('patients.documents.store', $patient), [
            'document' => $file,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('document');
    }

    public function test_store_document_fails_if_file_too_large(): void
    {
        Storage::fake('local');

        /**
         * @var Patient $patient
         */
        $patient = Patient::factory()->create();

        $file = UploadedFile::fake()->create('document.pdf', env('MAX_FILE_SIZE', 5120) * 2, 'application/pdf');

        $response = $this->actingAs($patient->user)->postJson(route('patients.documents.store', $patient), [
            'document' => $file,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('document');
    }

    public function test_store_document_fails_if_patient_not_belong_to_user(): void
    {
        Storage::fake('local');

        /**
         * @var Patient $patient
         */
        $patient = Patient::factory()->create();
        $anotherUser = User::factory()->create();

        $file = UploadedFile::fake()->create('document.pdf', 1000, 'application/pdf');

        $response = $this->actingAs($anotherUser)->postJson(route('patients.documents.store', $patient), [
            'document' => $file,
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }
}
