<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PengajuanPrestasi extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'mahasiswa_id',
        'formulir_prestasi_id',
        'status',
        'catatan_verifikator',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    public function formulirPrestasi(): BelongsTo
    {
        return $this->belongsTo(FormulirPrestasi::class);
    }

    public function verifikator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function jawabans(): HasMany
    {
        return $this->hasMany(JawabanPrestasi::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'menunggu' => 'bg-yellow-100 text-yellow-700',
            'revisi' => 'bg-orange-100 text-orange-700',
            'ditolak' => 'bg-red-100 text-red-700',
            'diterima' => 'bg-green-100 text-green-700',
            default => 'bg-gray-100 text-gray-700',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'menunggu' => 'Menunggu Verifikasi',
            'revisi' => 'Butuh Revisi',
            'ditolak' => 'Ditolak',
            'diterima' => 'Diterima',
            default => $this->status,
        };
    }
}
