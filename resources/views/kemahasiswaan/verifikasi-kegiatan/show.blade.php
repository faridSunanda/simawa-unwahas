@extends('layouts.dashboard')

@section('title', 'Detail Verifikasi Pendaftaran Kegiatan')
@section('page-title', 'Detail Verifikasi Pendaftaran Kegiatan')
@section('page-description', 'Detail pendaftaran kegiatan mahasiswa')

@section('content')
    <div class="mb-6">
        <a href="{{ route('kemahasiswaan.verifikasi-kegiatan.index') }}"
            class="text-simawa-600 hover:text-simawa-700 flex items-center gap-2 text-sm font-medium mb-4">
            <ion-icon name="arrow-back-outline"></ion-icon>
            Kembali ke Daftar
        </a>
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-gray-800">Detail Pendaftaran Kegiatan</h2>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $kegiatanPeserta->status_badge }}">
                {{ $kegiatanPeserta->status_label }}
            </span>
        </div>
    </div>

    <!-- Success/Error Alert -->
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center gap-3">
            <ion-icon name="checkmark-circle" class="text-xl flex-shrink-0"></ion-icon>
            <span class="text-sm">{{ session('success') }}</span>
        </div>
    @endif
    @if($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg">
            <ul class="list-disc list-inside text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <!-- Data Mahasiswa -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 pb-3 border-b border-gray-100 flex items-center gap-2">
                    <ion-icon name="person-outline" class="text-simawa-600"></ion-icon>Data Mahasiswa
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Nama Mahasiswa</p>
                        <p class="font-medium text-gray-800">{{ $kegiatanPeserta->mahasiswa->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">NIM / NPM</p>
                        <p class="font-medium text-gray-800">{{ $kegiatanPeserta->mahasiswa->nim }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Fakultas</p>
                        <p class="font-medium text-gray-800">{{ $kegiatanPeserta->mahasiswa->fakultas }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Program Studi</p>
                        <p class="font-medium text-gray-800">{{ $kegiatanPeserta->mahasiswa->prodi }}</p>
                    </div>
                </div>
            </div>

            <!-- Detail Kegiatan -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 pb-3 border-b border-gray-100 flex items-center gap-2">
                    <ion-icon name="calendar-outline" class="text-simawa-600"></ion-icon>Detail Kegiatan
                </h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Nama Kegiatan</p>
                        <p class="font-medium text-gray-800">{{ $kegiatanPeserta->kegiatan->nama }}</p>
                    </div>
                    @if($kegiatanPeserta->jawabans->count() > 0)
                        <div class="mt-6">
                            <h4 class="font-medium text-gray-700 mb-3">Jawaban Formulir Pendafataran:</h4>
                            <div class="space-y-4">
                                @foreach($kegiatanPeserta->jawabans as $jawaban)
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <p class="text-sm font-medium text-gray-700 mb-2">{{ $jawaban->pertanyaan->pertanyaan }}</p>
                                        
                                        @if($jawaban->pertanyaan->tipe === 'file')
                                            <a href="{{ Storage::url($jawaban->jawaban) }}" target="_blank"
                                               class="inline-flex items-center gap-2 px-3 py-1.5 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50">
                                                <ion-icon name="download-outline"></ion-icon>
                                                Lihat Berkas / Unduh
                                            </a>
                                        @else
                                            <p class="text-gray-600 text-sm">{{ $jawaban->jawaban }}</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="mt-4 p-4 bg-gray-50 rounded-lg text-sm text-gray-500 text-center">
                            Tidak ada jawaban formulir untuk kegiatan ini.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            @if($kegiatanPeserta->status === 'menunggu')
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden sticky top-6">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 pb-3 border-b border-gray-100 flex items-center gap-2">
                        <ion-icon name="checkmark-done-circle-outline" class="text-simawa-600"></ion-icon>
                        Aksi Verifikasi
                    </h3>
                    
                    <form action="{{ route('kemahasiswaan.verifikasi-kegiatan.verify', $kegiatanPeserta) }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <div class="grid grid-cols-2 gap-3">
                            <label class="relative cursor-pointer">
                                <input type="radio" name="status" value="ditolak" class="peer sr-only" required>
                                <div class="p-4 rounded-xl border-2 border-gray-200 transition-all hover:bg-red-50 peer-checked:border-red-500 peer-checked:bg-red-50 flex flex-col items-center justify-center gap-2 group text-gray-500 peer-checked:text-red-700">
                                    <ion-icon name="close-circle" class="text-3xl transition-transform group-hover:scale-110"></ion-icon>
                                    <span class="font-bold text-sm">Tolak</span>
                                </div>
                            </label>

                            <label class="relative cursor-pointer">
                                <input type="radio" name="status" value="terdaftar" class="peer sr-only" required>
                                <div class="p-4 rounded-xl border-2 border-gray-200 transition-all hover:bg-green-50 peer-checked:border-green-500 peer-checked:bg-green-50 flex flex-col items-center justify-center gap-2 group text-gray-500 peer-checked:text-green-700">
                                    <ion-icon name="checkmark-circle" class="text-3xl transition-transform group-hover:scale-110"></ion-icon>
                                    <span class="font-bold text-sm">Terima</span>
                                </div>
                            </label>
                        </div>

                        <button type="submit" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-simawa-600 hover:bg-simawa-700 text-white rounded-lg transition-colors font-medium">
                            <ion-icon name="save-outline" class="text-xl"></ion-icon>
                            Simpan Verifikasi
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection
