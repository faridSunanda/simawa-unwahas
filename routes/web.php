<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DaftarMahasiswaController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\KegiatanMahasiswaController;
use App\Http\Controllers\DaftarPrestasiController;
use App\Http\Controllers\FormulirPrestasiController;
use App\Http\Controllers\JenisSertifikatController;
use App\Http\Controllers\KategoriPrestasiController;
use App\Http\Controllers\PengajuanPrestasiController;
use App\Http\Controllers\PengajuanSertifikatController;
use App\Http\Controllers\VerifikasiPrestasiController;
use App\Http\Controllers\VerifikasiSertifikatController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', function () {
    return redirect()->route('login');
});

// Auth Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Dashboard Routes for each role
Route::middleware(['auth', 'role:superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard.superadmin');
    })->name('dashboard');

    // Role switching routes (superadmin only)
    Route::get('/switch-role/{role}', [AuthController::class, 'switchRole'])->name('switch-role');
    Route::get('/reset-role', [AuthController::class, 'resetRole'])->name('reset-role');

    // Kategori Prestasi CRUD
    Route::get('/kategori-prestasi', [KategoriPrestasiController::class, 'index'])->name('kategori-prestasi.index');
    Route::post('/kategori-prestasi', [KategoriPrestasiController::class, 'store'])->name('kategori-prestasi.store');
    Route::put('/kategori-prestasi/{kategoriPrestasi}', [KategoriPrestasiController::class, 'update'])->name('kategori-prestasi.update');
    Route::delete('/kategori-prestasi/{kategoriPrestasi}', [KategoriPrestasiController::class, 'destroy'])->name('kategori-prestasi.destroy');

    // Formulir Prestasi CRUD
    Route::get('/formulir-prestasi', [FormulirPrestasiController::class, 'index'])->name('formulir-prestasi.index');
    Route::get('/formulir-prestasi/{formulirPrestasi}', [FormulirPrestasiController::class, 'show'])->name('formulir-prestasi.show');
    Route::post('/formulir-prestasi', [FormulirPrestasiController::class, 'store'])->name('formulir-prestasi.store');
    Route::put('/formulir-prestasi/{formulirPrestasi}', [FormulirPrestasiController::class, 'update'])->name('formulir-prestasi.update');
    Route::delete('/formulir-prestasi/{formulirPrestasi}', [FormulirPrestasiController::class, 'destroy'])->name('formulir-prestasi.destroy');

    // Verifikasi Prestasi
    Route::get('/verifikasi-prestasi', [VerifikasiPrestasiController::class, 'index'])->name('verifikasi-prestasi.index');
    Route::get('/verifikasi-prestasi/{pengajuanPrestasi}', [VerifikasiPrestasiController::class, 'show'])->name('verifikasi-prestasi.show');
    Route::post('/verifikasi-prestasi/{pengajuanPrestasi}', [VerifikasiPrestasiController::class, 'verify'])->name('verifikasi-prestasi.verify');

    // Daftar Prestasi (ACC)
    Route::get('/daftar-prestasi', [DaftarPrestasiController::class, 'index'])->name('daftar-prestasi.index');
    Route::get('/daftar-prestasi/{pengajuanPrestasi}', [DaftarPrestasiController::class, 'show'])->name('daftar-prestasi.show');

    // Jenis Sertifikat CRUD (Master Data)
    Route::get('/jenis-sertifikat', [JenisSertifikatController::class, 'index'])->name('jenis-sertifikat.index');
    Route::post('/jenis-sertifikat', [JenisSertifikatController::class, 'store'])->name('jenis-sertifikat.store');
    Route::put('/jenis-sertifikat/{jenisSertifikat}', [JenisSertifikatController::class, 'update'])->name('jenis-sertifikat.update');
    Route::delete('/jenis-sertifikat/{jenisSertifikat}', [JenisSertifikatController::class, 'destroy'])->name('jenis-sertifikat.destroy');

    // Daftar Mahasiswa (View Only - Data from API)
    Route::get('/daftar-mahasiswa', [DaftarMahasiswaController::class, 'index'])->name('daftar-mahasiswa.index');
    Route::get('/daftar-mahasiswa/{mahasiswa}', [DaftarMahasiswaController::class, 'show'])->name('daftar-mahasiswa.show');

    // User Management
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::put('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');

    // Kegiatan CRUD
    Route::get('/kegiatan', [KegiatanController::class, 'index'])->name('kegiatan.index');
    Route::post('/kegiatan', [KegiatanController::class, 'store'])->name('kegiatan.store');
    Route::put('/kegiatan/{kegiatan}', [KegiatanController::class, 'update'])->name('kegiatan.update');
    Route::delete('/kegiatan/{kegiatan}', [KegiatanController::class, 'destroy'])->name('kegiatan.destroy');

    // Kegiatan - Peserta
    Route::get('/kegiatan/{kegiatan}/peserta', [KegiatanController::class, 'peserta'])->name('kegiatan.peserta');
    Route::put('/kegiatan/peserta/{peserta}/presensi', [KegiatanController::class, 'updatePresensi'])->name('kegiatan.peserta.presensi');

    // Kegiatan - QR Presensi
    Route::get('/kegiatan/{kegiatan}/scan-presensi', [KegiatanController::class, 'scanPresensi'])->name('kegiatan.scan-presensi');
    Route::post('/kegiatan/{kegiatan}/process-qr', [KegiatanController::class, 'processQrPresensi'])->name('kegiatan.process-qr');

    // Kegiatan - Rundown
    Route::get('/kegiatan/{kegiatan}/rundown', [KegiatanController::class, 'rundown'])->name('kegiatan.rundown');
    Route::post('/kegiatan/{kegiatan}/rundown', [KegiatanController::class, 'storeRundown'])->name('kegiatan.rundown.store');
    Route::put('/kegiatan/rundown/{rundown}', [KegiatanController::class, 'updateRundown'])->name('kegiatan.rundown.update');
    Route::delete('/kegiatan/rundown/{rundown}', [KegiatanController::class, 'destroyRundown'])->name('kegiatan.rundown.destroy');

    // Kegiatan - Sertifikat
    Route::get('/kegiatan/{kegiatan}/sertifikat', [KegiatanController::class, 'sertifikat'])->name('kegiatan.sertifikat');
    Route::post('/kegiatan/{kegiatan}/sertifikat', [KegiatanController::class, 'uploadSertifikat'])->name('kegiatan.sertifikat.upload');
    Route::delete('/kegiatan/{kegiatan}/sertifikat', [KegiatanController::class, 'deleteSertifikat'])->name('kegiatan.sertifikat.delete');
    Route::get('/kegiatan/{kegiatan}/sertifikat/preview', [KegiatanController::class, 'previewSertifikat'])->name('kegiatan.sertifikat.preview');
    Route::get('/kegiatan/peserta/{peserta}/download-sertifikat', [KegiatanController::class, 'downloadSertifikatPeserta'])->name('kegiatan.peserta.download-sertifikat');
});

Route::middleware(['auth', 'role:kemahasiswaan'])->prefix('kemahasiswaan')->name('kemahasiswaan.')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard.kemahasiswaan');
    })->name('dashboard');

    // Verifikasi Prestasi (same as superadmin)
    Route::get('/verifikasi-prestasi', [VerifikasiPrestasiController::class, 'index'])->name('verifikasi-prestasi.index');
    Route::get('/verifikasi-prestasi/{pengajuanPrestasi}', [VerifikasiPrestasiController::class, 'show'])->name('verifikasi-prestasi.show');
    Route::post('/verifikasi-prestasi/{pengajuanPrestasi}', [VerifikasiPrestasiController::class, 'verify'])->name('verifikasi-prestasi.verify');

    // Daftar Prestasi
    Route::get('/daftar-prestasi', [DaftarPrestasiController::class, 'index'])->name('daftar-prestasi.index');
    Route::get('/daftar-prestasi/{pengajuanPrestasi}', [DaftarPrestasiController::class, 'show'])->name('daftar-prestasi.show');

    // Jenis Sertifikat CRUD (Master Data)
    Route::get('/jenis-sertifikat', [JenisSertifikatController::class, 'index'])->name('jenis-sertifikat.index');
    Route::post('/jenis-sertifikat', [JenisSertifikatController::class, 'store'])->name('jenis-sertifikat.store');
    Route::put('/jenis-sertifikat/{jenisSertifikat}', [JenisSertifikatController::class, 'update'])->name('jenis-sertifikat.update');
    Route::delete('/jenis-sertifikat/{jenisSertifikat}', [JenisSertifikatController::class, 'destroy'])->name('jenis-sertifikat.destroy');
});

Route::middleware(['auth', 'role:pimpinan'])->prefix('pimpinan')->name('pimpinan.')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard.pimpinan');
    })->name('dashboard');
});

Route::middleware(['auth', 'role:dekan'])->prefix('dekan')->name('dekan.')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard.dekan');
    })->name('dashboard');
});

Route::middleware(['auth', 'role:kaprodi'])->prefix('kaprodi')->name('kaprodi.')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard.kaprodi');
    })->name('dashboard');

    // Verifikasi Sertifikat
    Route::get('/verifikasi-sertifikat', [VerifikasiSertifikatController::class, 'index'])->name('verifikasi-sertifikat.index');
    Route::get('/verifikasi-sertifikat/{pengajuanSertifikat}', [VerifikasiSertifikatController::class, 'show'])->name('verifikasi-sertifikat.show');
    Route::post('/verifikasi-sertifikat/{pengajuanSertifikat}/verify', [VerifikasiSertifikatController::class, 'verify'])->name('verifikasi-sertifikat.verify');
});

Route::middleware(['auth', 'role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard.mahasiswa');
    })->name('dashboard');

    // Pengajuan Prestasi
    Route::get('/prestasi', [PengajuanPrestasiController::class, 'index'])->name('prestasi.index');
    Route::get('/prestasi/create/{formulirPrestasi}', [PengajuanPrestasiController::class, 'create'])->name('prestasi.create');
    Route::post('/prestasi/{formulirPrestasi}', [PengajuanPrestasiController::class, 'store'])->name('prestasi.store');
    Route::get('/prestasi/{pengajuanPrestasi}', [PengajuanPrestasiController::class, 'show'])->name('prestasi.show');
    Route::get('/prestasi/{pengajuanPrestasi}/edit', [PengajuanPrestasiController::class, 'edit'])->name('prestasi.edit');
    Route::put('/prestasi/{pengajuanPrestasi}', [PengajuanPrestasiController::class, 'update'])->name('prestasi.update');

    // Pengajuan Sertifikat
    Route::get('/sertifikat', [PengajuanSertifikatController::class, 'index'])->name('sertifikat.index');
    Route::get('/sertifikat/create', [PengajuanSertifikatController::class, 'create'])->name('sertifikat.create');
    Route::post('/sertifikat', [PengajuanSertifikatController::class, 'store'])->name('sertifikat.store');
    Route::get('/sertifikat/{sertifikat}', [PengajuanSertifikatController::class, 'show'])->name('sertifikat.show');
    Route::get('/sertifikat/{sertifikat}/edit', [PengajuanSertifikatController::class, 'edit'])->name('sertifikat.edit');
    Route::put('/sertifikat/{sertifikat}', [PengajuanSertifikatController::class, 'update'])->name('sertifikat.update');

    // Kegiatan
    Route::get('/kegiatan', [KegiatanMahasiswaController::class, 'index'])->name('kegiatan.index');
    Route::get('/kegiatan-saya', [KegiatanMahasiswaController::class, 'kegiatanSaya'])->name('kegiatan.saya');
    Route::get('/kegiatan/{kegiatan}', [KegiatanMahasiswaController::class, 'show'])->name('kegiatan.show');
    Route::post('/kegiatan/{kegiatan}/daftar', [KegiatanMahasiswaController::class, 'daftar'])->name('kegiatan.daftar');
});

