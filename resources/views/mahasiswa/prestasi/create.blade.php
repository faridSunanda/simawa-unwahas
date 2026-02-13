@extends('layouts.dashboard')

@section('title', 'Ajukan Prestasi')
@section('page-title', 'Ajukan Prestasi')
@section('page-description', $formulirPrestasi->judul)

@section('content')
    <div class="max-w-3xl mx-auto">
        <!-- Back Button -->
        <a href="{{ route('mahasiswa.prestasi.index') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-simawa-600 mb-6 text-sm">
            <ion-icon name="arrow-back-outline"></ion-icon>
            Kembali ke Daftar
        </a>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-simawa-500 to-simawa-700 p-6 text-white">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
                        <ion-icon name="trophy-outline" class="text-3xl"></ion-icon>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold">{{ $formulirPrestasi->judul }}</h2>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-white/20 mt-2">
                            {{ $formulirPrestasi->kategoriPrestasi->nama }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Form Body -->
            <form action="{{ route('mahasiswa.prestasi.store', $formulirPrestasi) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf

                @foreach($formulirPrestasi->pertanyaans as $index => $pertanyaan)
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">
                            {{ $index + 1 }}. {{ $pertanyaan->pertanyaan }}
                            @if($pertanyaan->wajib)
                                <span class="text-red-500">*</span>
                            @endif
                        </label>

                        @if($pertanyaan->tipe === 'text')
                            <input type="text" name="jawaban[{{ $pertanyaan->id }}]"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-simawa-500/20 focus:border-simawa-500 transition-all text-sm @error('jawaban.'.$pertanyaan->id) border-red-500 @enderror"
                                placeholder="Masukkan jawaban..."
                                value="{{ old('jawaban.'.$pertanyaan->id) }}"
                                {{ $pertanyaan->wajib ? 'required' : '' }}>
                        @elseif($pertanyaan->tipe === 'dropdown')
                            <select name="jawaban[{{ $pertanyaan->id }}]"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-simawa-500/20 focus:border-simawa-500 transition-all text-sm @error('jawaban.'.$pertanyaan->id) border-red-500 @enderror"
                                {{ $pertanyaan->wajib ? 'required' : '' }}>
                                <option value="">Pilih salah satu...</option>
                                @foreach($pertanyaan->opsi as $opsi)
                                    <option value="{{ $opsi }}" {{ old('jawaban.'.$pertanyaan->id) == $opsi ? 'selected' : '' }}>{{ $opsi }}</option>
                                @endforeach
                            </select>
                        @elseif($pertanyaan->tipe === 'file')
                            <div class="relative">
                                <input type="file" name="jawaban[{{ $pertanyaan->id }}]" id="file-{{ $pertanyaan->id }}"
                                    class="hidden"
                                    accept=".pdf,.jpg,.jpeg,.png"
                                    {{ $pertanyaan->wajib ? 'required' : '' }}
                                    onchange="updateFileName('{{ $pertanyaan->id }}', this)">
                                <label for="file-{{ $pertanyaan->id }}"
                                    class="flex items-center justify-center gap-3 w-full px-4 py-8 border-2 border-dashed border-gray-200 rounded-lg cursor-pointer hover:border-simawa-400 hover:bg-simawa-50/30 transition-all @error('jawaban.'.$pertanyaan->id) border-red-500 @enderror">
                                    <ion-icon name="cloud-upload-outline" class="text-3xl text-gray-400"></ion-icon>
                                    <div class="text-center">
                                        <p class="text-sm text-gray-600" id="file-label-{{ $pertanyaan->id }}">Klik untuk upload file</p>
                                        <p class="text-xs text-gray-400 mt-1">PDF, JPG, PNG (Max. 5MB)</p>
                                    </div>
                                </label>
                            </div>
                        @endif

                        @error('jawaban.'.$pertanyaan->id)
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                @endforeach

                <!-- Submit Button -->
                <div class="pt-4 border-t border-gray-100 flex gap-3">
                    <a href="{{ route('mahasiswa.prestasi.index') }}"
                        class="flex-1 px-4 py-2.5 border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 transition-all text-sm font-medium text-center">
                        Batal
                    </a>
                    <button type="submit"
                        class="flex-1 px-4 py-2.5 bg-gradient-to-r from-simawa-500 to-simawa-700 text-white rounded-lg hover:from-simawa-600 hover:to-simawa-800 transition-all text-sm font-medium">
                        <ion-icon name="send-outline" class="mr-1"></ion-icon>
                        Kirim Pengajuan
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
