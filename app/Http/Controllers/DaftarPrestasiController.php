<?php

namespace App\Http\Controllers;

use App\Models\PengajuanPrestasi;
use Illuminate\Http\Request;

class DaftarPrestasiController extends Controller
{
    /**
     * Display list of accepted (diterima) prestasi
     */
    public function index()
    {
        $prestasis = PengajuanPrestasi::with([
            'mahasiswa',
            'formulirPrestasi.kategoriPrestasi',
            'verifikator'
        ])
            ->where('status', 'diterima')
            ->orderBy('verified_at', 'desc')
            ->get();

        return view('superadmin.daftar-prestasi.index', compact('prestasis'));
    }

    /**
     * Show detail of prestasi
     */
    public function show(PengajuanPrestasi $pengajuanPrestasi)
    {
        if ($pengajuanPrestasi->status !== 'diterima') {
            abort(404);
        }

        $pengajuanPrestasi->load([
            'mahasiswa',
            'formulirPrestasi.kategoriPrestasi',
            'formulirPrestasi.pertanyaans' => fn($q) => $q->orderBy('urutan'),
            'jawabans.pertanyaan',
            'verifikator'
        ]);

        return view('superadmin.daftar-prestasi.show', compact('pengajuanPrestasi'));
    }
}
