<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\KegiatanPeserta;
use Illuminate\Http\Request;

class KegiatanMahasiswaController extends Controller
{
    /**
     * Display a listing of available kegiatan.
     */
    public function index()
    {
        $mahasiswa = auth()->user()->mahasiswa;

        // Get kegiatan yang tampilkan = true
        $kegiatans = Kegiatan::where('tampilkan', true)
            ->orderBy('waktu_mulai', 'desc')
            ->get();

        // Get kegiatan IDs yang sudah didaftari mahasiswa
        $registeredIds = [];
        if ($mahasiswa) {
            $registeredIds = KegiatanPeserta::where('mahasiswa_id', $mahasiswa->id)
                ->pluck('kegiatan_id')
                ->toArray();
        }

        return view('mahasiswa.kegiatan.index', compact('kegiatans', 'registeredIds'));
    }

    /**
     * Display kegiatan detail with rundown.
     */
    public function show(Kegiatan $kegiatan)
    {
        // Only show if tampilkan is true
        if (!$kegiatan->tampilkan) {
            abort(404);
        }

        $kegiatan->load('rundowns');

        $mahasiswa = auth()->user()->mahasiswa;
        $isRegistered = false;
        $peserta = null;

        if ($mahasiswa) {
            $peserta = KegiatanPeserta::where('kegiatan_id', $kegiatan->id)
                ->where('mahasiswa_id', $mahasiswa->id)
                ->first();
            $isRegistered = $peserta !== null;
        }

        return view('mahasiswa.kegiatan.show', compact('kegiatan', 'isRegistered', 'peserta'));
    }

    /**
     * Register mahasiswa to kegiatan.
     */
    public function daftar(Kegiatan $kegiatan)
    {
        $mahasiswa = auth()->user()->mahasiswa;

        if (!$mahasiswa) {
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan');
        }

        // Check if kegiatan pendaftaran is open
        if ($kegiatan->pendaftaran !== 'buka') {
            return redirect()->back()->with('error', 'Pendaftaran untuk kegiatan ini sudah ditutup');
        }

        // Check if already registered
        $existingRegistration = KegiatanPeserta::where('kegiatan_id', $kegiatan->id)
            ->where('mahasiswa_id', $mahasiswa->id)
            ->first();

        if ($existingRegistration) {
            return redirect()->back()->with('error', 'Anda sudah terdaftar di kegiatan ini');
        }

        // Register
        KegiatanPeserta::create([
            'kegiatan_id' => $kegiatan->id,
            'mahasiswa_id' => $mahasiswa->id,
            'status_presensi' => 'belum_hadir',
        ]);

        return redirect()->route('mahasiswa.kegiatan.saya')
            ->with('success', 'Berhasil mendaftar ke kegiatan: ' . $kegiatan->nama);
    }

    /**
     * Display kegiatan yang sudah diikuti mahasiswa.
     */
    public function kegiatanSaya()
    {
        $mahasiswa = auth()->user()->mahasiswa;

        $pesertaRecords = collect();

        if ($mahasiswa) {
            $pesertaRecords = KegiatanPeserta::with('kegiatan')
                ->where('mahasiswa_id', $mahasiswa->id)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('mahasiswa.kegiatan.kegiatan-saya', compact('pesertaRecords', 'mahasiswa'));
    }
}
