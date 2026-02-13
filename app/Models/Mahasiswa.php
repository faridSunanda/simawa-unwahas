<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mahasiswa extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'nim',
        'fakultas',
        'prodi',
        'angkatan',
        'no_hp',
        'alamat',
    ];

    /**
     * Get the user that owns this mahasiswa profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all pengajuan sertifikat for this mahasiswa.
     */
    public function pengajuanSertifikats()
    {
        return $this->hasMany(\App\Models\PengajuanSertifikat::class);
    }
}
