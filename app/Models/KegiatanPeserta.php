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
}
