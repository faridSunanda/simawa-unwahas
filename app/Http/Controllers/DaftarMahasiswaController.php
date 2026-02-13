<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\PengajuanSertifikat;
use Illuminate\Http\Request;

class DaftarMahasiswaController extends Controller
{
    public function index()
    {
        // Get all mahasiswa with their user data and count of sertifikat
        $mahasiswas = Mahasiswa::with('user')
            ->withCount([
                'pengajuanSertifikats as sertifikat_count' => function ($query) {
                    $query->where('status', 'terverifikasi');
                }
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('superadmin.daftar-mahasiswa.index', compact('mahasiswas'));
    }

    public function show(Mahasiswa $mahasiswa)
    {
        $mahasiswa->load('user');

        // Get sertifikat pengajuan for this mahasiswa
        $pengajuanSertifikats = PengajuanSertifikat::with('jenisSertifikat')
            ->where('mahasiswa_id', $mahasiswa->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('superadmin.daftar-mahasiswa.show', compact('mahasiswa', 'pengajuanSertifikats'));
    }
}
