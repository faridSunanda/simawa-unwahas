@extends('layouts.dashboard')

@section('title', 'Detail Pengajuan')
@section('page-title', 'Detail Pengajuan')
@section('page-description', 'Verifikasi pengajuan prestasi mahasiswa')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Back Button -->
        <a href="{{ url()->previous() }}"
            class="inline-flex items-center gap-2 text-gray-600 hover:text-simawa-600 mb-6 text-sm">
            <ion-icon name="arrow-back-outline"></ion-icon>
            Kembali
        </a>

        <div class="grid lg:grid-cols-3 gap-6">
            <!-- Left Column: Submission Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Mahasiswa Info -->
                <div class="bg-white rounded-md shadow-sm border border-gray-100 overflow-hidden">
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

                <!-- Formulir Header -->
                <div class="bg-white rounded-md shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-simawa-500 to-simawa-700 p-6 text-white">
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

            <!-- Right Column: Status & Actions -->
            <div class="space-y-6">
                <!-- Status Card -->
                <div class="bg-white rounded-md shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="font-semibold text-gray-800">Status Pengajuan</h3>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center justify-center mb-4">
                            <span
                                class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium {{ $pengajuanPrestasi->status_badge }}">
                                {{ $pengajuanPrestasi->status_label }}
                            </span>
                        </div>
                        <div class="text-sm text-gray-600 space-y-2">
                            <div class="flex justify-between">
                                <span>Diajukan:</span>
                                <span class="font-medium">{{ $pengajuanPrestasi->created_at->format('d M Y, H:i') }}</span>
                            </div>
                            @if($pengajuanPrestasi->verified_at)
                                <div class="flex justify-between">
                                    <span>Diverifikasi:</span>
                                    <span class="font-medium">{{ $pengajuanPrestasi->verified_at->format('d M Y, H:i') }}</span>
                                </div>
                                @if($pengajuanPrestasi->verifikator)
                                    <div class="flex justify-between">
                                        <span>Oleh:</span>
                                        <span class="font-medium">{{ $pengajuanPrestasi->verifikator->name }}</span>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Verification Actions -->
                @if($pengajuanPrestasi->status === 'menunggu' || $pengajuanPrestasi->status === 'revisi')
                    <div class="bg-white rounded-md shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6 border-b border-gray-100">
                            <h3 class="font-semibold text-gray-800">Verifikasi</h3>
                        </div>
                        <div class="p-6">
                            <form
                                action="{{ route(str_replace('.show', '.verify', request()->route()->getName()), $pengajuanPrestasi) }}"
                                method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                                    <textarea name="catatan_verifikator" rows="3"
                                        class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-simawa-500/20 focus:border-simawa-500 transition-all text-sm"
                                        placeholder="Tambahkan catatan..."></textarea>
                                </div>

                                <div class="space-y-2">
                                    <button type="submit" name="status" value="diterima"
                                        class="w-full px-4 py-2.5 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all text-sm font-medium flex items-center justify-center gap-2">
                                        <ion-icon name="checkmark-circle-outline"></ion-icon>
                                        ACC (Terima)
                                    </button>
                                    <button type="submit" name="status" value="revisi"
                                        class="w-full px-4 py-2.5 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-lg hover:from-orange-600 hover:to-orange-700 transition-all text-sm font-medium flex items-center justify-center gap-2">
                                        <ion-icon name="refresh-outline"></ion-icon>
                                        Minta Revisi
                                    </button>
                                    <button type="submit" name="status" value="ditolak"
                                        class="w-full px-4 py-2.5 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition-all text-sm font-medium flex items-center justify-center gap-2">
                                        <ion-icon name="close-circle-outline"></ion-icon>
                                        Tolak
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif

                <!-- Previous Notes -->
                @if($pengajuanPrestasi->catatan_verifikator && $pengajuanPrestasi->status !== 'menunggu')
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6 border-b border-gray-100">
                            <h3 class="font-semibold text-gray-800">Catatan Terakhir</h3>
                        </div>
                        <div class="p-6">
                            <p class="text-sm text-gray-700">{{ $pengajuanPrestasi->catatan_verifikator }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection