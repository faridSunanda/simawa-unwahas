@extends('layouts.dashboard')

@section('title', $kegiatan->nama)
@section('page-title', 'Detail Kegiatan')
@section('page-description', 'Informasi lengkap tentang kegiatan')

@section('content')
    <!-- Back Button -->
    <div class="mb-4">
        <a href="{{ route('mahasiswa.kegiatan.index') }}"
            class="inline-flex items-center gap-2 text-gray-500 hover:text-simawa-600 transition-colors text-sm">
            <ion-icon name="arrow-back-outline" class="text-lg"></ion-icon>
            Kembali ke Daftar Kegiatan
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
            <ion-icon name="close-circle" class="text-xl flex-shrink-0"></ion-icon>
            <span class="text-sm">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Kegiatan Header Card -->
    <div class="bg-gradient-to-r from-simawa-500 to-simawa-700 rounded-xl p-6 mb-6 text-white">
        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
            <div class="flex-1">
                <h2 class="text-2xl font-bold mb-2">{{ $kegiatan->nama }}</h2>
                <p class="text-white/80 mb-4">{{ $kegiatan->detail ?? 'Tidak ada deskripsi' }}</p>

                <div class="flex flex-wrap gap-4 text-sm text-white/70">
                    <span class="flex items-center gap-2">
                        <ion-icon name="calendar-outline" class="text-lg"></ion-icon>
                        {{ $kegiatan->waktu_mulai->format('d M Y') }} - {{ $kegiatan->waktu_selesai->format('d M Y') }}
                    </span>
                    <span class="flex items-center gap-2">
                        <ion-icon name="time-outline" class="text-lg"></ion-icon>
                        {{ $kegiatan->waktu_mulai->format('H:i') }} - {{ $kegiatan->waktu_selesai->format('H:i') }}
                    </span>
                </div>
            </div>

            <div class="flex flex-col gap-2">
                @if($kegiatan->pendaftaran === 'buka')
                    <span
                        class="inline-flex items-center gap-2 px-4 py-2 bg-green-500/20 text-white rounded-lg text-sm font-medium">
                        <ion-icon name="checkmark-circle"></ion-icon>
                        Pendaftaran Dibuka
                    </span>
                @else
                    <span
                        class="inline-flex items-center gap-2 px-4 py-2 bg-red-500/20 text-white rounded-lg text-sm font-medium">
                        <ion-icon name="close-circle"></ion-icon>
                        Pendaftaran Ditutup
                    </span>
                @endif

                @if($isRegistered)
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/20 text-white rounded-lg text-sm font-medium">
                        <ion-icon name="checkmark-done"></ion-icon>
                        Anda Sudah Terdaftar
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Rundown Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-4 border-b border-gray-100 flex items-center gap-3">
                    <div>
                        <h3 class="font-semibold text-gray-800">Rundown Kegiatan</h3>
                        <p class="text-sm text-gray-500">Jadwal acara kegiatan</p>
                    </div>
                </div>

                <div class="p-4">
                    @if($kegiatan->rundowns->count() > 0)
                        <div class="space-y-3">
                            @foreach($kegiatan->rundowns as $index => $rundown)
                                <div class="flex gap-4 p-4 bg-gray-50 rounded-lg">
                                    <div
                                        class="w-8 h-8 bg-simawa-500 text-white rounded-lg flex items-center justify-center text-sm font-bold flex-shrink-0">
                                        {{ $index + 1 }}
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-800">{{ $rundown->nama }}</h4>
                                        <p class="text-sm text-gray-500">
                                            {{ $rundown->waktu_mulai->format('d M Y, H:i') }} -
                                            {{ $rundown->waktu_selesai->format('H:i') }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <ion-icon name="calendar-clear-outline" class="text-4xl text-gray-300 mb-2"></ion-icon>
                            <p>Rundown belum tersedia</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Registration Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Pendaftaran</h3>

                @if($isRegistered)
                    <div class="text-center py-4">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <ion-icon name="checkmark-circle" class="text-3xl text-green-500"></ion-icon>
                        </div>
                        <h4 class="font-medium text-gray-800 mb-1">Anda Sudah Terdaftar!</h4>
                        <p class="text-sm text-gray-500 mb-4">Anda telah terdaftar dalam kegiatan ini.</p>

                        @if($peserta)
                            <div class="text-sm text-gray-600 bg-gray-50 rounded-lg p-3">
                                <p class="flex items-center justify-between mb-1">
                                    <span>Status Presensi:</span>
                                    <span
                                        class="font-medium {{ $peserta->status_presensi === 'hadir' ? 'text-green-600' : 'text-amber-600' }}">
                                        {{ $peserta->status_presensi === 'hadir' ? 'Hadir' : 'Belum Hadir' }}
                                    </span>
                                </p>
                                @if($peserta->waktu_presensi)
                                    <p class="text-xs text-gray-500">
                                        Presensi: {{ $peserta->waktu_presensi->format('d M Y, H:i') }}
                                    </p>
                                @endif
                            </div>
                        @endif

                        <a href="{{ route('mahasiswa.kegiatan.saya') }}"
                            class="mt-4 inline-flex items-center gap-2 text-simawa-600 hover:text-simawa-700 text-sm font-medium">
                            Lihat Kegiatan Saya
                        </a>
                    </div>
                @elseif($kegiatan->pendaftaran === 'buka')
                    <p class="text-sm text-gray-500 mb-4">Daftarkan diri Anda untuk mengikuti kegiatan ini.</p>
                    <form action="{{ route('mahasiswa.kegiatan.daftar', $kegiatan) }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-simawa-500 to-simawa-700 text-white rounded-lg hover:from-simawa-600 hover:to-simawa-800 transition-all text-sm font-medium">
                            <ion-icon name="add-circle-outline" class="text-lg"></ion-icon>
                            Daftar Sekarang
                        </button>
                    </form>
                @else
                    <div class="text-center py-4">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <ion-icon name="close-circle-outline" class="text-3xl text-gray-400"></ion-icon>
                        </div>
                        <h4 class="font-medium text-gray-800 mb-1">Pendaftaran Ditutup</h4>
                        <p class="text-sm text-gray-500">Maaf, pendaftaran untuk kegiatan ini sudah ditutup.</p>
                    </div>
                @endif
            </div>

            <!-- Info Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Informasi</h3>
                <ul class="space-y-3 text-sm text-gray-600">
                    <li class="flex items-center gap-3">
                        <ion-icon name="albums-outline" class="text-gray-400 text-lg"></ion-icon>
                        <span>{{ $kegiatan->rundowns->count() }} Agenda dalam rundown</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endsection