<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Services\DocumentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class DocumentController extends Controller
{
    private const DOCUMENT_UPLOAD_FAILED = 'Document upload failed';

    private const DOCUMENT_UPLOAD_INITIATED = 'Document upload initiated';

    public function __construct(
        private readonly DocumentService $documentService,
    ) {}

    public function store(Request $request, Patient $patient): Response
    {
        $request->validate([
            'document' => 'required|file|mimes:pdf|max:'.env('MAX_FILE_SIZE', 5120),
        ]);

        try {
            $this->documentService->storeDocument($patient, $request->file('document'));

            return response()->json(['message' => self::DOCUMENT_UPLOAD_INITIATED], Response::HTTP_ACCEPTED);
        } catch (\Exception $e) {
            Log::error(
                self::DOCUMENT_UPLOAD_FAILED,
                ['exception' => $e, 'patient_id' => $patient->id]
            );

            return response()->json(['message' => self::DOCUMENT_UPLOAD_FAILED], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }
}
