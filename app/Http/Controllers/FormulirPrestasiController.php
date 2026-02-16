<?php

namespace App\Http\Controllers;

use App\Models\FormulirPertanyaan;
use App\Models\FormulirPrestasi;
use App\Models\KategoriPrestasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FormulirPrestasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tahun = $request->get('tahun');
        
        // Get unique years for filter (dari kolom tahun)
        $tahunList = FormulirPrestasi::select('tahun')
            ->distinct()
            ->whereNotNull('tahun')
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');
        
        // Get all formulirs for counting
        $allFormulirs = FormulirPrestasi::with(['kategoriPrestasi', 'pertanyaans'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Filter by tahun if selected
        if ($tahun) {
            $formulirs = $allFormulirs->filter(function ($item) use ($tahun) {
                return $item->tahun == $tahun;
            });
        } else {
            $formulirs = $allFormulirs;
        }

        $categories = KategoriPrestasi::where('is_active', true)->get();

        return view('superadmin.formulir-prestasi.index', compact('formulirs', 'categories', 'tahunList', 'tahun', 'allFormulirs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'kategori_prestasi_id' => 'required|exists:kategori_prestasis,id',
            'tahun' => 'required|integer|min:2020|max:2030',
            'pertanyaans' => 'required|array|min:1',
            'pertanyaans.*.pertanyaan' => 'required|string|max:255',
            'pertanyaans.*.tipe' => 'required|in:text,dropdown,file',
            'pertanyaans.*.opsi' => 'nullable|array',
            'pertanyaans.*.wajib' => 'nullable',
        ]);

        DB::transaction(function () use ($validated) {
            $formulir = FormulirPrestasi::create([
                'judul' => $validated['judul'],
                'kategori_prestasi_id' => $validated['kategori_prestasi_id'],
                'tahun' => $validated['tahun'],
            ]);

            foreach ($validated['pertanyaans'] as $index => $pertanyaan) {
                // Convert string 'true'/'false' to boolean
                $wajib = isset($pertanyaan['wajib']) ? filter_var($pertanyaan['wajib'], FILTER_VALIDATE_BOOLEAN) : true;

                FormulirPertanyaan::create([
                    'formulir_prestasi_id' => $formulir->id,
                    'pertanyaan' => $pertanyaan['pertanyaan'],
                    'tipe' => $pertanyaan['tipe'],
                    'opsi' => $pertanyaan['opsi'] ?? null,
                    'wajib' => $wajib,
                    'urutan' => $index,
                ]);
            }
        });

        return redirect()->route('superadmin.formulir-prestasi.index')
            ->with('success', 'Formulir prestasi berhasil ditambahkan!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FormulirPrestasi $formulirPrestasi)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'kategori_prestasi_id' => 'required|exists:kategori_prestasis,id',
            'tahun' => 'required|integer|min:2020|max:2030',
            'pertanyaans' => 'required|array|min:1',
            'pertanyaans.*.pertanyaan' => 'required|string|max:255',
            'pertanyaans.*.tipe' => 'required|in:text,dropdown,file',
            'pertanyaans.*.opsi' => 'nullable|array',
            'pertanyaans.*.wajib' => 'nullable',
        ]);

        DB::transaction(function () use ($validated, $formulirPrestasi) {
            $formulirPrestasi->update([
                'judul' => $validated['judul'],
                'kategori_prestasi_id' => $validated['kategori_prestasi_id'],
                'tahun' => $validated['tahun'],
            ]);

            // Delete existing questions and recreate
            $formulirPrestasi->pertanyaans()->delete();

            foreach ($validated['pertanyaans'] as $index => $pertanyaan) {
                // Convert string 'true'/'false' to boolean
                $wajib = isset($pertanyaan['wajib']) ? filter_var($pertanyaan['wajib'], FILTER_VALIDATE_BOOLEAN) : true;

                FormulirPertanyaan::create([
                    'formulir_prestasi_id' => $formulirPrestasi->id,
                    'pertanyaan' => $pertanyaan['pertanyaan'],
                    'tipe' => $pertanyaan['tipe'],
                    'opsi' => $pertanyaan['opsi'] ?? null,
                    'wajib' => $wajib,
                    'urutan' => $index,
                ]);
            }
        });

        return redirect()->route('superadmin.formulir-prestasi.index')
            ->with('success', 'Formulir prestasi berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FormulirPrestasi $formulirPrestasi)
    {
        $formulirPrestasi->delete();

        return redirect()->route('superadmin.formulir-prestasi.index')
            ->with('success', 'Formulir prestasi berhasil dihapus!');
    }

    /**
     * Get formulir data as JSON for AJAX
     */
    public function show(FormulirPrestasi $formulirPrestasi)
    {
        $formulirPrestasi->load('pertanyaans');
        return response()->json($formulirPrestasi);
    }

    public function toggleVisibility($id)
    {
        $formulir = FormulirPrestasi::findOrFail($id);
        $formulir->is_active = !$formulir->is_active;
        $formulir->save();

        $status = $formulir->is_active ? 'diaktifkan' : 'disembunyikan';
        return redirect()->back()->with('success', "Formulir prestasi berhasil {$status}!");
    }
}
