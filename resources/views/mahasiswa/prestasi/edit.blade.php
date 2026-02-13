@extends('layouts.dashboard')

@section('title', 'Edit Pengajuan')
@section('page-title', 'Edit Pengajuan')
@section('page-description', 'Revisi pengajuan prestasi Anda')

@section('content')
    <div class="max-w-3xl mx-auto">
        <!-- Back Button -->
        <a href="{{ route('mahasiswa.prestasi.show', $pengajuanPrestasi) }}"
            class="inline-flex items-center gap-2 text-gray-600 hover:text-simawa-600 mb-6 text-sm">
            <ion-icon name="arrow-back-outline"></ion-icon>
            Kembali ke Detail
        </a>

        <!-- Revision Notice -->
        @if($pengajuanPrestasi->catatan_verifikator)
            <div class="mb-6 p-4 bg-orange-50 border border-orange-200 rounded-lg">
                <p class="text-sm font-medium text-orange-800 mb-1">Catatan Verifikator:</p>
                <p class="text-sm text-orange-700">{{ $pengajuanPrestasi->catatan_verifikator }}</p>
            </div>
        @endif

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 p-6 text-white">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
                        <ion-icon name="create-outline" class="text-3xl"></ion-icon>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold">Revisi: {{ $pengajuanPrestasi->formulirPrestasi->judul }}</h2>
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-white/20 mt-2">
                            {{ $pengajuanPrestasi->formulirPrestasi->kategoriPrestasi->nama }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Form Body -->
            <form action="{{ route('mahasiswa.prestasi.update', $pengajuanPrestasi) }}" method="POST"
                enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                @foreach($pengajuanPrestasi->formulirPrestasi->pertanyaans as $index => $pertanyaan)
                    @php
                        $jawaban = $pengajuanPrestasi->jawabans->where('formulir_pertanyaan_id', $pertanyaan->id)->first();
                    @endphp
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">
                            {{ $index + 1 }}. {{ $pertanyaan->pertanyaan }}
                            @if($pertanyaan->wajib)
                                <span class="text-red-500">*</span>
                            @endif
                        </label>

                        @if($pertanyaan->tipe === 'text')
                            <input type="text" name="jawaban[{{ $pertanyaan->id }}]"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-simawa-500/20 focus:border-simawa-500 transition-all text-sm @error('jawaban.' . $pertanyaan->id) border-red-500 @enderror"
                                placeholder="Masukkan jawaban..."
                                value="{{ old('jawaban.' . $pertanyaan->id, $jawaban->jawaban ?? '') }}" {{ $pertanyaan->wajib ? 'required' : '' }}>
                        @elseif($pertanyaan->tipe === 'dropdown')
                            <select name="jawaban[{{ $pertanyaan->id }}]"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-simawa-500/20 focus:border-simawa-500 transition-all text-sm @error('jawaban.' . $pertanyaan->id) border-red-500 @enderror"
                                {{ $pertanyaan->wajib ? 'required' : '' }}>
                                <option value="">Pilih salah satu...</option>
                                @foreach($pertanyaan->opsi as $opsi)
                                    <option value="{{ $opsi }}" {{ old('jawaban.' . $pertanyaan->id, $jawaban->jawaban ?? '') == $opsi ? 'selected' : '' }}>{{ $opsi }}</option>
                                @endforeach
                            </select>
                        @elseif($pertanyaan->tipe === 'file')
                            <div class="space-y-2">
                                @if($jawaban && $jawaban->file_path)
                                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                        <ion-icon name="document-outline" class="text-xl text-gray-500"></ion-icon>
                                        <a href="{{ Storage::url($jawaban->file_path) }}" target="_blank"
                                            class="text-sm text-simawa-600 hover:text-simawa-700 font-medium">
                                            File saat ini
                                        </a>
                                        <span class="text-xs text-gray-400">(Opsional: upload file baru untuk mengganti)</span>
                                    </div>
                                @endif
                                <div class="relative">
                                    <input type="file" name="jawaban[{{ $pertanyaan->id }}]" id="file-{{ $pertanyaan->id }}"
                                        class="hidden" accept=".pdf,.jpg,.jpeg,.png"
                                        onchange="updateFileName('{{ $pertanyaan->id }}', this)">
                                    <label for="file-{{ $pertanyaan->id }}"
                                        class="flex items-center justify-center gap-3 w-full px-4 py-6 border-2 border-dashed border-gray-200 rounded-lg cursor-pointer hover:border-simawa-400 hover:bg-simawa-50/30 transition-all">
                                        <ion-icon name="cloud-upload-outline" class="text-2xl text-gray-400"></ion-icon>
                                        <div class="text-center">
                                            <p class="text-sm text-gray-600" id="file-label-{{ $pertanyaan->id }}">Upload file baru
                                            </p>
                                            <p class="text-xs text-gray-400 mt-1">PDF, JPG, PNG (Max. 5MB)</p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        @endif

                        @error('jawaban.' . $pertanyaan->id)
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                @endforeach

                <!-- Submit Button -->
                <div class="pt-4 border-t border-gray-100 flex gap-3">
                    <a href="{{ route('mahasiswa.prestasi.show', $pengajuanPrestasi) }}"
                        class="flex-1 px-4 py-2.5 border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 transition-all text-sm font-medium text-center">
                        Batal
                    </a>
                    <button type="submit"
                        class="flex-1 px-4 py-2.5 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-lg hover:from-orange-600 hover:to-orange-700 transition-all text-sm font-medium">
                        <ion-icon name="send-outline" class="mr-1"></ion-icon>
                        Submit Ulang
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function updateFileName(id, input) {
            const label = document.getElementById('file-label-' + id);
            if (input.files && input.files[0]) {
                label.textContent = input.files[0].name;
                label.classList.add('text-simawa-600', 'font-medium');
            }
        }
    </script>
@endpush