<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KegiatanPeserta extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'kegiatan_peserta';

    protected $fillable = [
        'kegiatan_id',
        'mahasiswa_id',
        'status_presensi',
        'waktu_presensi',
        'status',
    ];

    protected $casts = [
        'waktu_presensi' => 'datetime',
    ];

    /**
     * Get the kegiatan that owns this peserta.
     */
    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }

    /**
     * Get the mahasiswa for this peserta.
     */
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    /**
     * Get the jawabans for this peserta.
     */
    public function jawabans()
    {
        return $this->hasMany(KegiatanJawabanPeserta::class , 'kegiatan_peserta_id');
    }

    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
                'menunggu' => 'Menunggu Verifikasi',
                'terdaftar' => 'Terdaftar (Diterima)',
                'ditolak' => 'Ditolak',
                default => 'Unknown',
            };
    }

    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {
                'menunggu' => 'bg-amber-100 text-amber-700',
                'terdaftar' => 'bg-green-100 text-green-700',
                'ditolak' => 'bg-red-100 text-red-700',
                default => 'bg-gray-100 text-gray-700',
            };
    }
}
