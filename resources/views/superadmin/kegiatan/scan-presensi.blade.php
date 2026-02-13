@extends('layouts.dashboard')

@section('title', 'Scan Presensi QR')
@section('page-title', 'Scan Presensi QR')
@section('page-description', 'Scan QR Code mahasiswa untuk presensi kegiatan')

@section('content')
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <a href="{{ route('superadmin.kegiatan.peserta', $kegiatan) }}"
                    class="text-gray-400 hover:text-simawa-600 transition-colors">
                    <ion-icon name="arrow-back-outline" class="text-xl"></ion-icon>
                </a>
                <h3 class="text-lg font-semibold text-gray-800">Scan Presensi QR</h3>
            </div>
            <p class="text-sm text-gray-500 mt-1">Arahkan kamera ke QR Code mahasiswa</p>
        </div>
    </div>

    <!-- Info Kegiatan -->
    <div class="bg-gradient-to-r from-simawa-500 to-simawa-700 rounded-xl p-5 mb-6 text-white">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center flex-shrink-0">
                <ion-icon name="qr-code-outline" class="text-2xl"></ion-icon>
            </div>
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
                        {{ $kegiatan->peserta->where('status_presensi', 'hadir')->count() }} /
                        {{ $kegiatan->peserta->count() }} Hadir
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- QR Scanner -->
        <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm">
            <h4 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <ion-icon name="camera-outline" class="text-simawa-600"></ion-icon>
                Scanner QR Code
            </h4>

            <div id="qr-reader" class="w-full rounded-lg overflow-hidden bg-gray-100"></div>

            <div id="scan-status" class="mt-4 hidden">
                <div class="p-4 rounded-lg" id="scan-result-box">
                    <div class="flex items-center gap-3">
                        <div id="scan-icon" class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0">
                            <ion-icon name="checkmark" class="text-xl"></ion-icon>
                        </div>
                        <div>
                            <p id="scan-message" class="font-medium"></p>
                            <p id="scan-detail" class="text-sm opacity-75"></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4 flex gap-2">
                <button id="btn-start" onclick="startScanner()"
                    class="flex-1 px-4 py-2.5 bg-simawa-600 text-white rounded-lg hover:bg-simawa-700 transition-colors flex items-center justify-center gap-2">
                    <ion-icon name="play-outline"></ion-icon>
                    Mulai Scan
                </button>
                <button id="btn-stop" onclick="stopScanner()"
                    class="flex-1 px-4 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors flex items-center justify-center gap-2 hidden">
                    <ion-icon name="stop-outline"></ion-icon>
                    Stop Scan
                </button>
            </div>
        </div>

        <!-- Recent Scans / Statistik -->
        <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm">
            <h4 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <ion-icon name="list-outline" class="text-simawa-600"></ion-icon>
                Riwayat Scan
            </h4>

            <div id="scan-history" class="space-y-3 max-h-96 overflow-y-auto">
                <div class="text-center py-8 text-gray-400">
                    <ion-icon name="scan-outline" class="text-4xl mb-2"></ion-icon>
                    <p class="text-sm">Belum ada scan</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Html5 QR Code Scanner Library -->
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

    <script>
        let html5QrCode = null;
        let isScanning = false;
        let isProcessing = false; // Flag to prevent duplicate scans
        const scanHistory = [];

        function startScanner() {
            html5QrCode = new Html5Qrcode("qr-reader");

            const config = {
                fps: 15,
                qrbox: function(viewfinderWidth, viewfinderHeight) {
                    // Use 70% of the viewfinder for the scan box
                    let minEdge = Math.min(viewfinderWidth, viewfinderHeight);
                    let qrboxSize = Math.floor(minEdge * 0.7);
                    return { width: qrboxSize, height: qrboxSize };
                },
                experimentalFeatures: {
                    useBarCodeDetectorIfSupported: true
                },
                rememberLastUsedCamera: true,
                showTorchButtonIfSupported: true
            };

            html5QrCode.start(
                { facingMode: "environment" },
                config,
                onScanSuccess,
                onScanFailure
            ).then(() => {
                isScanning = true;
                document.getElementById('btn-start').classList.add('hidden');
                document.getElementById('btn-stop').classList.remove('hidden');
                showScanResult(true, 'Scanner aktif', 'Arahkan kamera ke QR Code');
            }).catch(err => {
                console.error('Error starting scanner:', err);
                showScanResult(false, 'Gagal memulai kamera', err.message || 'Pastikan izin kamera diberikan');
            });
        }

        function stopScanner() {
            if (html5QrCode && isScanning) {
                html5QrCode.stop().then(() => {
                    isScanning = false;
                    isProcessing = false;
                    document.getElementById('btn-start').classList.remove('hidden');
                    document.getElementById('btn-stop').classList.add('hidden');
                }).catch(err => {
                    console.error('Error stopping scanner:', err);
                });
            }
        }

        async function onScanSuccess(decodedText, decodedResult) {
            // Prevent duplicate scans while processing
            if (isProcessing) {
                return;
            }
            isProcessing = true;

            console.log('QR Scanned:', decodedText);

            try {
                // Parse QR data
                let qrData;
                try {
                    qrData = JSON.parse(decodedText);
                } catch (parseError) {
                    showScanResult(false, 'QR Code tidak valid', 'Format JSON tidak valid');
                    setTimeout(() => { isProcessing = false; }, 2000);
                    return;
                }

                // Validate QR format
                if (!qrData.type || qrData.type !== 'simkatmawa_presensi' || !qrData.mahasiswa_id) {
                    showScanResult(false, 'QR Code tidak valid', 'Bukan QR presensi SIMKATMAWA');
                    setTimeout(() => { isProcessing = false; }, 2000);
                    return;
                }

                showScanResult(true, 'Memproses...', `${qrData.name || ''} (${qrData.nim || ''})`);

                // Send to server
                const response = await fetch('{{ route("superadmin.kegiatan.process-qr", $kegiatan) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        mahasiswa_id: qrData.mahasiswa_id
                    })
                });

                const result = await response.json();
                console.log('Server response:', result);

                if (result.success) {
                    showScanResult(true, result.message, `${result.data.nama} (${result.data.nim})`);
                    addToHistory(true, result.data.nama, result.data.nim, result.data.waktu_presensi);
                    // Play success sound if available
                    playBeep(true);
                } else {
                    showScanResult(false, result.message, result.data ? `${result.data.nama} (${result.data.nim})` : '');
                    if (result.data) {
                        addToHistory(false, result.data.nama, result.data.nim, 'Sudah hadir');
                    }
                    playBeep(false);
                }

            } catch (error) {
                console.error('Error processing QR:', error);
                showScanResult(false, 'Gagal memproses QR', error.message || 'Error koneksi ke server');
            }

            // Allow next scan after 2.5 seconds
            setTimeout(() => {
                isProcessing = false;
                // Reset to ready state
                if (isScanning) {
                    showScanResult(true, 'Scanner aktif', 'Siap scan berikutnya...');
                }
            }, 2500);
        }

        function onScanFailure(error) {
            // Ignore scan failures (no QR detected)
        }

        function showScanResult(success, message, detail) {
            const statusEl = document.getElementById('scan-status');
            const boxEl = document.getElementById('scan-result-box');
            const iconEl = document.getElementById('scan-icon');
            const messageEl = document.getElementById('scan-message');
            const detailEl = document.getElementById('scan-detail');

            statusEl.classList.remove('hidden');

            if (success) {
                boxEl.className = 'p-4 rounded-lg bg-emerald-50 text-emerald-800';
                iconEl.className = 'w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 bg-emerald-500 text-white';
                iconEl.innerHTML = '<ion-icon name="checkmark" class="text-xl"></ion-icon>';
            } else {
                boxEl.className = 'p-4 rounded-lg bg-red-50 text-red-800';
                iconEl.className = 'w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 bg-red-500 text-white';
                iconEl.innerHTML = '<ion-icon name="close" class="text-xl"></ion-icon>';
            }

            messageEl.textContent = message;
            detailEl.textContent = detail || '';
        }

        function addToHistory(success, nama, nim, waktu) {
            const historyEl = document.getElementById('scan-history');

            // Clear placeholder if first item
            if (scanHistory.length === 0) {
                historyEl.innerHTML = '';
            }

            scanHistory.unshift({ success, nama, nim, waktu });

            const item = document.createElement('div');
            item.className = `p-3 rounded-lg border ${success ? 'bg-emerald-50 border-emerald-200' : 'bg-amber-50 border-amber-200'}`;
            item.innerHTML = `
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full ${success ? 'bg-emerald-500' : 'bg-amber-500'} text-white flex items-center justify-center flex-shrink-0">
                            <ion-icon name="${success ? 'checkmark' : 'alert'}" class="text-sm"></ion-icon>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-800 text-sm truncate">${nama}</p>
                            <p class="text-xs text-gray-500">${nim} • ${waktu}</p>
                        </div>
                    </div>
                `;

            historyEl.insertBefore(item, historyEl.firstChild);
        }

        function playBeep(success) {
            try {
                const audioContext = new (window.AudioContext || window.webkitAudioContext)();
                const oscillator = audioContext.createOscillator();
                const gainNode = audioContext.createGain();

                oscillator.connect(gainNode);
                gainNode.connect(audioContext.destination);

                oscillator.frequency.value = success ? 800 : 300;
                oscillator.type = 'sine';
                gainNode.gain.value = 0.1;

                oscillator.start();
                oscillator.stop(audioContext.currentTime + 0.15);
            } catch (e) {
                // Audio not supported, ignore
            }
        }
    </script>
@endpush