<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormulirPertanyaan extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'formulir_pertanyaans';

    protected $fillable = [
        'formulir_prestasi_id',
        'pertanyaan',
        'tipe',
        'opsi',
        'wajib',
        'urutan',
    ];

    protected $casts = [
        'opsi' => 'array',
        'wajib' => 'boolean',
    ];

    /**
     * Get the formulir prestasi that owns this pertanyaan.
     */
    public function formulirPrestasi(): BelongsTo
    {
        return $this->belongsTo(FormulirPrestasi::class);
    }
}
