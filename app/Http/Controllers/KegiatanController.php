<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\KegiatanPeserta;
use App\Models\KegiatanRundown;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\TemplateProcessor;

class KegiatanController extends Controller
{
    /**
     * Display a listing of kegiatan.
     */
    public function index()
    {
        $kegiatans = Kegiatan::withCount(['peserta', 'rundowns'])
            ->orderBy('waktu_mulai', 'desc')
            ->get();

        return view('superadmin.kegiatan.index', compact('kegiatans'));
    }

    /**
     * Store a newly created kegiatan.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'detail' => 'nullable|string',
            'waktu_mulai' => 'required|date',
            'waktu_selesai' => 'required|date|after_or_equal:waktu_mulai',
            'tampilkan' => 'boolean',
            'pendaftaran' => 'required|in:buka,tutup',
        ]);

        $validated['tampilkan'] = $request->boolean('tampilkan');

        Kegiatan::create($validated);

        return redirect()->route('superadmin.kegiatan.index')
            ->with('success', 'Kegiatan berhasil ditambahkan');
    }

    /**
     * Update the specified kegiatan.
     */
    public function update(Request $request, Kegiatan $kegiatan)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'detail' => 'nullable|string',
            'waktu_mulai' => 'required|date',
            'waktu_selesai' => 'required|date|after_or_equal:waktu_mulai',
            'tampilkan' => 'boolean',
            'pendaftaran' => 'required|in:buka,tutup',
        ]);

        $validated['tampilkan'] = $request->boolean('tampilkan');

        $kegiatan->update($validated);

        return redirect()->route('superadmin.kegiatan.index')
            ->with('success', 'Kegiatan berhasil diperbarui');
    }

    /**
     * Remove the specified kegiatan.
     */
    public function destroy(Kegiatan $kegiatan)
    {
        // Delete sertifikat template if exists
        if ($kegiatan->sertifikat_template) {
            Storage::disk('public')->delete($kegiatan->sertifikat_template);
        }

        $kegiatan->delete();

        return redirect()->route('superadmin.kegiatan.index')
            ->with('success', 'Kegiatan berhasil dihapus');
    }

    /**
     * Display peserta for a kegiatan.
     */
    public function peserta(Kegiatan $kegiatan)
    {
        $kegiatan->load(['peserta.mahasiswa.user']);

        return view('superadmin.kegiatan.peserta', compact('kegiatan'));
    }

    /**
     * Update presensi status for peserta.
     */
    public function updatePresensi(Request $request, KegiatanPeserta $peserta)
    {
        $validated = $request->validate([
            'status_presensi' => 'required|in:hadir,belum_hadir',
        ]);

        $peserta->update([
            'status_presensi' => $validated['status_presensi'],
            'waktu_presensi' => $validated['status_presensi'] === 'hadir' ? now() : null,
        ]);

        return redirect()->back()->with('success', 'Presensi berhasil diperbarui');
    }

    /**
     * Display QR scanner page for presensi.
     */
    public function scanPresensi(Kegiatan $kegiatan)
    {
        $kegiatan->load(['peserta.mahasiswa.user']);

        return view('superadmin.kegiatan.scan-presensi', compact('kegiatan'));
    }

    /**
     * Process QR code data for presensi.
     */
    public function processQrPresensi(Request $request, Kegiatan $kegiatan)
    {
        $validated = $request->validate([
            'mahasiswa_id' => 'required|uuid',
        ]);

        // Find peserta by mahasiswa_id in this kegiatan
        $peserta = KegiatanPeserta::where('kegiatan_id', $kegiatan->id)
            ->where('mahasiswa_id', $validated['mahasiswa_id'])
            ->with('mahasiswa.user')
            ->first();

        if (!$peserta) {
            return response()->json([
                'success' => false,
                'message' => 'Mahasiswa tidak terdaftar dalam kegiatan ini',
            ], 404);
        }

        // Check if already marked as present
        if ($peserta->status_presensi === 'hadir') {
            return response()->json([
                'success' => false,
                'message' => 'Mahasiswa sudah tercatat hadir',
                'data' => [
                    'nama' => $peserta->mahasiswa->user->name ?? 'Unknown',
                    'nim' => $peserta->mahasiswa->nim ?? '-',
                    'waktu_presensi' => $peserta->waktu_presensi?->format('d M Y, H:i'),
                ],
            ], 400);
        }

        // Update presensi status
        $peserta->update([
            'status_presensi' => 'hadir',
            'waktu_presensi' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Presensi berhasil dicatat',
            'data' => [
                'nama' => $peserta->mahasiswa->user->name ?? 'Unknown',
                'nim' => $peserta->mahasiswa->nim ?? '-',
                'waktu_presensi' => $peserta->waktu_presensi?->format('d M Y, H:i'),
            ],
        ]);
    }

    /**
     * Display rundown for a kegiatan.
     */
    public function rundown(Kegiatan $kegiatan)
    {
        $kegiatan->load('rundowns');

        return view('superadmin.kegiatan.rundown', compact('kegiatan'));
    }

    /**
     * Store a new rundown item.
     */
    public function storeRundown(Request $request, Kegiatan $kegiatan)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'waktu_mulai' => 'required|date|after_or_equal:' . $kegiatan->waktu_mulai->format('Y-m-d\TH:i'),
            'waktu_selesai' => 'required|date|after_or_equal:waktu_mulai|before_or_equal:' . $kegiatan->waktu_selesai->format('Y-m-d\TH:i'),
        ], [
            'waktu_mulai.after_or_equal' => 'Waktu mulai tidak boleh sebelum waktu mulai kegiatan (' . $kegiatan->waktu_mulai->format('d M Y H:i') . ')',
            'waktu_selesai.before_or_equal' => 'Waktu selesai tidak boleh melewati waktu selesai kegiatan (' . $kegiatan->waktu_selesai->format('d M Y H:i') . ')',
        ]);

        $kegiatan->rundowns()->create($validated);

        return redirect()->back()->with('success', 'Rundown berhasil ditambahkan');
    }

    /**
     * Update a rundown item.
     */
    public function updateRundown(Request $request, KegiatanRundown $rundown)
    {
        $kegiatan = $rundown->kegiatan;

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'waktu_mulai' => 'required|date|after_or_equal:' . $kegiatan->waktu_mulai->format('Y-m-d\TH:i'),
            'waktu_selesai' => 'required|date|after_or_equal:waktu_mulai|before_or_equal:' . $kegiatan->waktu_selesai->format('Y-m-d\TH:i'),
        ], [
            'waktu_mulai.after_or_equal' => 'Waktu mulai tidak boleh sebelum waktu mulai kegiatan (' . $kegiatan->waktu_mulai->format('d M Y H:i') . ')',
            'waktu_selesai.before_or_equal' => 'Waktu selesai tidak boleh melewati waktu selesai kegiatan (' . $kegiatan->waktu_selesai->format('d M Y H:i') . ')',
        ]);

        $rundown->update($validated);

        return redirect()->back()->with('success', 'Rundown berhasil diperbarui');
    }

    /**
     * Delete a rundown item.
     */
    public function destroyRundown(KegiatanRundown $rundown)
    {
        $rundown->delete();

        return redirect()->back()->with('success', 'Rundown berhasil dihapus');
    }

    /**
     * Display sertifikat template for a kegiatan.
     */
    public function sertifikat(Kegiatan $kegiatan)
    {
        return view('superadmin.kegiatan.sertifikat', compact('kegiatan'));
    }

    /**
     * Upload sertifikat template.
     */
    public function uploadSertifikat(Request $request, Kegiatan $kegiatan)
    {
        $request->validate([
            'sertifikat_template' => 'required|mimes:docx|max:5120',
        ], [
            'sertifikat_template.mimes' => 'File harus berupa dokumen Word (.docx)',
        ]);

        // Delete old template if exists
        if ($kegiatan->sertifikat_template) {
            Storage::disk('public')->delete($kegiatan->sertifikat_template);
        }

        $path = $request->file('sertifikat_template')->store('sertifikat-templates', 'public');

        $kegiatan->update(['sertifikat_template' => $path]);

        return redirect()->back()->with('success', 'Template sertifikat berhasil diunggah');
    }

    /**
     * Delete sertifikat template.
     */
    public function deleteSertifikat(Kegiatan $kegiatan)
    {
        if ($kegiatan->sertifikat_template) {
            Storage::disk('public')->delete($kegiatan->sertifikat_template);
            $kegiatan->update(['sertifikat_template' => null]);
        }

        return redirect()->back()->with('success', 'Template sertifikat berhasil dihapus');
    }



    /**
     * Preview sertifikat with sample name.
     */
    public function previewSertifikat(Request $request, Kegiatan $kegiatan)
    {
        if (!$kegiatan->sertifikat_template) {
            abort(404, 'Template sertifikat belum diupload');
        }

        $sampleName = $request->input('name', 'Hairudin Farid Sunanda');
        $sampleNim = $request->input('nim', '1234567890');
        $pdfContent = $this->generateSertifikatPdf($kegiatan, $sampleName, $sampleNim);

        return response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
        ]);
    }

    /**
     * Download sertifikat for specific peserta.
     */
    public function downloadSertifikatPeserta(KegiatanPeserta $peserta)
    {
        $kegiatan = $peserta->kegiatan;

        if (!$kegiatan->sertifikat_template) {
            return redirect()->back()->with('error', 'Template sertifikat belum diupload');
        }

        if ($peserta->status_presensi !== 'hadir') {
            return redirect()->back()->with('error', 'Peserta belum hadir, tidak dapat generate sertifikat');
        }

        $peserta->load('mahasiswa.user');
        $nama = $peserta->mahasiswa->user->name ?? 'Peserta';
        $nim = $peserta->mahasiswa->nim ?? '-';

        $safeNama = str_replace(' ', '_', $nama);
        $safeKegiatan = str_replace(' ', '_', $kegiatan->nama);

        $pdfContent = $this->generateSertifikatPdf($kegiatan, $nama, $nim);
        $filename = 'Sertifikat_' . $safeNama . '_' . $safeKegiatan . '.pdf';

        return response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Generate sertifikat PDF from DOCX template with placeholder replacement.
     */
    private function generateSertifikatPdf(Kegiatan $kegiatan, string $name, string $nim = '-'): string
    {
        $templatePath = Storage::disk('public')->path($kegiatan->sertifikat_template);

        // Create temp file for processed DOCX
        $tempDocx = tempnam(sys_get_temp_dir(), 'cert_') . '.docx';
        $tempPdf = tempnam(sys_get_temp_dir(), 'cert_') . '.pdf';

        try {
            // Load and process template with placeholders
            $templateProcessor = new TemplateProcessor($templatePath);

            // Replace placeholders
            $templateProcessor->setValue('NAMA_PESERTA', $name);

            // Save processed DOCX
            $templateProcessor->saveAs($tempDocx);

            // Convert DOCX to PDF using LibreOffice (if available) or return DOCX as download
            $libreOfficePath = $this->findLibreOffice();

            if ($libreOfficePath) {
                // Convert using LibreOffice
                $outputDir = dirname($tempPdf);
                $command = escapeshellarg($libreOfficePath) . ' --headless --convert-to pdf --outdir ' . escapeshellarg($outputDir) . ' ' . escapeshellarg($tempDocx);
                exec($command, $output, $returnCode);

                $convertedPdf = str_replace('.docx', '.pdf', $tempDocx);

                if ($returnCode === 0 && file_exists($convertedPdf)) {
                    $pdfContent = file_get_contents($convertedPdf);
                    @unlink($convertedPdf);
                    return $pdfContent;
                }
            }

            // LibreOffice not available - throw exception with clear message
            throw new \RuntimeException('LibreOffice tidak tersedia di server. Silakan install LibreOffice untuk generate sertifikat PDF.');

        } finally {
            // Cleanup temp files
            @unlink($tempDocx);
            @unlink($tempPdf);
        }
    }

    /**
     * Find LibreOffice installation path.
     */
    private function findLibreOffice(): ?string
    {
        $possiblePaths = [
            // Windows
            'C:\\Program Files\\LibreOffice\\program\\soffice.exe',
            'C:\\Program Files (x86)\\LibreOffice\\program\\soffice.exe',
            // Linux
            '/usr/bin/libreoffice',
            '/usr/bin/soffice',
            // macOS
            '/Applications/LibreOffice.app/Contents/MacOS/soffice',
        ];

        foreach ($possiblePaths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }

        return null;
    }
}
