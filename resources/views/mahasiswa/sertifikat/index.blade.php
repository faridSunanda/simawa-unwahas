@extends('layouts.dashboard')

@section('title', 'Sertifikat Saya')
@section('page-title', 'Sertifikat Saya')
@section('page-description', 'Kelola pengajuan sertifikat Anda')

@section('content')
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h3 class="text-lg font-semibold text-gray-800">Riwayat Pengajuan Sertifikat</h3>
            <p class="text-sm text-gray-500 mt-1">Daftar sertifikat yang sudah diajukan</p>
        </div>
        <a href="{{ route('mahasiswa.sertifikat.create') }}"
            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-simawa-500 to-simawa-700 text-white rounded-lg hover:from-simawa-600 hover:to-simawa-800 transition-all shadow-lg shadow-simawa-500/25 text-sm font-medium">
            <ion-icon name="add-circle-outline" class="text-lg"></ion-icon>
            Ajukan Sertifikat
        </a>
    </div>

    <!-- Success/Error Alert -->
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center gap-3">
            <ion-icon name="checkmark-circle" class="text-xl flex-shrink-0"></ion-icon>
            <span class="text-sm">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg flex items-center gap-3">
            <ion-icon name="alert-circle" class="text-xl flex-shrink-0"></ion-icon>
            <span class="text-sm">{{ session('error') }}</span>
        </div>
    @endif

    @if($pengajuans->count() > 0)
        <!-- Card View -->
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            @foreach($pengajuans as $pengajuan)
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 hover:shadow-md transition-all">
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <h4 class="font-semibold text-gray-800 line-clamp-1">{{ $pengajuan->nama_sertifikat }}</h4>
                        <span
                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium flex-shrink-0 {{ $pengajuan->status_badge }}">
                            {{ $pengajuan->status_label }}
                        </span>
                    </div>
                    <div class="space-y-2 text-sm mb-4">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500">Jenis:</span>
                            <span class="text-gray-800 font-medium">{{ $pengajuan->jenisSertifikat->nama }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500">Penerbit:</span>
                            <span class="text-gray-800">{{ $pengajuan->penerbit }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500">Tanggal Terbit:</span>
                            <span class="text-gray-800">{{ $pengajuan->tanggal_terbit->format('d M Y') }}</span>
                        </div>
                    </div>
                    @if($pengajuan->catatan_verifikasi)
                        <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-500 mb-1">Catatan Verifikasi:</p>
                            <p class="text-sm text-gray-700">{{ $pengajuan->catatan_verifikasi }}</p>
                        </div>
                    @endif
                    <div class="pt-3 border-t border-gray-100 flex gap-2">
                        <a href="{{ route('mahasiswa.sertifikat.show', $pengajuan) }}"
                            class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-simawa-600 hover:bg-simawa-700 text-white rounded-lg transition-colors text-sm font-medium">
                            <ion-icon name="eye-outline"></ion-icon>
                            Detail
                        </a>
                        @if($pengajuan->status !== 'diterima')
                            <a href="{{ route('mahasiswa.sertifikat.edit', $pengajuan) }}"
                                class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 border border-simawa-500 text-simawa-600 hover:bg-simawa-50 rounded-lg transition-colors text-sm font-medium">
                                <ion-icon name="create-outline"></ion-icon>
                                Edit
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 py-12 text-center">
            <ion-icon name="ribbon-outline" class="text-6xl text-gray-300 mb-4"></ion-icon>
            <p class="text-gray-500 mb-2">Belum ada pengajuan sertifikat</p>
            <a href="{{ route('mahasiswa.sertifikat.create') }}"
                class="text-simawa-500 hover:text-simawa-700 font-medium text-sm">
                + Ajukan Sertifikat Baru
            </a>
        </div>
    @endif
@endsection