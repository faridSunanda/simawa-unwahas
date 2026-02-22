<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KegiatanPertanyaan extends Model
{
    protected $fillable = [
        'kegiatan_id',
        'pertanyaan',
        'tipe',
        'wajib',
        'opsi'
    ];

    protected $casts = [
        'wajib' => 'boolean',
        'opsi' => 'array',
    ];

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }
}
