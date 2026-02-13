<?php

namespace App\Http\Controllers;

use App\Models\PengajuanPrestasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifikasiPrestasiController extends Controller
{
    /**
     * Display list of all pengajuan for verification
     */
    public function index(Request $request)
    {
        $query = PengajuanPrestasi::with([
            'mahasiswa',
            'formulirPrestasi.kategoriPrestasi'
        ])->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->has('status') && $request->status !== 'semua') {
            $query->where('status', $request->status);
        }

        $pengajuans = $query->get();

        return view('superadmin.verifikasi-prestasi.index', compact('pengajuans'));
    }

    /**
     * Show detail of pengajuan for verification
     */
    public function show(PengajuanPrestasi $pengajuanPrestasi)
    {
        $pengajuanPrestasi->load([
            'mahasiswa',
            'formulirPrestasi.kategoriPrestasi',
            'formulirPrestasi.pertanyaans' => fn($q) => $q->orderBy('urutan'),
            'jawabans.pertanyaan',
            'verifikator'
        ]);

        return view('superadmin.verifikasi-prestasi.show', compact('pengajuanPrestasi'));
    }

    /**
     * Process verification (ACC, REVISI, TOLAK)
     */
    public function verify(Request $request, PengajuanPrestasi $pengajuanPrestasi)
    {
        $validated = $request->validate([
            'status' => 'required|in:diterima,revisi,ditolak',
            'catatan_verifikator' => 'nullable|string|max:1000',
        ]);

        $pengajuanPrestasi->update([
            'status' => $validated['status'],
            'catatan_verifikator' => $validated['catatan_verifikator'],
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        $messages = [
            'diterima' => 'Pengajuan berhasil di-ACC!',
            'revisi' => 'Pengajuan dikembalikan untuk revisi.',
            'ditolak' => 'Pengajuan telah ditolak.',
        ];

        return redirect()->route('superadmin.verifikasi-prestasi.index')
            ->with('success', $messages[$validated['status']]);
    }
}
