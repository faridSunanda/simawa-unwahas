@extends('layouts.dashboard')

@section('title', 'Daftar Kegiatan')
@section('page-title', 'Daftar Kegiatan')
@section('page-description', 'Temukan dan daftar kegiatan yang tersedia')

@section('content')
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

    <!-- Search -->
    <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm mb-6">
        <div class="relative">
            <ion-icon name="search-outline" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></ion-icon>
            <input type="text" id="searchKegiatan" placeholder="Cari kegiatan..."
                class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-simawa-500/20 focus:border-simawa-500">
        </div>
    </div>

    @if($kegiatans->count() > 0)
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4" id="kegiatanList">
            @foreach($kegiatans as $kegiatan)
                @php
                    $statusDaftar = $registeredStatuses[$kegiatan->id] ?? null;
                    $isRegistered = $statusDaftar !== null;
                @endphp
                <div class="kegiatan-card bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-all"
                    data-nama="{{ strtolower($kegiatan->nama) }}" data-detail="{{ strtolower($kegiatan->detail ?? '') }}">
                    <!-- Header with gradient -->
                    <div class="bg-gradient-to-r from-simawa-500 to-simawa-700 p-4 text-white">
                        <h4 class="font-bold text-lg truncate">{{ $kegiatan->nama }}</h4>
                        <div class="flex items-center gap-2 mt-2 text-sm text-white/80">
                            <ion-icon name="time-outline"></ion-icon>
                            <span>{{ $kegiatan->waktu_mulai->format('d M Y') }}</span>
                        </div>
                    </div>

                    <div class="p-4">
                        <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $kegiatan->detail ?? 'Tidak ada deskripsi' }}</p>

                        <div class="flex items-center justify-between text-xs text-gray-500 mb-4">
                            <span class="flex items-center gap-1">
                                <ion-icon name="calendar-outline"></ion-icon>
                                {{ $kegiatan->waktu_mulai->format('H:i') }} - {{ $kegiatan->waktu_selesai->format('H:i') }}
                            </span>
                            @if($kegiatan->pendaftaran === 'buka')
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full font-medium">Buka</span>
                            @else
                                <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full font-medium">Tutup</span>
                            @endif
                        </div>

                        <div class="flex gap-2">
                            <a href="{{ route('mahasiswa.kegiatan.show', $kegiatan) }}"
                                class="flex-1 inline-flex items-center justify-center gap-2 px-3 py-2.5 border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 transition-all text-sm font-medium">
                                <ion-icon name="eye-outline"></ion-icon>
                                Detail
                            </a>

                            @if($isRegistered)
                                @if($statusDaftar === 'menunggu')
                                    <span
                                        class="flex-1 inline-flex items-center justify-center gap-2 px-3 py-2.5 bg-amber-500 text-white rounded-lg text-sm font-medium cursor-default">
                                        <ion-icon name="time-outline"></ion-icon>
                                        Menunggu
                                    </span>
                                @elseif($statusDaftar === 'ditolak')
                                    <span
                                        class="flex-1 inline-flex items-center justify-center gap-2 px-3 py-2.5 bg-red-500 text-white rounded-lg text-sm font-medium cursor-default">
                                        <ion-icon name="close-circle"></ion-icon>
                                        Ditolak
                                    </span>
                                @else
                                    <span
                                        class="flex-1 inline-flex items-center justify-center gap-2 px-3 py-2.5 bg-green-500 text-white rounded-lg text-sm font-medium cursor-default">
                                        <ion-icon name="checkmark-circle"></ion-icon>
                                        Terdaftar
                                    </span>
                                @endif
                            @elseif($kegiatan->pendaftaran === 'buka')
                                @if($kegiatan->opsi_formulir)
                                    <a href="{{ route('mahasiswa.kegiatan.form', $kegiatan) }}"
                                        class="flex-1 inline-flex items-center justify-center gap-2 px-3 py-2.5 bg-gradient-to-r from-simawa-500 to-simawa-700 text-white rounded-lg hover:from-simawa-600 hover:to-simawa-800 transition-all text-sm font-medium">
                                        <ion-icon name="document-text-outline"></ion-icon>
                                        Isi Formulir
                                    </a>
                                @else
                                    <form action="{{ route('mahasiswa.kegiatan.daftar', $kegiatan) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit"
                                            class="w-full inline-flex items-center justify-center gap-2 px-3 py-2.5 bg-gradient-to-r from-simawa-500 to-simawa-700 text-white rounded-lg hover:from-simawa-600 hover:to-simawa-800 transition-all text-sm font-medium">
                                            <ion-icon name="add-circle-outline"></ion-icon>
                                            Daftar
                                        </button>
                                    </form>
                                @endif
                            @else
                                <span
                                    class="flex-1 inline-flex items-center justify-center gap-2 px-3 py-2.5 bg-gray-200 text-gray-500 rounded-lg text-sm font-medium cursor-not-allowed">
                                    <ion-icon name="close-circle-outline"></ion-icon>
                                    Ditutup
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 py-12 text-center">
            <ion-icon name="sad-outline" class="text-5xl text-gray-300 mb-3"></ion-icon>
            <p class="text-gray-500">Belum ada kegiatan tersedia</p>
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        document.getElementById('searchKegiatan').addEventListener('input', function () {
            const search = this.value.toLowerCase();
            document.querySelectorAll('.kegiatan-card').forEach(card => {
                const nama = card.dataset.nama;
                const detail = card.dataset.detail;
                card.style.display = nama.includes(search) || detail.includes(search) ? '' : 'none';
            });
        });
    </script>
@endpush