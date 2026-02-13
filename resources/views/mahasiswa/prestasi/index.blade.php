@extends('layouts.dashboard')

@section('title', 'Pengajuan Prestasi')
@section('page-title', 'Pengajuan Prestasi')
@section('page-description', 'Ajukan prestasi Anda melalui formulir yang tersedia')

@section('content')
    <!-- Success/Error Alert -->
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center gap-3">
            <ion-icon name="checkmark-circle" class="text-xl"></ion-icon>
            <span class="text-sm">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg flex items-center gap-3">
            <ion-icon name="close-circle" class="text-xl"></ion-icon>
            <span class="text-sm">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Available Forms Section -->
    <div class="mb-8">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Formulir Tersedia</h3>

        @if($formulirs->count() > 0)
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($formulirs as $formulir)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-all">
                        <div class="flex items-start gap-3 mb-3">
                            <div
                                class="w-12 h-12 rounded-lg bg-gradient-to-br from-simawa-100 to-simawa-200 flex items-center justify-center flex-shrink-0">
                                <ion-icon name="document-text-outline" class="text-simawa-600 text-xl"></ion-icon>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="font-semibold text-gray-800 truncate">{{ $formulir->judul }}</h4>
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700 mt-1">
                                    {{ $formulir->kategoriPrestasi->nama }}
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 text-sm text-gray-500 mb-4">
                            <span class="flex items-center gap-1">
                                <ion-icon name="help-circle-outline"></ion-icon>
                                {{ $formulir->pertanyaans->count() }} Pertanyaan
                            </span>
                        </div>
                        <a href="{{ route('mahasiswa.prestasi.create', $formulir) }}"
                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-simawa-500 to-simawa-700 text-white rounded-lg hover:from-simawa-600 hover:to-simawa-800 transition-all text-sm font-medium">
                            <ion-icon name="add-circle-outline"></ion-icon>
                            Ajukan Prestasi
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 py-12 text-center">
                <ion-icon name="document-text-outline" class="text-5xl text-gray-300 mb-3"></ion-icon>
                <p class="text-gray-500">Belum ada formulir tersedia</p>
            </div>
        @endif
    </div>

    <!-- Submission History Section -->
    <div>
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Riwayat Pengajuan</h3>

        @if($pengajuans->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full" id="dataTable">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Formulir</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Kategori</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Tanggal</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($pengajuans as $pengajuan)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 text-sm text-gray-800 font-medium">
                                        {{ $pengajuan->formulirPrestasi->judul }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $pengajuan->formulirPrestasi->kategoriPrestasi->nama }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $pengajuan->created_at->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $pengajuan->status_badge }}">
                                            {{ $pengajuan->status_label }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('mahasiswa.prestasi.show', $pengajuan) }}"
                                                class="flex h-8 w-8 items-center justify-center text-white bg-simawa-600 hover:bg-simawa-700 rounded-lg transition-colors"
                                                title="Lihat Detail">
                                                <ion-icon name="eye-outline" class="text-lg"></ion-icon>
                                            </a>
                                            @if($pengajuan->status === 'revisi')
                                                <a href="{{ route('mahasiswa.prestasi.edit', $pengajuan) }}"
                                                    class="flex h-8 w-8 items-center justify-center text-white bg-orange-500 hover:bg-orange-600 rounded-lg transition-colors"
                                                    title="Edit Pengajuan">
                                                    <ion-icon name="create-outline" class="text-lg"></ion-icon>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 py-12 text-center">
                <ion-icon name="time-outline" class="text-5xl text-gray-300 mb-3"></ion-icon>
                <p class="text-gray-500">Belum ada riwayat pengajuan</p>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            if ($('#dataTable tbody tr').length > 0) {
                $('#dataTable').DataTable({
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
                    },
                    order: [[2, 'desc']],
                    columnDefs: [
                        { orderable: false, targets: [4] }
                    ]
                });
            }
        });
    </script>
@endpush