@extends('layouts.dashboard')

@section('title', 'Detail Pengajuan')
@section('page-title', 'Detail Pengajuan')
@section('page-description', $pengajuanPrestasi->formulirPrestasi->judul)

@section('content')
    <div class="max-w-3xl mx-auto">
        <!-- Back Button -->
        <a href="{{ route('mahasiswa.prestasi.index') }}"
            class="inline-flex items-center gap-2 text-gray-600 hover:text-simawa-600 mb-6 text-sm">
            <ion-icon name="arrow-back-outline"></ion-icon>
            Kembali ke Daftar
        </a>

        <!-- Status Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-gray-800">Status Pengajuan</h3>
                    <span
                        class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium {{ $pengajuanPrestasi->status_badge }}">
                        {{ $pengajuanPrestasi->status_label }}
                    </span>
                </div>

                <div class="grid sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500">Tanggal Pengajuan</p>
                        <p class="font-medium text-gray-800">{{ $pengajuanPrestasi->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    @if($pengajuanPrestasi->verified_at)
                        <div>
                            <p class="text-gray-500">Tanggal Verifikasi</p>
                            <p class="font-medium text-gray-800">{{ $pengajuanPrestasi->verified_at->format('d M Y, H:i') }}</p>
                        </div>
                    @endif
                </div>

                @if($pengajuanPrestasi->catatan_verifikator)
                    <div class="mt-4 p-4 bg-orange-50 border border-orange-200 rounded-lg">
                        <p class="text-sm font-medium text-orange-800 mb-1">Catatan Verifikator:</p>
                        <p class="text-sm text-orange-700">{{ $pengajuanPrestasi->catatan_verifikator }}</p>
                    </div>
                @endif

                @if($pengajuanPrestasi->status === 'revisi')
                    <div class="mt-4">
                        <a href="{{ route('mahasiswa.prestasi.edit', $pengajuanPrestasi) }}"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-all text-sm font-medium">
                            <ion-icon name="create-outline"></ion-icon>
                            Edit & Submit Ulang
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Detail Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-simawa-500 to-simawa-700 p-6 text-white">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
                        <ion-icon name="trophy-outline" class="text-3xl"></ion-icon>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold">{{ $pengajuanPrestasi->formulirPrestasi->judul }}</h2>
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-white/20 mt-2">
                            {{ $pengajuanPrestasi->formulirPrestasi->kategoriPrestasi->nama }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Answers -->
            <div class="p-6 space-y-6">
                @foreach($pengajuanPrestasi->formulirPrestasi->pertanyaans as $index => $pertanyaan)
                    @php
                        $jawaban = $pengajuanPrestasi->jawabans->where('formulir_pertanyaan_id', $pertanyaan->id)->first();
                    @endphp
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">
                            {{ $index + 1 }}. {{ $pertanyaan->pertanyaan }}
                        </label>

                        @if($pertanyaan->tipe === 'file' && $jawaban && $jawaban->file_path)
                            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                                <ion-icon name="document-outline" class="text-xl text-gray-500"></ion-icon>
                                <a href="{{ Storage::url($jawaban->file_path) }}" target="_blank"
                                    class="text-sm text-simawa-600 hover:text-simawa-700 font-medium">
                                    Lihat File
                                </a>
                            </div>
                        @else
                            <p class="px-4 py-2.5 bg-gray-50 rounded-lg text-sm text-gray-700">
                                {{ $jawaban->jawaban ?? '-' }}
                            </p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection