<?php

declare(strict_types=1);

namespace App\Services;

use App\Jobs\StoreDocument;
use App\Models\Patient;
use Illuminate\Http\UploadedFile;

readonly class DocumentService
{
    public function storeDocument(Patient $patient, UploadedFile $file): void
    {
        $path = $file->store('documents');
        StoreDocument::dispatch($patient->id, $path);
    }
}
