<?php

namespace App\Http\Controllers;

use App\Models\JenisSertifikat;
use App\Models\PengajuanSertifikat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class PengajuanSertifikatController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = PengajuanSertifikat::with('jenisSertifikat')
                ->where('mahasiswa_id', Auth::id())
                ->orderBy('created_at', 'desc');

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('tanggal_terbit', function ($row) {
                    return $row->tanggal_terbit ? $row->tanggal_terbit->format('d M Y') : '-';
                })
                ->addColumn('jenis_sertifikat', function ($row) {
                    return $row->jenisSertifikat ? $row->jenisSertifikat->nama : '-';
                })
                ->editColumn('status', function ($row) {
                    return '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ' . $row->status_badge . '">' . $row->status_label . '</span>';
                })
                ->addColumn('action', function ($row) {
                    $detailBtn = '<button onclick="showDetail(\'' . route('mahasiswa.sertifikat.show', $row->id) . '\')" class="inline-flex items-center justify-center w-8 h-8 bg-simawa-50 text-simawa-600 rounded-lg hover:bg-simawa-600 hover:text-white transition-all" title="Detail"><ion-icon name="eye-outline"></ion-icon></button>';
                    
                    $editBtn = '';
                    if ($row->status !== 'diterima') {
                        $editBtn = '<button onclick="showEdit(\'' . route('mahasiswa.sertifikat.edit', $row->id) . '\')" class="inline-flex items-center justify-center w-8 h-8 bg-orange-50 text-orange-600 rounded-lg hover:bg-orange-600 hover:text-white transition-all" title="Edit"><ion-icon name="create-outline"></ion-icon></button>';
                    }

                    return '<div class="flex gap-2 justify-center">' . $detailBtn . $editBtn . '</div>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('mahasiswa.sertifikat.index');
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

        if (request()->ajax()) {
            return view('mahasiswa.sertifikat.show_partial', compact('sertifikat'));
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
        
        if (request()->ajax()) {
            return view('mahasiswa.sertifikat.edit_partial', compact('sertifikat', 'jenisSertifikats'));
        }

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

        if ($request->ajax()) {
            return response()->json(['message' => 'Pengajuan berhasil diperbarui']);
        }

        return redirect()->route('mahasiswa.sertifikat.index')
            ->with('success', 'Pengajuan sertifikat berhasil diperbarui');
    }
}
