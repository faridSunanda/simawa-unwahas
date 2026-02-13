@extends('layouts.dashboard')

@section('title', 'Daftar Prestasi')
@section('page-title', 'Daftar Prestasi')
@section('page-description', 'Daftar prestasi mahasiswa yang sudah diverifikasi')

@section('content')
    <!-- Page Title -->
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-800">Daftar Prestasi</h2>
        <p class="text-sm text-gray-500 mt-1">Daftar prestasi mahasiswa yang sudah diverifikasi</p>
    </div>

    <!-- Success/Error Alert -->
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center gap-3">
            <ion-icon name="checkmark-circle" class="text-xl flex-shrink-0"></ion-icon>
            <span class="text-sm">{{ session('success') }}</span>
        </div>
    @endif

    @if($prestasis->count() > 0)
        <!-- Mobile Card View -->
        <div class="lg:hidden space-y-3">
            @foreach($prestasis as $index => $prestasi)
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-gray-800 truncate">{{ $prestasi->mahasiswa->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ $prestasi->mahasiswa->email }}</p>
                        </div>
                        <span
                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700 flex-shrink-0">
                            Terverifikasi
                        </span>
                    </div>
                    <div class="space-y-2 text-sm">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500">Prestasi:</span>
                            <span
                                class="text-gray-800 font-medium text-right truncate max-w-[60%]">{{ $prestasi->formulirPrestasi->judul }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500">Kategori:</span>
                            <span
                                class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                {{ $prestasi->formulirPrestasi->kategoriPrestasi->nama }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500">Verifikasi:</span>
                            <span class="text-gray-800">{{ $prestasi->verified_at->format('d M Y') }}</span>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-gray-100">
                        <a href="{{ route(str_replace('.index', '.show', request()->route()->getName()), $prestasi) }}"
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
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase w-16">No</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase">Mahasiswa</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase">Prestasi</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase">Kategori</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase">Tanggal Verifikasi</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-white uppercase w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($prestasis as $index => $prestasi)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $index + 1 }}</td>
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="text-sm font-medium text-gray-800">{{ $prestasi->mahasiswa->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $prestasi->mahasiswa->email }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-800">{{ $prestasi->formulirPrestasi->judul }}</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                        {{ $prestasi->formulirPrestasi->kategoriPrestasi->nama }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $prestasi->verified_at->format('d M Y') }}</td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route(str_replace('.index', '.show', request()->route()->getName()), $prestasi) }}"
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
            <ion-icon name="sad-outline" class="text-5xl text-gray-300 mb-3"></ion-icon>
            <p class="text-gray-500">Belum ada prestasi terverifikasi</p>
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
                    order: [[4, 'desc']],
                    columnDefs: [
                        { orderable: false, targets: [0, 5] }
                    ]
                });
            }
        });
    </script>
@endpush