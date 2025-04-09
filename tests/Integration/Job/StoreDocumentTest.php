<?php

declare(strict_types=1);

namespace Tests\Integration\Job;

use App\Jobs\StoreDocument;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\Storage;

class StoreDocumentTest extends TestCase
{
    use RefreshDatabase;

    public function test_handle_stores_document(): void
    {
        Storage::fake('documents');

        $user = User::factory()->create();
        $patient = Patient::create(['name' => 'John Doe', 'user_id' => $user->id]);
        $job = new StoreDocument(1, 'documents/document.pdf');

        $job->handle();

        $this->assertDatabaseHas('documents', [
            'patient_id' => 1,
            'file_path' => 'documents/document.pdf',
        ]);
    }
}
