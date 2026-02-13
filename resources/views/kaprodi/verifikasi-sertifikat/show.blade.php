@extends('layouts.dashboard')

@section('title', 'Detail Pengajuan Sertifikat')
@section('page-title', 'Detail Pengajuan')
@section('page-description', 'Verifikasi pengajuan sertifikat mahasiswa')

@section('content')
    <div class="max-w-3xl mx-auto">
        <!-- Back Button -->
        <a href="{{ route('kaprodi.verifikasi-sertifikat.index') }}"
            class="inline-flex items-center gap-2 text-gray-600 hover:text-simawa-600 mb-6 text-sm">
            <ion-icon name="arrow-back-outline"></ion-icon>
            Kembali ke Daftar
        </a>

        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
            <!-- Header -->
            <div class="p-6 border-b border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">{{ $pengajuanSertifikat->nama_sertifikat }}</h3>
                        <p class="text-sm text-gray-500 mt-1">Diajukan oleh: {{ $pengajuanSertifikat->mahasiswa->name }}</p>
                    </div>
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium w-fit {{ $pengajuanSertifikat->status_badge }}">
                        {{ $pengajuanSertifikat->status_label }}
                    </span>
                </div>
            </div>

            <!-- Detail Info -->
            <div class="p-6 space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Jenis Sertifikat</p>
                        <p class="text-sm font-medium text-gray-800">{{ $pengajuanSertifikat->jenisSertifikat->nama }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Nama Sertifikat</p>
                        <p class="text-sm font-medium text-gray-800">{{ $pengajuanSertifikat->nama_sertifikat }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Name in English</p>
                        <p class="text-sm font-medium text-gray-800">{{ $pengajuanSertifikat->nama_sertifikat_en ?? '-' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Penerbit</p>
                        <p class="text-sm font-medium text-gray-800">{{ $pengajuanSertifikat->penerbit }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Tanggal Terbit</p>
                        <p class="text-sm font-medium text-gray-800">
                            {{ $pengajuanSertifikat->tanggal_terbit->format('d F Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Tanggal Pengajuan</p>
                        <p class="text-sm font-medium text-gray-800">
                            {{ $pengajuanSertifikat->created_at->format('d F Y H:i') }}</p>
                    </div>
                </div>

                @if($pengajuanSertifikat->file_sertifikat)
                    <div class="pt-5 border-t border-gray-100">
                        <p class="text-xs text-gray-500 mb-3">File Sertifikat</p>
                        <a href="{{ Storage::url($pengajuanSertifikat->file_sertifikat) }}" target="_blank"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm text-gray-700 transition-colors">
                            <ion-icon name="document-outline" class="text-lg"></ion-icon>
                            Lihat File Sertifikat
                        </a>
                    </div>
                @endif

                @if($pengajuanSertifikat->verified_at)
                    <div class="pt-5 border-t border-gray-100">
                        <p class="text-xs text-gray-500 mb-1">Verifikasi</p>
                        <p class="text-sm text-gray-700">Diverifikasi pada
                            {{ $pengajuanSertifikat->verified_at->format('d F Y H:i') }} oleh
                            {{ $pengajuanSertifikat->verifier->name ?? '-' }}</p>
                        @if($pengajuanSertifikat->catatan_verifikasi)
                            <div class="mt-3 p-4 bg-gray-50 rounded-lg">
                                <p class="text-xs text-gray-500 mb-1">Catatan:</p>
                                <p class="text-sm text-gray-700">{{ $pengajuanSertifikat->catatan_verifikasi }}</p>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Verification Form -->
            @if($pengajuanSertifikat->status === 'menunggu')
                <div class="p-6 border-t border-gray-100 bg-gray-50">
                    <h4 class="font-medium text-gray-800 mb-4">Verifikasi Pengajuan</h4>
                    <form action="{{ route('kaprodi.verifikasi-sertifikat.verify', $pengajuanSertifikat) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                            <textarea name="catatan_verifikasi" rows="3"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-simawa-500/20 focus:border-simawa-500 transition-all text-sm resize-none"
                                placeholder="Berikan catatan jika diperlukan..."></textarea>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <button type="submit" name="status" value="ditolak"
                                class="w-full sm:flex-1 px-4 py-2.5 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors text-sm font-medium">
                                <ion-icon name="close-outline" class="mr-1"></ion-icon>
                                Tolak
                            </button>
                            <button type="submit" name="status" value="diterima"
                                class="w-full sm:flex-1 px-4 py-2.5 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-colors text-sm font-medium">
                                <ion-icon name="checkmark-outline" class="mr-1"></ion-icon>
                                Terima
                            </button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>
@endsection