<?php

namespace App\Http\Controllers\Kemahasiswaan;

use App\Http\Controllers\Controller;
use App\Models\KegiatanPeserta;
use Illuminate\Http\Request;

class VerifikasiKegiatanController extends Controller
{
    public function index(Request $request)
    {
        $query = KegiatanPeserta::with(['kegiatan', 'mahasiswa.user'])->orderBy('created_at', 'desc');

        // Filter by status, default to pending ('menunggu') if not specified
        $status = $request->input('status', 'menunggu');

        if ($status !== 'semua') {
            $query->where('status', $status);
        }

        $pesertas = $query->get();

        return view('kemahasiswaan.verifikasi-kegiatan.index', compact('pesertas'));
    }

    public function show(KegiatanPeserta $kegiatanPeserta)
    {
        $kegiatanPeserta->load(['kegiatan', 'mahasiswa.user', 'jawabans.pertanyaan']);

        return view('kemahasiswaan.verifikasi-kegiatan.show', compact('kegiatanPeserta'));
    }

    public function verify(Request $request, KegiatanPeserta $kegiatanPeserta)
    {
        $validated = $request->validate([
            'status' => 'required|in:terdaftar,ditolak',
        ]);

        $kegiatanPeserta->update([
            'status' => $validated['status'],
        ]);

        $messages = [
            'terdaftar' => 'Pendaftaran mahasiswa berhasil diverifikasi (Diterima).',
            'ditolak' => 'Pendaftaran mahasiswa telah ditolak.',
        ];

        return redirect()->route('kemahasiswaan.verifikasi-kegiatan.index')
            ->with('success', $messages[$validated['status']]);
    }
}
