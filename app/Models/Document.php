<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $patient_id
 * @property string $file_path
 * @property Patient $patient
 */
class Document extends Model
{
    protected $guarded = [
        'id',
    ];

    protected $fillable = [
        'patient_id',
        'file_path',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}
