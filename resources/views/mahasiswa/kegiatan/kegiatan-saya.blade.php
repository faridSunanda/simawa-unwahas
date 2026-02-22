@extends('layouts.dashboard')

@section('title', 'Kegiatan Saya')
@section('page-title', 'Kegiatan Saya')
@section('page-description', 'Daftar kegiatan yang telah Anda ikuti')

@section('content')
    <!-- Header with QR Button -->
    @if($mahasiswa)
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Kegiatan Saya</h3>
                <p class="text-sm text-gray-500 mt-1">Daftar kegiatan yang telah Anda ikuti</p>
            </div>
            <button onclick="showQrModal()"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-simawa-500 to-simawa-700 text-white rounded-lg hover:from-simawa-600 hover:to-simawa-800 transition-all text-sm font-medium shadow-sm">
                <ion-icon name="qr-code-outline"></ion-icon>
                Lihat QR Code Saya
            </button>
        </div>
    @endif

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

    @if($pesertaRecords->count() > 0)
        <!-- Mobile Card View -->
        <div class="lg:hidden space-y-3">
            @foreach($pesertaRecords as $record)
                @if($record->kegiatan)
                    <div class="bg-white rounded-md shadow-sm border border-gray-100 overflow-hidden">
                        <div class="bg-gradient-to-r from-simawa-500 to-simawa-600 p-4 text-white">
                            <h4 class="font-bold truncate">{{ $record->kegiatan->nama }}</h4>
                            <p class="text-sm text-white/80 mt-1">{{ $record->kegiatan->waktu_mulai->format('d M Y') }}</p>
                        </div>
                        <div class="p-4">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-sm text-gray-500">Status Pendaftaran:</span>
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $record->status_badge }}">
                                    {{ $record->status_label }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-sm text-gray-500">Status Presensi:</span>
                                @if($record->kegiatan->presensi)
                                    <span
                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium {{ $record->status_presensi === 'hadir' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                                        <ion-icon
                                            name="{{ $record->status_presensi === 'hadir' ? 'checkmark-circle' : 'time-outline' }}"></ion-icon>
                                        {{ $record->status_presensi === 'hadir' ? 'Hadir' : 'Belum Hadir' }}
                                    </span>
                                @else
                                    <span class="text-gray-500">-</span>
                                @endif
                            </div>
                            @if($record->kegiatan->presensi && $record->waktu_presensi)
                                <p class="text-xs text-gray-500">Presensi: {{ $record->waktu_presensi->format('d M Y, H:i') }}</p>
                            @endif
                            <div class="mt-3 pt-3 border-t border-gray-100">
                                <a href="{{ route('mahasiswa.kegiatan.show', $record->kegiatan) }}"
                                    class="inline-flex items-center gap-2 text-simawa-600 hover:text-simawa-700 text-sm font-medium">
                                    <ion-icon name="eye-outline"></ion-icon>
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <!-- Desktop Table View -->
        <div class="hidden lg:block bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-simawa-600">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider w-16">No
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Nama
                                Kegiatan</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Tanggal
                                Kegiatan</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-white uppercase tracking-wider">Status
                                Pendaftaran</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-white uppercase tracking-wider">Status
                                Presensi</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Waktu
                                Presensi</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-white uppercase tracking-wider w-24">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($pesertaRecords as $index => $record)
                            @if($record->kegiatan)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4 text-sm text-gray-600 font-medium">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4">
                                        <div>
                                            <span class="text-sm font-medium text-gray-800">{{ $record->kegiatan->nama }}</span>
                                            <p class="text-xs text-gray-500 line-clamp-1">
                                                {{ Str::limit($record->kegiatan->detail ?? '-', 50) }}
                                            </p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">
                                        {{ $record->kegiatan->waktu_mulai->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $record->status_badge }}">
                                            {{ $record->status_label }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($record->kegiatan->presensi)
                                            <span
                                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium {{ $record->status_presensi === 'hadir' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                                                <ion-icon
                                                    name="{{ $record->status_presensi === 'hadir' ? 'checkmark-circle' : 'time-outline' }}"></ion-icon>
                                                {{ $record->status_presensi === 'hadir' ? 'Hadir' : 'Belum Hadir' }}
                                            </span>
                                        @else
                                            <span class="text-gray-500">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">
                                        {{ $record->kegiatan->presensi && $record->waktu_presensi ? $record->waktu_presensi->format('d M Y, H:i') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('mahasiswa.kegiatan.show', $record->kegiatan) }}"
                                            class="inline-flex items-center justify-center h-9 w-9 text-white bg-simawa-600 hover:bg-simawa-700 rounded-lg transition-colors"
                                            title="Lihat Detail">
                                            <ion-icon name="eye-outline" class="text-lg"></ion-icon>
                                        </a>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 py-12 text-center">
            <ion-icon name="calendar-clear-outline" class="text-5xl text-gray-300 mb-3"></ion-icon>
            <p class="text-gray-500 mb-4">Anda belum mendaftar ke kegiatan apapun</p>
            <a href="{{ route('mahasiswa.kegiatan.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-simawa-500 to-simawa-700 text-white rounded-lg hover:from-simawa-600 hover:to-simawa-800 transition-all text-sm font-medium">
                <ion-icon name="search-outline"></ion-icon>
                Cari Kegiatan
            </a>
        </div>
    @endif

    <!-- QR Code Modal -->
    @if($mahasiswa)
        <div id="qrModal" class="fixed inset-0 z-50 hidden">
            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="hideQrModal()"></div>
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="relative bg-white rounded-2xl shadow-xl max-w-sm w-full p-6 transform transition-all">
                    <button onclick="hideQrModal()"
                        class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors">
                        <ion-icon name="close-outline" class="text-2xl"></ion-icon>
                    </button>

                    <div class="text-center">
                        <div
                            class="w-16 h-16 bg-gradient-to-br from-simawa-400 to-simawa-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <ion-icon name="qr-code-outline" class="text-3xl text-white"></ion-icon>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 mb-1">QR Code Presensi</h3>
                        <p class="text-sm text-gray-500 mb-4">Tunjukkan QR ini kepada petugas untuk presensi</p>

                        <div class="bg-white p-4 rounded-xl border-2 border-gray-100 inline-block mb-4">
                            <div id="qrcode" class="mx-auto"></div>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-3 text-left">
                            <p class="text-sm font-medium text-gray-800">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500 font-mono">{{ $mahasiswa->nim }}</p>
                            <p class="text-xs text-gray-500">{{ $mahasiswa->prodi }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@if($mahasiswa)
    @push('scripts')
        <!-- QRCode.js Library - Using reliable CDN -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

        <script>
            let qrGenerated = false;

            function showQrModal() {
                document.getElementById('qrModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';

                if (!qrGenerated) {
                    generateQrCode();
                    qrGenerated = true;
                }
            }

            function hideQrModal() {
                document.getElementById('qrModal').classList.add('hidden');
                document.body.style.overflow = '';
            }

            function generateQrCode() {
                const qrData = {
                    type: 'simkatmawa_presensi',
                    mahasiswa_id: '{{ $mahasiswa->id }}',
                    nim: '{{ $mahasiswa->nim }}',
                    name: '{{ auth()->user()->name }}'
                };

                const qrText = JSON.stringify(qrData);
                console.log('QR Data Generated:', qrText);

                const qrContainer = document.getElementById('qrcode');
                qrContainer.innerHTML = '';

                try {
                    new QRCode(qrContainer, {
                        text: qrText,
                        width: 256,
                        height: 256,
                        colorDark: '#000000',
                        colorLight: '#ffffff',
                        correctLevel: QRCode.CorrectLevel.L
                    });
                    console.log('QR Code generated successfully');
                } catch (error) {
                    console.error('Error generating QR:', error);
                    qrContainer.innerHTML = '<p class="text-red-500 text-sm">Gagal generate QR Code</p>';
                }
            }

            // Close modal on escape key
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    hideQrModal();
                }
            });
        </script>
    @endpush
@endif