@extends('layouts.dashboard')

@section('title', 'Ajukan Sertifikat')
@section('page-title', 'Ajukan Sertifikat')
@section('page-description', 'Isi form untuk mengajukan sertifikat baru')

@section('content')
    <div class="w-full mx-auto">
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800">Form Pengajuan Sertifikat</h3>
                <p class="text-sm text-gray-500 mt-1">Lengkapi data sertifikat Anda</p>
            </div>

            <form action="{{ route('mahasiswa.sertifikat.store') }}" method="POST" enctype="multipart/form-data"
                class="p-6 space-y-5">
                @csrf

                <!-- Jenis Sertifikat -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Jenis Sertifikat <span class="text-red-500">*</span>
                    </label>
                    <select name="jenis_sertifikat_id" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-simawa-500/20 focus:border-simawa-500 transition-all text-sm">
                        <option value="">Pilih Jenis Sertifikat</option>
                        @foreach($jenisSertifikats as $jenis)
                            <option value="{{ $jenis->id }}" {{ old('jenis_sertifikat_id') == $jenis->id ? 'selected' : '' }}>
                                {{ $jenis->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('jenis_sertifikat_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nama Sertifikat -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Sertifikat <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_sertifikat" value="{{ old('nama_sertifikat') }}" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-simawa-500/20 focus:border-simawa-500 transition-all text-sm"
                        placeholder="Contoh: Test of English as a Foreign Language">
                    @error('nama_sertifikat')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nama Sertifikat (English) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Name in English
                    </label>
                    <input type="text" name="nama_sertifikat_en" value="{{ old('nama_sertifikat_en') }}"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-simawa-500/20 focus:border-simawa-500 transition-all text-sm"
                        placeholder="Certificate name in English (optional)">
                    @error('nama_sertifikat_en')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Penerbit -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Penerbit <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="penerbit" value="{{ old('penerbit') }}" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-simawa-500/20 focus:border-simawa-500 transition-all text-sm"
                        placeholder="Contoh: ETS, British Council, Kominfo">
                    @error('penerbit')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Terbit -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Terbit <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal_terbit" value="{{ old('tanggal_terbit') }}" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-simawa-500/20 focus:border-simawa-500 transition-all text-sm">
                    @error('tanggal_terbit')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- File Upload -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        File Sertifikat <span class="text-red-500">*</span>
                    </label>
                    <div
                        class="border-2 border-dashed border-gray-200 rounded-lg p-6 text-center hover:border-simawa-400 transition-colors">
                        <input type="file" name="file_sertifikat" id="file_sertifikat" required
                            accept=".pdf,.jpg,.jpeg,.png" class="hidden" onchange="updateFileName(this)">
                        <label for="file_sertifikat" class="cursor-pointer">
                            <ion-icon name="cloud-upload-outline" class="text-4xl text-gray-400 mb-2"></ion-icon>
                            <p id="fileName" class="text-sm text-gray-500">Klik untuk upload file</p>
                            <p class="text-xs text-gray-400 mt-1">PDF, JPG, PNG (Max. 5MB)</p>
                        </label>
                    </div>
                    @error('file_sertifikat')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row gap-3 pt-4">
                    <a href="{{ route('mahasiswa.sertifikat.index') }}"
                        class="w-full sm:flex-1 px-4 py-2.5 border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 transition-all text-sm font-medium text-center">
                        Batal
                    </a>
                    <button type="submit"
                        class="w-full sm:flex-1 px-4 py-2.5 bg-gradient-to-r from-simawa-500 to-simawa-700 text-white rounded-lg hover:from-simawa-600 hover:to-simawa-800 transition-all text-sm font-medium">
                        Kirim Pengajuan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function updateFileName(input) {
            const fileName = input.files[0]?.name || 'Klik untuk upload file';
            document.getElementById('fileName').textContent = fileName;
        }
    </script>
@endpush