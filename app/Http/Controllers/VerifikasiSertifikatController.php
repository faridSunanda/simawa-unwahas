<?php

namespace App\Http\Controllers;

use App\Models\PengajuanSertifikat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifikasiSertifikatController extends Controller
{
    /**
     * Get the current kaprodi's fakultas and prodi from dosen profile.
     */
    private function getKaprodiProfile()
    {
        $user = Auth::user();
        $dosen = $user->dosen;

        if (!$dosen) {
            abort(403, 'Profil dosen tidak ditemukan.');
        }

        return $dosen;
    }

    public function index(Request $request)
    {
        $status = $request->get('status', 'semua');
        $kaprodi = $this->getKaprodiProfile();

        // Filter sertifikat berdasarkan fakultas dan prodi kaprodi
        $query = PengajuanSertifikat::with(['mahasiswa.mahasiswa', 'jenisSertifikat'])
            ->whereHas('mahasiswa.mahasiswa', function ($q) use ($kaprodi) {
                $q->where('fakultas', $kaprodi->fakultas)
                    ->where('prodi', $kaprodi->prodi);
            })
            ->orderBy('created_at', 'desc');

        if ($status !== 'semua') {
            $query->where('status', $status);
        }

        $pengajuans = $query->get();

        return view('kaprodi.verifikasi-sertifikat.index', compact('pengajuans'));
    }

    public function show(PengajuanSertifikat $pengajuanSertifikat)
    {
        $kaprodi = $this->getKaprodiProfile();

        // Pastikan pengajuan dari mahasiswa dengan fakultas dan prodi yang sama
        $pengajuanSertifikat->load(['mahasiswa.mahasiswa', 'jenisSertifikat', 'verifier']);

        $mahasiswaProfile = $pengajuanSertifikat->mahasiswa->mahasiswa;
        if (
            !$mahasiswaProfile ||
            $mahasiswaProfile->fakultas !== $kaprodi->fakultas ||
            $mahasiswaProfile->prodi !== $kaprodi->prodi
        ) {
            abort(403, 'Anda tidak memiliki akses untuk melihat pengajuan sertifikat ini.');
        }

        return view('kaprodi.verifikasi-sertifikat.show', compact('pengajuanSertifikat'));
    }

    public function verify(Request $request, PengajuanSertifikat $pengajuanSertifikat)
    {
        $kaprodi = $this->getKaprodiProfile();

        // Pastikan kaprodi hanya bisa memverifikasi sertifikat dari prodi mereka
        $pengajuanSertifikat->load('mahasiswa.mahasiswa');
        $mahasiswaProfile = $pengajuanSertifikat->mahasiswa->mahasiswa;

        if (
            !$mahasiswaProfile ||
            $mahasiswaProfile->fakultas !== $kaprodi->fakultas ||
            $mahasiswaProfile->prodi !== $kaprodi->prodi
        ) {
            abort(403, 'Anda tidak memiliki akses untuk memverifikasi pengajuan sertifikat ini.');
        }

        $validated = $request->validate([
            'status' => 'required|in:diterima,ditolak',
            'catatan_verifikasi' => 'nullable|string',
        ]);

        $pengajuanSertifikat->update([
            'status' => $validated['status'],
            'catatan_verifikasi' => $validated['catatan_verifikasi'],
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        $message = $validated['status'] === 'diterima'
            ? 'Pengajuan sertifikat telah disetujui'
            : 'Pengajuan sertifikat telah ditolak';

        return redirect()->route('kaprodi.verifikasi-sertifikat.index')
            ->with('success', $message);
    }
}
