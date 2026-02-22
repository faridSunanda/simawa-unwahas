<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'nama',
        'detail',
        'waktu_mulai',
        'waktu_selesai',
        'tampilkan',
        'presensi',
        'pendaftaran',
        'opsi_formulir',
        'sertifikat_template',
    ];

    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
        'tampilkan' => 'boolean',
        'presensi' => 'boolean',
        'opsi_formulir' => 'boolean',
    ];

    public function pertanyaans()
    {
        return $this->hasMany(KegiatanPertanyaan::class)->orderBy('id');
    }

    public function peserta()
    {
        return $this->hasMany(KegiatanPeserta::class);
    }

    public function rundowns()
    {
        return $this->hasMany(KegiatanRundown::class);
    }

    public function mahasiswas()
    {
        return $this->belongsToMany(Mahasiswa::class , 'kegiatan_peserta')
            ->withPivot('status_presensi', 'waktu_presensi')
            ->withTimestamps();
    }
}
