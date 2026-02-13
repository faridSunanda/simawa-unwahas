@extends('layouts.dashboard')

@section('title', 'Detail Sertifikat')
@section('page-title', 'Detail Sertifikat')
@section('page-description', 'Lihat detail pengajuan sertifikat')

@section('content')
    <div class="max-w-2xl mx-auto">
        <!-- Back Button -->
        <a href="{{ route('mahasiswa.sertifikat.index') }}"
            class="inline-flex items-center gap-2 text-gray-600 hover:text-simawa-600 mb-6 text-sm">
            <ion-icon name="arrow-back-outline"></ion-icon>
            Kembali ke Daftar
        </a>

        <!-- Detail Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">{{ $sertifikat->nama_sertifikat }}</h3>
                    <p class="text-sm text-gray-500 mt-1">{{ $sertifikat->jenisSertifikat->nama }}</p>
                </div>
                <span
                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $sertifikat->status_badge }}">
                    {{ $sertifikat->status_label }}
                </span>
            </div>

            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Nama Sertifikat</p>
                        <p class="text-sm font-medium text-gray-800">{{ $sertifikat->nama_sertifikat }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Name in English</p>
                        <p class="text-sm font-medium text-gray-800">{{ $sertifikat->nama_sertifikat_en ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Penerbit</p>
                        <p class="text-sm font-medium text-gray-800">{{ $sertifikat->penerbit }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Tanggal Terbit</p>
                        <p class="text-sm font-medium text-gray-800">{{ $sertifikat->tanggal_terbit->format('d F Y') }}</p>
                    </div>
                </div>

                @if($sertifikat->file_sertifikat)
                    <div class="pt-4 border-t border-gray-100">
                        <p class="text-xs text-gray-500 mb-2">File Sertifikat</p>
                        <a href="{{ Storage::url($sertifikat->file_sertifikat) }}" target="_blank"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm text-gray-700 transition-colors">
                            <ion-icon name="document-outline"></ion-icon>
                            Lihat File
                        </a>
                    </div>
                @endif

                @if($sertifikat->catatan_verifikasi)
                    <div class="pt-4 border-t border-gray-100">
                        <p class="text-xs text-gray-500 mb-2">Catatan Verifikasi</p>
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-700">{{ $sertifikat->catatan_verifikasi }}</p>
                        </div>
                    </div>
                @endif

                @if($sertifikat->verified_at)
                    <div class="pt-4 border-t border-gray-100">
                        <p class="text-xs text-gray-500 mb-1">Diverifikasi pada</p>
                        <p class="text-sm text-gray-700">{{ $sertifikat->verified_at->format('d F Y H:i') }} oleh
                            {{ $sertifikat->verifier->name ?? '-' }}</p>
                    </div>
                @endif
            </div>

            @if($sertifikat->status !== 'diterima')
                <div class="p-6 border-t border-gray-100 bg-gray-50">
                    <a href="{{ route('mahasiswa.sertifikat.edit', $sertifikat) }}"
                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-simawa-600 hover:bg-simawa-700 text-white rounded-lg transition-colors text-sm font-medium">
                        <ion-icon name="create-outline"></ion-icon>
                        Edit Pengajuan
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection