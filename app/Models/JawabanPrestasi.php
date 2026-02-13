<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JawabanPrestasi extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'pengajuan_prestasi_id',
        'formulir_pertanyaan_id',
        'jawaban',
        'file_path',
    ];

    public function pengajuanPrestasi(): BelongsTo
    {
        return $this->belongsTo(PengajuanPrestasi::class);
    }

    public function pertanyaan(): BelongsTo
    {
        return $this->belongsTo(FormulirPertanyaan::class, 'formulir_pertanyaan_id');
    }
}
