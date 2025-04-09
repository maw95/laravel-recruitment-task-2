<?php

declare(strict_types=1);

use App\Http\Controllers\DocumentController;
use App\Http\Middleware\EnsurePatientBelongsToUser;
use Illuminate\Support\Facades\Route;

Route::middleware('auth.basic')->group(function () {
    Route::post('patients/{patient}/documents', [DocumentController::class, 'store'])
        ->middleware(EnsurePatientBelongsToUser::class)
        ->name('patients.documents.store');
});
