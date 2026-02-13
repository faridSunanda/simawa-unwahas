<?php

namespace App\Http\Controllers;

use App\Models\FormulirPrestasi;
use App\Models\JawabanPrestasi;
use App\Models\PengajuanPrestasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PengajuanPrestasiController extends Controller
{
    /**
     * Display list of available formulirs and mahasiswa's submissions
     */
    public function index()
    {
        $formulirs = FormulirPrestasi::with('kategoriPrestasi')
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();

        $pengajuans = PengajuanPrestasi::with(['formulirPrestasi.kategoriPrestasi'])
            ->where('mahasiswa_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('mahasiswa.prestasi.index', compact('formulirs', 'pengajuans'));
    }

    /**
     * Show form for creating new pengajuan
     */
    public function create(FormulirPrestasi $formulirPrestasi)
    {
        $formulirPrestasi->load([
            'kategoriPrestasi',
            'pertanyaans' => function ($q) {
                $q->orderBy('urutan');
            }
        ]);

        return view('mahasiswa.prestasi.create', compact('formulirPrestasi'));
    }

    /**
     * Store new pengajuan
     */
    public function store(Request $request, FormulirPrestasi $formulirPrestasi)
    {
        $formulirPrestasi->load('pertanyaans');

        // Build validation rules dynamically
        $rules = [];
        foreach ($formulirPrestasi->pertanyaans as $pertanyaan) {
            $key = 'jawaban.' . $pertanyaan->id;
            if ($pertanyaan->wajib) {
                if ($pertanyaan->tipe === 'file') {
                    $rules[$key] = 'required|file|max:5120|mimes:pdf,jpg,jpeg,png';
                } else {
                    $rules[$key] = 'required|string|max:1000';
                }
            } else {
                if ($pertanyaan->tipe === 'file') {
                    $rules[$key] = 'nullable|file|max:5120|mimes:pdf,jpg,jpeg,png';
                } else {
                    $rules[$key] = 'nullable|string|max:1000';
                }
            }
        }

        $validated = $request->validate($rules);

        DB::transaction(function () use ($validated, $formulirPrestasi) {
            $pengajuan = PengajuanPrestasi::create([
                'mahasiswa_id' => Auth::id(),
                'formulir_prestasi_id' => $formulirPrestasi->id,
                'status' => 'menunggu',
            ]);

            foreach ($formulirPrestasi->pertanyaans as $pertanyaan) {
                $jawaban = $validated['jawaban'][$pertanyaan->id] ?? null;
                $filePath = null;

                if ($pertanyaan->tipe === 'file' && $jawaban) {
                    $filePath = $jawaban->store('prestasi/' . $pengajuan->id, 'public');
                    $jawaban = null;
                }

                JawabanPrestasi::create([
                    'pengajuan_prestasi_id' => $pengajuan->id,
                    'formulir_pertanyaan_id' => $pertanyaan->id,
                    'jawaban' => $jawaban,
                    'file_path' => $filePath,
                ]);
            }
        });

        return redirect()->route('mahasiswa.prestasi.index')
            ->with('success', 'Pengajuan prestasi berhasil dikirim!');
    }

    /**
     * Show detail of pengajuan
     */
    public function show(PengajuanPrestasi $pengajuanPrestasi)
    {
        // Ensure only the owner can view
        if ($pengajuanPrestasi->mahasiswa_id !== Auth::id()) {
            abort(403);
        }

        $pengajuanPrestasi->load([
            'formulirPrestasi.kategoriPrestasi',
            'formulirPrestasi.pertanyaans' => fn($q) => $q->orderBy('urutan'),
            'jawabans.pertanyaan',
            'verifikator'
        ]);

        return view('mahasiswa.prestasi.show', compact('pengajuanPrestasi'));
    }

    /**
     * Edit pengajuan (only for revisi status)
     */
    public function edit(PengajuanPrestasi $pengajuanPrestasi)
    {
        if ($pengajuanPrestasi->mahasiswa_id !== Auth::id()) {
            abort(403);
        }

        if ($pengajuanPrestasi->status !== 'revisi') {
            return redirect()->route('mahasiswa.prestasi.show', $pengajuanPrestasi)
                ->with('error', 'Pengajuan ini tidak dapat diedit.');
        }

        $pengajuanPrestasi->load([
            'formulirPrestasi.kategoriPrestasi',
            'formulirPrestasi.pertanyaans' => fn($q) => $q->orderBy('urutan'),
            'jawabans'
        ]);

        return view('mahasiswa.prestasi.edit', compact('pengajuanPrestasi'));
    }

    /**
     * Update pengajuan (resubmit after revision)
     */
    public function update(Request $request, PengajuanPrestasi $pengajuanPrestasi)
    {
        if ($pengajuanPrestasi->mahasiswa_id !== Auth::id()) {
            abort(403);
        }

        if ($pengajuanPrestasi->status !== 'revisi') {
            return redirect()->route('mahasiswa.prestasi.show', $pengajuanPrestasi)
                ->with('error', 'Pengajuan ini tidak dapat diedit.');
        }

        $pengajuanPrestasi->load('formulirPrestasi.pertanyaans');

        // Build validation rules
        $rules = [];
        foreach ($pengajuanPrestasi->formulirPrestasi->pertanyaans as $pertanyaan) {
            $key = 'jawaban.' . $pertanyaan->id;
            if ($pertanyaan->wajib) {
                if ($pertanyaan->tipe === 'file') {
                    // File optional on update (keep existing if not provided)
                    $rules[$key] = 'nullable|file|max:5120|mimes:pdf,jpg,jpeg,png';
                } else {
                    $rules[$key] = 'required|string|max:1000';
                }
            } else {
                if ($pertanyaan->tipe === 'file') {
                    $rules[$key] = 'nullable|file|max:5120|mimes:pdf,jpg,jpeg,png';
                } else {
                    $rules[$key] = 'nullable|string|max:1000';
                }
            }
        }

        $validated = $request->validate($rules);

        DB::transaction(function () use ($validated, $pengajuanPrestasi) {
            $pengajuanPrestasi->update([
                'status' => 'menunggu',
                'catatan_verifikator' => null,
            ]);

            foreach ($pengajuanPrestasi->formulirPrestasi->pertanyaans as $pertanyaan) {
                $jawabanModel = $pengajuanPrestasi->jawabans->where('formulir_pertanyaan_id', $pertanyaan->id)->first();
                $jawabanValue = $validated['jawaban'][$pertanyaan->id] ?? null;

                if ($pertanyaan->tipe === 'file' && $jawabanValue) {
                    // Delete old file
                    if ($jawabanModel->file_path) {
                        Storage::disk('public')->delete($jawabanModel->file_path);
                    }
                    $filePath = $jawabanValue->store('prestasi/' . $pengajuanPrestasi->id, 'public');
                    $jawabanModel->update(['file_path' => $filePath]);
                } elseif ($pertanyaan->tipe !== 'file') {
                    $jawabanModel->update(['jawaban' => $jawabanValue]);
                }
            }
        });

        return redirect()->route('mahasiswa.prestasi.index')
            ->with('success', 'Pengajuan prestasi berhasil diperbarui!');
    }
}
