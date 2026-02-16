<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FormulirPrestasi extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'formulir_prestasis';

    protected $fillable = [
        'judul',
        'kategori_prestasi_id',
        'tahun',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];


    public function kategoriPrestasi(): BelongsTo
    {
        return $this->belongsTo(KategoriPrestasi::class);
    }


    public function pertanyaans(): HasMany
    {
        return $this->hasMany(FormulirPertanyaan::class)->orderBy('urutan');
    }
}
