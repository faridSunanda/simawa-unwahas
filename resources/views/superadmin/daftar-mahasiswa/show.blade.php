@extends('layouts.dashboard')

@section('title', 'Detail Mahasiswa')
@section('page-title', 'Detail Mahasiswa')
@section('page-description', 'Informasi lengkap mahasiswa dan sertifikat')

@section('content')
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('superadmin.daftar-mahasiswa.index') }}"
            class="inline-flex items-center gap-2 text-gray-600 hover:text-simawa-600 transition-colors text-sm">
            <ion-icon name="arrow-back-outline" class="text-lg"></ion-icon>
            Kembali ke Daftar Mahasiswa
        </a>
    </div>

    <!-- Mahasiswa Profile Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
            @php
                $colors = ['blue', 'pink', 'green', 'purple', 'orange'];
                $color = $colors[array_rand($colors)];
                $initials = collect(explode(' ', $mahasiswa->user->name ?? 'XX'))->map(fn($n) => substr($n, 0, 1))->take(2)->join('');
            @endphp
            <div
                class="w-16 h-16 rounded-full bg-gradient-to-br from-{{ $color }}-400 to-{{ $color }}-600 flex items-center justify-center text-white font-bold text-xl">
                {{ strtoupper($initials) }}
            </div>
            <div class="flex-1">
                <h2 class="text-xl font-bold text-gray-800">{{ $mahasiswa->user->name ?? '-' }}</h2>
                <p class="text-sm text-gray-500 mt-1">{{ $mahasiswa->nim }}</p>
            </div>
            <div class="flex flex-col items-end gap-1 text-sm text-gray-600">
                <span
                    class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-simawa-100 text-simawa-700 font-medium">
                    {{ $pengajuanSertifikats->where('status', 'terverifikasi')->count() }} Sertifikat Terverifikasi
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-6 pt-6 border-t border-gray-100">
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Program Studi</p>
                <p class="text-sm font-medium text-gray-800">{{ $mahasiswa->prodi ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Fakultas</p>
                <p class="text-sm font-medium text-gray-800">{{ $mahasiswa->fakultas ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Angkatan</p>
                <p class="text-sm font-medium text-gray-800">{{ $mahasiswa->angkatan ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Email</p>
                <p class="text-sm font-medium text-gray-800">{{ $mahasiswa->user->email ?? '-' }}</p>
            </div>
        </div>
    </div>

    <!-- Sertifikat Section -->
    <div class="mb-4">
        <h3 class="text-lg font-semibold text-gray-800">Riwayat Pengajuan Sertifikat</h3>
        <p class="text-sm text-gray-500 mt-1">Daftar sertifikat yang telah diajukan oleh mahasiswa</p>
    </div>

    @if($pengajuanSertifikats->count() > 0)
        <!-- Mobile Card View -->
        <div class="lg:hidden space-y-3">
            @foreach($pengajuanSertifikats as $sertifikat)
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <div class="min-w-0 flex-1">
                            <span
                                class="text-sm font-semibold text-gray-800 block">{{ $sertifikat->jenisSertifikat->nama ?? '-' }}</span>
                            <span class="text-xs text-gray-500">{{ $sertifikat->nomor_sertifikat ?? '-' }}</span>
                        </div>
                        @php
                            $statusClasses = [
                                'menunggu' => 'bg-amber-100 text-amber-700',
                                'terverifikasi' => 'bg-emerald-100 text-emerald-700',
                                'ditolak' => 'bg-red-100 text-red-700',
                            ];
                            $statusClass = $statusClasses[$sertifikat->status] ?? 'bg-gray-100 text-gray-700';
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                            {{ ucfirst($sertifikat->status) }}
                        </span>
                    </div>
                    <div class="space-y-1 text-sm text-gray-600 mt-3">
                        <p><span class="text-gray-500">Tanggal:</span>
                            {{ $sertifikat->tanggal_terbit ? \Carbon\Carbon::parse($sertifikat->tanggal_terbit)->format('d M Y') : '-' }}
                        </p>
                        <p><span class="text-gray-500">Penerbit:</span> {{ $sertifikat->penerbit ?? '-' }}</p>
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
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider w-12">No
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Jenis
                                Sertifikat</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Nomor
                                Sertifikat</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Penerbit
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-white uppercase tracking-wider">Tanggal
                                Terbit</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-white uppercase tracking-wider">Status
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($pengajuanSertifikats as $index => $sertifikat)
                            @php
                                $statusClasses = [
                                    'menunggu' => 'bg-amber-100 text-amber-700',
                                    'terverifikasi' => 'bg-emerald-100 text-emerald-700',
                                    'ditolak' => 'bg-red-100 text-red-700',
                                ];
                                $statusClass = $statusClasses[$sertifikat->status] ?? 'bg-gray-100 text-gray-700';
                            @endphp
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 text-sm text-gray-600 font-medium">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-800">
                                    {{ $sertifikat->jenisSertifikat->nama ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $sertifikat->nomor_sertifikat ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $sertifikat->penerbit ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600 text-center">
                                    {{ $sertifikat->tanggal_terbit ? \Carbon\Carbon::parse($sertifikat->tanggal_terbit)->format('d M Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                        {{ ucfirst($sertifikat->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 py-12 text-center">
            <ion-icon name="document-outline" class="text-5xl text-gray-300 mb-4"></ion-icon>
            <p class="text-gray-500">Mahasiswa ini belum memiliki pengajuan sertifikat</p>
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof $ !== 'undefined' && $('#dataTable tbody tr').length > 0) {
                $('#dataTable').DataTable({
                    responsive: true,
                    pageLength: 10,
                    order: [[4, 'desc']],
                    columnDefs: [
                        { orderable: false, targets: [0] },
                        { searchable: false, targets: [0, 5] }
                    ],
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
                    }
                });
            }
        });
    </script>
@endpush