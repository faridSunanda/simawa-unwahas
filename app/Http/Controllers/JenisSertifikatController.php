<?php

namespace App\Http\Controllers;

use App\Models\JenisSertifikat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JenisSertifikatController extends Controller
{
    private function getRoutePrefix()
    {
        return Auth::user()->role?->name === 'superadmin' ? 'superadmin' : 'kemahasiswaan';
    }

    public function index()
    {
        $jenisSertifikats = JenisSertifikat::orderBy('created_at', 'desc')->get();
        $viewPath = Auth::user()->role?->name === 'superadmin'
            ? 'superadmin.jenis-sertifikat.index'
            : 'kemahasiswaan.jenis-sertifikat.index';
        return view($viewPath, compact('jenisSertifikats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        JenisSertifikat::create($validated);

        return redirect()->route($this->getRoutePrefix() . '.jenis-sertifikat.index')
            ->with('success', 'Jenis sertifikat berhasil ditambahkan');
    }

    public function update(Request $request, JenisSertifikat $jenisSertifikat)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $jenisSertifikat->update($validated);

        return redirect()->route($this->getRoutePrefix() . '.jenis-sertifikat.index')
            ->with('success', 'Jenis sertifikat berhasil diperbarui');
    }

    public function destroy(JenisSertifikat $jenisSertifikat)
    {
        $jenisSertifikat->delete();

        return redirect()->route($this->getRoutePrefix() . '.jenis-sertifikat.index')
            ->with('success', 'Jenis sertifikat berhasil dihapus');
    }
}
