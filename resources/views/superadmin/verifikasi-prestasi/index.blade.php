@extends('layouts.dashboard')

@section('title', 'Verifikasi Prestasi')
@section('page-title', 'Verifikasi Prestasi')
@section('page-description', 'Verifikasi pengajuan prestasi mahasiswa')

@section('content')
    <!-- Page Title -->
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-800">Verifikasi Prestasi</h2>
        <p class="text-sm text-gray-500 mt-1">Verifikasi pengajuan prestasi mahasiswa</p>
    </div>

    <!-- Success/Error Alert -->
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center gap-3">
            <ion-icon name="checkmark-circle" class="text-xl flex-shrink-0"></ion-icon>
            <span class="text-sm">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Filter Tabs -->
    <div class="mb-6 flex flex-wrap gap-2">
        @php
            $currentStatus = request('status', 'semua');
            $statuses = [
                'semua' => 'Semua',
                'menunggu' => 'Menunggu',
                'revisi' => 'Revisi',
                'diterima' => 'Diterima',
                'ditolak' => 'Ditolak'
            ];
        @endphp
        @foreach($statuses as $key => $label)
            <a href="{{ route(request()->route()->getName(), ['status' => $key]) }}"
                class="px-3 py-1.5 sm:px-4 sm:py-2 rounded-lg text-xs sm:text-sm font-medium transition-all {{ $currentStatus === $key ? 'bg-simawa-500 text-white' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    @if($pengajuans->count() > 0)
        <!-- Mobile Card View -->
        <div class="lg:hidden space-y-3">
            @foreach($pengajuans as $pengajuan)
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-gray-800 truncate">{{ $pengajuan->mahasiswa->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ $pengajuan->mahasiswa->email }}</p>
                        </div>
                        <span
                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium flex-shrink-0 {{ $pengajuan->status_badge }}">
                            {{ $pengajuan->status_label }}
                        </span>
                    </div>
                    <div class="space-y-2 text-sm">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500">Formulir:</span>
                            <span
                                class="text-gray-800 font-medium text-right truncate max-w-[60%]">{{ $pengajuan->formulirPrestasi->judul }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500">Kategori:</span>
                            <span
                                class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                {{ $pengajuan->formulirPrestasi->kategoriPrestasi->nama }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500">Tanggal:</span>
                            <span class="text-gray-800">{{ $pengajuan->created_at->format('d M Y') }}</span>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-gray-100">
                        <a href="{{ route(str_replace('.index', '.show', request()->route()->getName()), $pengajuan) }}"
                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-simawa-600 hover:bg-simawa-700 text-white rounded-lg transition-colors text-sm font-medium">
                            <ion-icon name="eye-outline"></ion-icon>
                            Lihat Detail
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Desktop Table View -->
        <div class="hidden lg:block bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full" id="dataTable">
                    <thead class="bg-simawa-600">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase">Mahasiswa</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase">Formulir</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase">Kategori</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase">Tanggal</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase">Status</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-white uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($pengajuans as $pengajuan)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="text-sm font-medium text-gray-800">{{ $pengajuan->mahasiswa->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $pengajuan->mahasiswa->email }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-800">{{ $pengajuan->formulirPrestasi->judul }}</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                        {{ $pengajuan->formulirPrestasi->kategoriPrestasi->nama }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $pengajuan->created_at->format('d M Y') }}</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $pengajuan->status_badge }}">
                                        {{ $pengajuan->status_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route(str_replace('.index', '.show', request()->route()->getName()), $pengajuan) }}"
                                        class="inline-flex h-9 w-9 items-center justify-center text-white bg-simawa-600 hover:bg-simawa-700 rounded-lg transition-colors"
                                        title="Lihat Detail">
                                        <ion-icon name="eye-outline" class="text-lg"></ion-icon>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 py-12 text-center">
            <p class="text-gray-500">Belum ada pengajuan prestasi</p>
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            if ($('#dataTable tbody tr').length > 0) {
                $('#dataTable').DataTable({
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
                    },
                    order: [[3, 'desc']],
                    columnDefs: [
                        { orderable: false, targets: [5] }
                    ]
                });
            }
        });
    </script>
@endpush