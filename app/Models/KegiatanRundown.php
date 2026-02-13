<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KegiatanRundown extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'kegiatan_id',
        'nama',
        'waktu_mulai',
        'waktu_selesai',
    ];

    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
    ];

    /**
     * Get the kegiatan that owns this rundown.
     */
    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }
}
