<?php

namespace App\Http\Controllers;

use App\Models\JenisSertifikat;
use App\Models\PengajuanSertifikat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PengajuanSertifikatController extends Controller
{
    public function index()
    {
        $jenisSertifikats = JenisSertifikat::where('is_active', true)->get();
        $pengajuans = PengajuanSertifikat::where('mahasiswa_id', Auth::id())
            ->with('jenisSertifikat')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('mahasiswa.sertifikat.index', compact('jenisSertifikats', 'pengajuans'));
    }

    public function create()
    {
        $jenisSertifikats = JenisSertifikat::where('is_active', true)->get();
        return view('mahasiswa.sertifikat.create', compact('jenisSertifikats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_sertifikat_id' => 'required|exists:jenis_sertifikats,id',
            'nama_sertifikat' => 'required|string|max:255',
            'nama_sertifikat_en' => 'nullable|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tanggal_terbit' => 'required|date',
            'file_sertifikat' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $filePath = null;
            if ($request->hasFile('file_sertifikat')) {
                $filePath = $request->file('file_sertifikat')->store('sertifikat', 'public');
            }

            PengajuanSertifikat::create([
                'mahasiswa_id' => Auth::id(),
                'jenis_sertifikat_id' => $validated['jenis_sertifikat_id'],
                'nama_sertifikat' => $validated['nama_sertifikat'],
                'nama_sertifikat_en' => $validated['nama_sertifikat_en'],
                'penerbit' => $validated['penerbit'],
                'tanggal_terbit' => $validated['tanggal_terbit'],
                'file_sertifikat' => $filePath,
                'status' => 'menunggu',
            ]);
        });

        return redirect()->route('mahasiswa.sertifikat.index')
            ->with('success', 'Pengajuan sertifikat berhasil dikirim');
    }

    public function show(PengajuanSertifikat $sertifikat)
    {
        // Ensure user can only see their own submissions
        if ($sertifikat->mahasiswa_id !== Auth::id()) {
            abort(403);
        }

        return view('mahasiswa.sertifikat.show', compact('sertifikat'));
    }

    public function edit(PengajuanSertifikat $sertifikat)
    {
        // Only allow edit if rejected or pending
        if ($sertifikat->mahasiswa_id !== Auth::id()) {
            abort(403);
        }

        if ($sertifikat->status === 'diterima') {
            return redirect()->route('mahasiswa.sertifikat.index')
                ->with('error', 'Pengajuan yang sudah diterima tidak dapat diubah');
        }

        $jenisSertifikats = JenisSertifikat::where('is_active', true)->get();
        return view('mahasiswa.sertifikat.edit', compact('sertifikat', 'jenisSertifikats'));
    }

    public function update(Request $request, PengajuanSertifikat $sertifikat)
    {
        if ($sertifikat->mahasiswa_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'jenis_sertifikat_id' => 'required|exists:jenis_sertifikats,id',
            'nama_sertifikat' => 'required|string|max:255',
            'nama_sertifikat_en' => 'nullable|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tanggal_terbit' => 'required|date',
            'file_sertifikat' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        DB::transaction(function () use ($validated, $request, $sertifikat) {
            $filePath = $sertifikat->file_sertifikat;

            if ($request->hasFile('file_sertifikat')) {
                // Delete old file
                if ($filePath) {
                    Storage::disk('public')->delete($filePath);
                }
                $filePath = $request->file('file_sertifikat')->store('sertifikat', 'public');
            }

            $sertifikat->update([
                'jenis_sertifikat_id' => $validated['jenis_sertifikat_id'],
                'nama_sertifikat' => $validated['nama_sertifikat'],
                'nama_sertifikat_en' => $validated['nama_sertifikat_en'],
                'penerbit' => $validated['penerbit'],
                'tanggal_terbit' => $validated['tanggal_terbit'],
                'file_sertifikat' => $filePath,
                'status' => 'menunggu', // Reset to pending
                'catatan_verifikasi' => null,
                'verified_by' => null,
                'verified_at' => null,
            ]);
        });

        return redirect()->route('mahasiswa.sertifikat.index')
            ->with('success', 'Pengajuan sertifikat berhasil diperbarui');
    }
}
