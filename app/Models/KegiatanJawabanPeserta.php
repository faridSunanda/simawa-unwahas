<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KegiatanJawabanPeserta extends Model
{
    protected $fillable = [
        'kegiatan_peserta_id',
        'pertanyaan_id',
        'jawaban'
    ];

    public function pertanyaan()
    {
        return $this->belongsTo(KegiatanPertanyaan::class , 'pertanyaan_id');
    }

    public function peserta()
    {
        return $this->belongsTo(KegiatanPeserta::class , 'kegiatan_peserta_id');
    }
}
