@extends('layouts.dashboard')

@section('title', 'Sertifikat Kegiatan')
@section('page-title', 'Sertifikat Kegiatan')
@section('page-description', 'Kelola template sertifikat kegiatan')

@section('content')
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <a href="{{ route('superadmin.kegiatan.index') }}"
                    class="text-gray-400 hover:text-simawa-600 transition-colors">
                    <ion-icon name="arrow-back-outline" class="text-xl"></ion-icon>
                </a>
                <h3 class="text-lg font-semibold text-gray-800">Sertifikat Kegiatan</h3>
            </div>
            <p class="text-sm text-gray-500 mt-1">Kelola template sertifikat untuk kegiatan ini</p>
        </div>
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
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center gap-3">
            <ion-icon name="checkmark-circle" class="text-xl flex-shrink-0"></ion-icon>
            <span class="text-sm">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg flex items-center gap-3">
            <ion-icon name="alert-circle" class="text-xl flex-shrink-0"></ion-icon>
            <span class="text-sm">{{ session('error') }}</span>
        </div>
    @endif

    <div class="grid lg:grid-cols-2 gap-6">
        <!-- Template Upload Section -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
            <div class="p-5 border-b border-gray-100">
                <h4 class="text-base font-semibold text-gray-800">Template Sertifikat (DOCX)</h4>
                <p class="text-xs text-gray-500 mt-1">Upload file Word (.docx) sebagai template sertifikat</p>
            </div>
            <div class="p-5">
                @if($kegiatan->sertifikat_template)
                    <!-- Template Info -->
                    <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-xl">
                        <div class="flex items-center gap-3">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-green-800 truncate">Template tersedia</p>
                                <p class="text-xs text-green-600">{{ basename($kegiatan->sertifikat_template) }}</p>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-2 mt-3">
                            <a href="{{ Storage::url($kegiatan->sertifikat_template) }}" download
                                class="inline-flex items-center gap-2 px-3 py-2 bg-white text-green-600 rounded-lg text-xs font-medium hover:bg-green-100 transition-colors border border-green-200">
                                Download Template
                            </a>
                            <form action="{{ route('superadmin.kegiatan.sertifikat.delete', $kegiatan) }}" method="POST"
                                class="inline" onsubmit="return confirm('Yakin ingin menghapus template?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center gap-2 px-3 py-2 bg-red-50 text-red-600 rounded-lg text-xs font-medium hover:bg-red-100 transition-colors">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- No Template -->
                    <div class="text-center py-8 border-2 border-dashed border-gray-200 rounded-xl mb-4">
                        <ion-icon name="document-outline" class="text-4xl text-gray-300 mb-2"></ion-icon>
                        <p class="text-gray-500 text-sm">Belum ada template</p>
                        <p class="text-gray-400 text-xs mt-1">Upload file Word (.docx)</p>
                    </div>
                @endif

                <!-- Upload Form -->
                <form action="{{ route('superadmin.kegiatan.sertifikat.upload', $kegiatan) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <label class="relative block cursor-pointer mb-3">
                        <input type="file" name="sertifikat_template" id="fileInput" accept=".docx" required
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                            onchange="updateFileName(this)">
                        <div id="uploadBox"
                            class="flex items-center gap-3 px-4 py-3 border-2 border-dashed border-gray-200 rounded-xl hover:border-simawa-400 transition-colors">
                            <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                                <ion-icon name="cloud-upload-outline" class="text-xl text-gray-400"></ion-icon>
                            </div>
                            <div>
                                <p id="fileName" class="text-sm font-medium text-gray-700">Pilih file Word (.docx)</p>
                                <p class="text-xs text-gray-400">DOCX (Max 5MB)</p>
                            </div>
                        </div>
                    </label>
                    @error('sertifikat_template')
                        <p class="text-red-500 text-xs mb-2">{{ $message }}</p>
                    @enderror
                    <button type="submit"
                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-simawa-500 to-simawa-600 text-white rounded-lg text-sm font-medium hover:from-simawa-600 hover:to-simawa-700 transition-all">
                        <ion-icon name="cloud-upload-outline"></ion-icon>
                        {{ $kegiatan->sertifikat_template ? 'Ganti Template' : 'Upload Template' }}
                    </button>
                </form>
            </div>
        </div>

        <!-- Placeholder Info Section -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
            <div class="p-5 border-b border-gray-100">
                <h4 class="text-base font-semibold text-gray-800">Panduan Placeholder</h4>
                <p class="text-xs text-gray-500 mt-1">Gunakan placeholder berikut dalam template Word</p>
            </div>
            <div class="p-5">
                <div class="space-y-3">
                    <div class="p-3 bg-blue-50 rounded-lg border border-blue-100">
                        <p class="text-xs text-blue-600 mb-2 font-medium">Cara Penggunaan:</p>
                        <p class="text-xs text-blue-700">Ketik placeholder di posisi yang diinginkan dalam file Word.
                            Placeholder akan otomatis diganti dengan data peserta saat sertifikat di-generate.</p>
                    </div>

                    <div class="overflow-hidden rounded-lg border border-gray-200">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600">Placeholder</th>
                                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr>
                                    <td class="px-3 py-2"><code
                                            class="text-xs bg-gray-100 px-1.5 py-0.5 rounded text-simawa-600">${NAMA_PESERTA}</code>
                                    </td>
                                    <td class="px-3 py-2 text-xs text-gray-600">Nama lengkap peserta</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($kegiatan->sertifikat_template)
                    <!-- Preview Button -->
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <a href="{{ route('superadmin.kegiatan.sertifikat.preview', $kegiatan) }}?name=Nama%20Peserta%20Contoh&nim=1234567890"
                            target="_blank"
                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 border border-simawa-500 text-simawa-600 rounded-lg text-sm font-medium hover:bg-simawa-50 transition-all">
                            Preview Sertifikat
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Peserta yang Hadir -->
    @if($kegiatan->sertifikat_template)
        @php
            $pesertaHadir = $kegiatan->peserta()->where('status_presensi', 'hadir')->with('mahasiswa.user')->get();
        @endphp
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm mt-6">
            <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                <h4 class="text-base font-semibold text-gray-800">Peserta Hadir ({{ $pesertaHadir->count() }})</h4>
            </div>
            <div class="p-5">
                @if($pesertaHadir->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">No</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Nama</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">NIM</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($pesertaHadir as $index => $p)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-gray-600">{{ $index + 1 }}</td>
                                        <td class="px-4 py-3 font-medium text-gray-800">{{ $p->mahasiswa->user->name ?? '-' }}</td>
                                        <td class="px-4 py-3 text-gray-600">{{ $p->mahasiswa->nim ?? '-' }}</td>
                                        <td class="px-4 py-3 text-center">
                                            <a href="{{ route('superadmin.kegiatan.peserta.download-sertifikat', $p) }}"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 bg-simawa-500 text-white rounded-lg text-xs font-medium hover:bg-simawa-600 transition-colors">
                                                Download
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <ion-icon name="people-outline" class="text-4xl text-gray-300 mb-2"></ion-icon>
                        <p class="text-sm">Belum ada peserta yang hadir</p>
                    </div>
                @endif
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        function updateFileName(input) {
            const fileName = input.files[0] ? input.files[0].name : 'Pilih file Word (.docx)';
            document.getElementById('fileName').textContent = fileName;
            if (input.files[0]) {
                document.getElementById('uploadBox').classList.remove('border-gray-200');
                document.getElementById('uploadBox').classList.add('border-simawa-400', 'bg-simawa-50/50');
            }
        }
    </script>
@endpush