<?php

namespace App\Http\Controllers;

use App\Models\KategoriPrestasi;
use Illuminate\Http\Request;

class KategoriPrestasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = KategoriPrestasi::orderBy('created_at', 'desc')->get();
        return view('superadmin.kategori-prestasi.index', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        KategoriPrestasi::create($validated);

        return redirect()->route('superadmin.kategori-prestasi.index')
            ->with('success', 'Kategori prestasi berhasil ditambahkan!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KategoriPrestasi $kategoriPrestasi)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $kategoriPrestasi->update($validated);

        return redirect()->route('superadmin.kategori-prestasi.index')
            ->with('success', 'Kategori prestasi berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KategoriPrestasi $kategoriPrestasi)
    {
        $kategoriPrestasi->delete();

        return redirect()->route('superadmin.kategori-prestasi.index')
            ->with('success', 'Kategori prestasi berhasil dihapus!');
    }
}
