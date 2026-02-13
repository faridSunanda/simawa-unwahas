@extends('layouts.dashboard')

@section('title', 'Daftar Mahasiswa')
@section('page-title', 'Daftar Mahasiswa')
@section('page-description', 'Daftar mahasiswa dan jumlah sertifikat')

@section('content')
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h3 class="text-lg font-semibold text-gray-800">Daftar Mahasiswa</h3>
            <p class="text-sm text-gray-500 mt-1">Data mahasiswa dan jumlah sertifikat yang terverifikasi</p>
        </div>
    </div>

    @if($mahasiswas->count() > 0)
        <!-- Mobile Card View -->
        <div class="lg:hidden space-y-3">
            @foreach($mahasiswas as $index => $mhs)
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
                    <div class="flex items-center gap-3 mb-3">
                        @php
                            $colors = ['blue', 'pink', 'green', 'purple', 'orange', 'teal', 'indigo'];
                            $color = $colors[$index % count($colors)];
                            $initials = collect(explode(' ', $mhs->user->name ?? 'XX'))->map(fn($n) => substr($n, 0, 1))->take(2)->join('');
                        @endphp
                        <div
                            class="w-10 h-10 rounded-full bg-gradient-to-br from-{{ $color }}-400 to-{{ $color }}-600 flex items-center justify-center text-white font-semibold text-sm">
                            {{ strtoupper($initials) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="text-sm font-semibold text-gray-800 block truncate">{{ $mhs->user->name ?? '-' }}</span>
                            <span class="text-xs text-gray-500">{{ $mhs->nim }}</span>
                        </div>
                    </div>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Program Studi</span>
                            <span class="text-gray-800 font-medium">{{ $mhs->prodi ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Fakultas</span>
                            <span class="text-gray-800">{{ $mhs->fakultas ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Sertifikat</span>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ ($mhs->sertifikat_count ?? 0) >= 10 ? 'bg-emerald-100 text-emerald-700' : (($mhs->sertifikat_count ?? 0) >= 1 ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-600') }}">
                                {{ $mhs->sertifikat_count ?? 0 }}
                            </span>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-gray-100">
                        <a href="{{ route('superadmin.daftar-mahasiswa.show', $mhs) }}"
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
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider w-16">No
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">NIM</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Nama
                                Mahasiswa</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Program
                                Studi</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-white uppercase tracking-wider">Jumlah
                                Sertifikat</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-white uppercase tracking-wider w-24">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($mahasiswas as $index => $mhs)
                            @php
                                $colors = ['blue', 'pink', 'green', 'purple', 'orange', 'teal', 'indigo'];
                                $color = $colors[$index % count($colors)];
                                $initials = collect(explode(' ', $mhs->user->name ?? 'XX'))->map(fn($n) => substr($n, 0, 1))->take(2)->join('');
                            @endphp
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 text-sm text-gray-600 font-medium">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-800">{{ $mhs->nim }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-9 h-9 rounded-full bg-gradient-to-br from-{{ $color }}-400 to-{{ $color }}-600 flex items-center justify-center text-white font-semibold text-sm">
                                            {{ strtoupper($initials) }}
                                        </div>
                                        <span class="text-sm font-medium text-gray-800">{{ $mhs->user->name ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $mhs->prodi ?? '-' }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm font-medium {{ ($mhs->sertifikat_count ?? 0) >= 10 ? 'bg-emerald-100 text-emerald-700' : (($mhs->sertifikat_count ?? 0) >= 1 ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-600') }}">
                                        {{ $mhs->sertifikat_count ?? 0 }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center">
                                        <a href="{{ route('superadmin.daftar-mahasiswa.show', $mhs) }}"
                                            class="flex h-9 w-9 items-center justify-center text-white bg-simawa-600 hover:bg-simawa-700 rounded-lg transition-colors"
                                            title="Lihat Detail">
                                            <ion-icon name="eye-outline" class="text-lg"></ion-icon>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 py-12 text-center">
            <ion-icon name="people-outline" class="text-5xl text-gray-300 mb-4"></ion-icon>
            <p class="text-gray-500">Belum ada data mahasiswa</p>
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
                    order: [[1, 'asc']],
                    columnDefs: [
                        { orderable: false, targets: [0, 5] },
                        { searchable: false, targets: [0, 4, 5] }
                    ],
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
                    }
                });
            }
        });
    </script>
@endpush