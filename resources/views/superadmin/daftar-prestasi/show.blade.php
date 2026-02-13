@extends('layouts.dashboard')

@section('title', 'Detail Prestasi')
@section('page-title', 'Detail Prestasi')
@section('page-description', 'Detail prestasi mahasiswa')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Back Button -->
        <a href="{{ url()->previous() }}"
            class="inline-flex items-center gap-2 text-gray-600 hover:text-simawa-600 mb-6 text-sm">
            <ion-icon name="arrow-back-outline"></ion-icon>
            Kembali
        </a>

        <div class="grid lg:grid-cols-3 gap-6">
            <!-- Left Column: Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Mahasiswa Info -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="font-semibold text-gray-800">Informasi Mahasiswa</h3>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-14 h-14 bg-gradient-to-br from-simawa-100 to-simawa-200 rounded-full flex items-center justify-center">
                                <ion-icon name="person-outline" class="text-simawa-600 text-2xl"></ion-icon>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">{{ $pengajuanPrestasi->mahasiswa->name }}</p>
                                <p class="text-sm text-gray-500">{{ $pengajuanPrestasi->mahasiswa->email }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Prestasi Details -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-[#194581] p-6 text-white">
                        <div class="flex items-center gap-4">
                            <div>
                                <h2 class="text-xl font-semibold">{{ $pengajuanPrestasi->formulirPrestasi->judul }}</h2>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-white/20 mt-2">
                                    {{ $pengajuanPrestasi->formulirPrestasi->kategoriPrestasi->nama }}
                                </span>
                            </div>
                        </div>
                    </div>

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

            <!-- Right Column: Status -->
            <div class="space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="font-semibold text-gray-800">Status</h3>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center justify-center mb-4">
                            <span
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium bg-green-100 text-green-700">
                                <ion-icon name="checkmark-circle"></ion-icon>
                                Terverifikasi
                            </span>
                        </div>
                        <div class="text-sm text-gray-600 space-y-2">
                            <div class="flex justify-between">
                                <span>Diajukan:</span>
                                <span class="font-medium">{{ $pengajuanPrestasi->created_at->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Diverifikasi:</span>
                                <span class="font-medium">{{ $pengajuanPrestasi->verified_at->format('d M Y') }}</span>
                            </div>
                            @if($pengajuanPrestasi->verifikator)
                                <div class="flex justify-between">
                                    <span>Oleh:</span>
                                    <span class="font-medium">{{ $pengajuanPrestasi->verifikator->name }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection