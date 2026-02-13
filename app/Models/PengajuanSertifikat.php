<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanSertifikat extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'mahasiswa_id',
        'jenis_sertifikat_id',
        'nama_sertifikat',
        'nama_sertifikat_en',
        'penerbit',
        'tanggal_terbit',
        'file_sertifikat',
        'status',
        'catatan_verifikasi',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'tanggal_terbit' => 'date',
        'verified_at' => 'datetime',
    ];

    // Relations
    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    public function jenisSertifikat()
    {
        return $this->belongsTo(JenisSertifikat::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // Status Badge Attribute
    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {
            'menunggu' => 'bg-yellow-100 text-yellow-700',
            'diterima' => 'bg-green-100 text-green-700',
            'ditolak' => 'bg-red-100 text-red-700',
            default => 'bg-gray-100 text-gray-700',
        };
    }

    // Status Label Attribute
    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'menunggu' => 'Menunggu Verifikasi',
            'diterima' => 'Diterima',
            'ditolak' => 'Ditolak',
            default => 'Unknown',
        };
    }
}
