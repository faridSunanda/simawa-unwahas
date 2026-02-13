@extends('layouts.dashboard')

@section('title', 'Peserta Kegiatan')
@section('page-title', 'Peserta Kegiatan')
@section('page-description', 'Kelola peserta kegiatan')

@section('content')
    <!-- Success/Error Alert -->
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center gap-3">
            <ion-icon name="checkmark-circle" class="text-xl flex-shrink-0"></ion-icon>
            <span class="text-sm">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <a href="{{ route('superadmin.kegiatan.index') }}"
                    class="text-gray-400 hover:text-simawa-600 transition-colors">
                    <ion-icon name="arrow-back-outline" class="text-xl"></ion-icon>
                </a>
                <h3 class="text-lg font-semibold text-gray-800">Peserta Kegiatan</h3>
            </div>
            <p class="text-sm text-gray-500 mt-1">Daftar peserta yang terdaftar dalam kegiatan</p>
        </div>
        <a href="{{ route('superadmin.kegiatan.scan-presensi', $kegiatan) }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-simawa-500 to-simawa-700 text-white rounded-lg hover:from-simawa-600 hover:to-simawa-800 transition-all text-sm font-medium shadow-sm">
            <ion-icon name="qr-code-outline"></ion-icon>
            Scan QR Presensi
        </a>
    </div>

    <!-- Info Kegiatan -->
    <div class="bg-gradient-to-r from-simawa-500 to-simawa-700 rounded-xl p-5 mb-6 text-white">
        <div class="flex items-start gap-4">
            <div>
                <h4 class="text-lg font-bold mb-1">{{ $kegiatan->nama }}</h4>
                <p class="text-sm text-white/80 mb-2">{{ $kegiatan->detail ?? '-' }}</p>
                <div class="flex flex-wrap items-center gap-4 text-xs text-white/70">
                    <span class="flex items-center gap-1">
                        <ion-icon name="time-outline"></ion-icon>
                        {{ $kegiatan->waktu_mulai->format('d M Y') }} - {{ $kegiatan->waktu_selesai->format('d M Y') }}
                    </span>
                    <span class="flex items-center gap-1">
                        <ion-icon name="people-outline"></ion-icon>
                        {{ $kegiatan->peserta->count() }} Peserta
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Search (for card view) -->
    <div class="lg:hidden bg-white rounded-xl p-4 border border-gray-100 shadow-sm mb-6">
        <div class="flex flex-col gap-4">
            <div class="flex-1">
                <div class="relative">
                    <ion-icon name="search-outline"
                        class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></ion-icon>
                    <input type="text" id="searchPesertaMobile" placeholder="Cari nama atau NIM mahasiswa..."
                        class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-simawa-500/20 focus:border-simawa-500">
                </div>
            </div>
            <select id="filterPresensiMobile"
                class="px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-simawa-500/20 focus:border-simawa-500 bg-white">
                <option value="">Semua Status Presensi</option>
                <option value="hadir">Hadir</option>
                <option value="belum_hadir">Belum Hadir</option>
            </select>
        </div>
    </div>

    @if($kegiatan->peserta->count() > 0)
        <!-- Mobile Card View -->
        <div class="lg:hidden space-y-3">
            @foreach($kegiatan->peserta as $index => $peserta)
                @php
                    $name = $peserta->mahasiswa->user->name ?? 'Unknown';
                    $initials = collect(explode(' ', $name))->map(fn($n) => strtoupper(substr($n, 0, 1)))->take(2)->join('');
                @endphp
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 peserta-row"
                    data-nama="{{ strtolower($name) }}" data-nim="{{ strtolower($peserta->mahasiswa->nim ?? '') }}"
                    data-status="{{ $peserta->status_presensi }}">
                    <div class="flex items-start gap-3">
                        <div
                            class="w-10 h-10 rounded-full bg-gradient-to-br from-simawa-400 to-simawa-600 flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                            {{ $initials }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-medium text-gray-800 truncate">{{ $name }}</h4>
                            <p class="text-xs text-gray-500 font-mono">{{ $peserta->mahasiswa->nim ?? '-' }}</p>
                            <p class="text-xs text-gray-500">{{ $peserta->mahasiswa->prodi ?? '-' }}</p>
                        </div>
                        <span
                            class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium {{ $peserta->status_presensi === 'hadir' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                            {{ $peserta->status_presensi === 'hadir' ? 'Hadir' : 'Belum Hadir' }}
                        </span>
                    </div>
                    @if($peserta->waktu_presensi)
                        <p class="text-xs text-gray-500 mt-2">Presensi: {{ $peserta->waktu_presensi->format('d M Y, H:i') }}</p>
                    @endif
                    <div class="mt-3 pt-3 border-t border-gray-100">
                        <form action="{{ route('superadmin.kegiatan.peserta.presensi', $peserta) }}" method="POST">
                            @csrf
                            @method('PUT')
                            @if($peserta->status_presensi === 'hadir')
                                <input type="hidden" name="status_presensi" value="belum_hadir">
                                <button type="submit"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 bg-amber-100 text-amber-700 hover:bg-amber-200 rounded-lg text-xs font-medium transition-colors">
                                    <ion-icon name="close-circle-outline"></ion-icon>
                                    Batalkan Hadir
                                </button>
                            @else
                                <input type="hidden" name="status_presensi" value="hadir">
                                <button type="submit"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 bg-emerald-100 text-emerald-700 hover:bg-emerald-200 rounded-lg text-xs font-medium transition-colors">
                                    <ion-icon name="checkmark-circle-outline"></ion-icon>
                                    Tandai Hadir
                                </button>
                            @endif
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Desktop Table View (DataTables) -->
        <div class="hidden lg:block bg-white rounded-md shadow-sm border border-gray-100 overflow-hidden">
            <table id="pesertaTable" class="w-full">
                <thead>
                    <tr>
                        <th class="text-left w-16">No</th>
                        <th class="text-left">Nama Mahasiswa</th>
                        <th class="text-left">NIM</th>
                        <th class="text-left">Fakultas</th>
                        <th class="text-left">Prodi</th>
                        <th class="text-center">Status Presensi</th>
                        <th class="text-left">Waktu Presensi</th>
                        <th class="text-center w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kegiatan->peserta as $index => $peserta)
                        @php
                            $name = $peserta->mahasiswa->user->name ?? 'Unknown';
                        @endphp
                        <tr>
                            <td class="text-sm text-gray-600 font-medium">{{ $index + 1 }}</td>
                            <td>
                                <span class="text-sm font-medium text-gray-800">{{ $name }}</span>
                            </td>
                            <td class="text-sm text-gray-600 font-mono">{{ $peserta->mahasiswa->nim ?? '-' }}</td>
                            <td class="text-sm text-gray-600">{{ $peserta->mahasiswa->fakultas ?? '-' }}</td>
                            <td class="text-sm text-gray-600">{{ $peserta->mahasiswa->prodi ?? '-' }}</td>
                            <td class="text-center">
                                <span
                                    class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium {{ $peserta->status_presensi === 'hadir' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ $peserta->status_presensi === 'hadir' ? 'Hadir' : 'Belum Hadir' }}
                                </span>
                            </td>
                            <td class="text-sm text-gray-600 whitespace-nowrap">
                                {{ $peserta->waktu_presensi ? $peserta->waktu_presensi->format('d M Y, H:i') : '-' }}
                            </td>
                            <td class="text-center">
                                <form action="{{ route('superadmin.kegiatan.peserta.presensi', $peserta) }}" method="POST"
                                    class="inline">
                                    @csrf
                                    @method('PUT')
                                    @if($peserta->status_presensi === 'hadir')
                                        <input type="hidden" name="status_presensi" value="belum_hadir">
                                        <button type="submit"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 bg-amber-100 text-amber-700 hover:bg-amber-200 rounded-lg text-xs font-medium transition-colors"
                                            title="Batalkan Hadir">
                                            <ion-icon name="close-circle-outline"></ion-icon>
                                            Batalkan
                                        </button>
                                    @else
                                        <input type="hidden" name="status_presensi" value="hadir">
                                        <button type="submit"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 bg-emerald-100 text-emerald-700 hover:bg-emerald-200 rounded-lg text-xs font-medium transition-colors"
                                            title="Tandai Hadir">
                                            <ion-icon name="checkmark-circle-outline"></ion-icon>
                                            Hadir
                                        </button>
                                    @endif
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 py-12 text-center">
            <ion-icon name="people-outline" class="text-5xl text-gray-300 mb-4"></ion-icon>
            <p class="text-gray-500">Belum ada peserta yang terdaftar</p>
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            // Initialize DataTables for desktop
            var table = $('#pesertaTable').DataTable({
                responsive: false,
                ordering: true,
                order: [[0, 'asc']],
                pageLength: 10,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ peserta",
                    infoEmpty: "Tidak ada data",
                    infoFiltered: "(disaring dari _MAX_ total data)",
                    zeroRecords: "Tidak ada peserta yang ditemukan",
                    paginate: {
                        first: "Awal",
                        last: "Akhir",
                        next: "Berikutnya",
                        previous: "Sebelumnya"
                    }
                },
                columnDefs: [
                    { orderable: false, targets: [7] }, // Disable sorting on Aksi column
                    { searchable: false, targets: [0, 7] } // No search on No & Aksi
                ]
            });

            // Make search & filter sit inline
            $('#pesertaTable_filter label').css({
                'display': 'flex',
                'align-items': 'center',
                'gap': '0.5rem'
            });
            $('#pesertaTable_filter').css({
                'display': 'flex',
                'align-items': 'center',
                'gap': '0.75rem',
                'flex-wrap': 'nowrap'
            });

            // Add status filter dropdown next to DataTables search
            var filterHtml = '<select id="filterPresensiDT" style="padding:0.5rem 0.75rem;border:1px solid #E5E7EB;border-radius:0.5rem;font-size:0.875rem;outline:none;white-space:nowrap;">' +
                '<option value="">Semua Status</option>' +
                '<option value="Hadir">Hadir</option>' +
                '<option value="Belum Hadir">Belum Hadir</option>' +
                '</select>';
            $('#pesertaTable_filter').append(filterHtml);

            // Custom search function for status filter
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                // Only apply to pesertaTable
                if (settings.nTable.id !== 'pesertaTable') return true;
                var selected = $('#filterPresensiDT').val();
                if (!selected) return true;
                var status = (data[5] || '').trim(); // Column 5 = Status Presensi
                return status === selected;
            });

            // Redraw table when filter changes
            $('#filterPresensiDT').on('change', function () {
                table.draw();
            });

            // Mobile card search & filter
            $('#searchPesertaMobile').on('input', filterMobileCards);
            $('#filterPresensiMobile').on('change', filterMobileCards);

            function filterMobileCards() {
                var search = $('#searchPesertaMobile').val().toLowerCase();
                var presensi = $('#filterPresensiMobile').val();

                $('.peserta-row').each(function () {
                    var nama = $(this).data('nama');
                    var nim = $(this).data('nim');
                    var status = $(this).data('status');

                    var matchSearch = nama.includes(search) || String(nim).includes(search);
                    var matchPresensi = !presensi || status === presensi;

                    $(this).toggle(matchSearch && matchPresensi);
                });
            }
        });
    </script>
@endpush