@extends('layouts.dashboard')

@section('title', 'Verifikasi Sertifikat')
@section('page-title', 'Verifikasi Sertifikat')
@section('page-description', 'Verifikasi pengajuan sertifikat mahasiswa')

@section('content')
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h3 class="text-lg font-semibold text-gray-800">Daftar Pengajuan Sertifikat</h3>
            <p class="text-sm text-gray-500 mt-1">Verifikasi sertifikat yang diajukan mahasiswa</p>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="flex flex-wrap gap-2 mb-6">
        <a href="{{ route('kaprodi.verifikasi-sertifikat.index') }}"
            class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ !request('status') || request('status') === 'semua' ? 'bg-simawa-600 text-white' : 'bg-white border border-gray-200 text-gray-700 hover:bg-gray-50' }}">
            Semua
        </a>
        <a href="{{ route('kaprodi.verifikasi-sertifikat.index', ['status' => 'menunggu']) }}"
            class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ request('status') === 'menunggu' ? 'bg-yellow-500 text-white' : 'bg-white border border-gray-200 text-gray-700 hover:bg-gray-50' }}">
            Menunggu
        </a>
        <a href="{{ route('kaprodi.verifikasi-sertifikat.index', ['status' => 'diterima']) }}"
            class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ request('status') === 'diterima' ? 'bg-green-500 text-white' : 'bg-white border border-gray-200 text-gray-700 hover:bg-gray-50' }}">
            Diterima
        </a>
        <a href="{{ route('kaprodi.verifikasi-sertifikat.index', ['status' => 'ditolak']) }}"
            class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ request('status') === 'ditolak' ? 'bg-red-500 text-white' : 'bg-white border border-gray-200 text-gray-700 hover:bg-gray-50' }}">
            Ditolak
        </a>
    </div>

    <!-- Success Alert -->
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center gap-3">
            <ion-icon name="checkmark-circle" class="text-xl flex-shrink-0"></ion-icon>
            <span class="text-sm">{{ session('success') }}</span>
        </div>
    @endif

    @if($pengajuans->count() > 0)
        <!-- Mobile Card View -->
        <div class="lg:hidden space-y-3">
            @foreach($pengajuans as $pengajuan)
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div class="min-w-0 flex-1">
                            <h4 class="font-semibold text-gray-800 truncate">{{ $pengajuan->nama_sertifikat }}</h4>
                            <p class="text-xs text-gray-500 mt-1">{{ $pengajuan->mahasiswa->name }}</p>
                        </div>
                        <span
                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium flex-shrink-0 {{ $pengajuan->status_badge }}">
                            {{ $pengajuan->status_label }}
                        </span>
                    </div>
                    <div class="space-y-1 text-sm text-gray-600 mb-4">
                        <p><span class="text-gray-500">Jenis:</span> {{ $pengajuan->jenisSertifikat->nama }}</p>
                        <p><span class="text-gray-500">Penerbit:</span> {{ $pengajuan->penerbit }}</p>
                        <p><span class="text-gray-500">Tanggal:</span> {{ $pengajuan->tanggal_terbit->format('d M Y') }}</p>
                    </div>
                    <div class="pt-3 border-t border-gray-100">
                        <a href="{{ route('kaprodi.verifikasi-sertifikat.show', $pengajuan) }}"
                            class="w-full inline-flex items-center justify-center gap-2 px-3 py-2 bg-simawa-600 hover:bg-simawa-700 text-white rounded-lg transition-colors text-sm font-medium">
                            <ion-icon name="eye-outline"></ion-icon>
                            Lihat Detail
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Desktop Table View -->
        <div class="hidden lg:block bg-white rounded-md shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table id="dataTable" class="w-full">
                    <thead class="bg-simawa-600">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Mahasiswa
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Sertifikat
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Jenis</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Penerbit
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-white uppercase tracking-wider">Status
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-white uppercase tracking-wider w-24">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($pengajuans as $pengajuan)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 text-sm text-gray-800 font-medium">{{ $pengajuan->mahasiswa->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-800">{{ $pengajuan->nama_sertifikat }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $pengajuan->jenisSertifikat->nama }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $pengajuan->penerbit }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $pengajuan->status_badge }}">
                                        {{ $pengajuan->status_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('kaprodi.verifikasi-sertifikat.show', $pengajuan) }}"
                                        class="inline-flex items-center justify-center h-9 w-9 text-white bg-simawa-600 hover:bg-simawa-700 rounded-lg transition-colors">
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
            <p class="text-gray-500">Tidak ada pengajuan sertifikat</p>
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            if ($('#dataTable tbody tr').length > 0) {
                $('#dataTable').DataTable({
                    responsive: true,
                    pageLength: 10,
                    order: [],
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
                    }
                });
            }
        });
    </script>
@endpush