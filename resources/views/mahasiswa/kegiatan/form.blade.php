@extends('layouts.dashboard')

@section('title', 'Formulir Pendaftaran - ' . $kegiatan->nama)
@section('page-title', 'Formulir Pendaftaran')
@section('page-description', 'Isi formulir pendaftaran untuk mengikuti kegiatan')

@section('content')
    <!-- Back Button -->
    <div class="mb-4">
        <a href="{{ route('mahasiswa.kegiatan.show', $kegiatan) }}"
            class="inline-flex items-center gap-2 text-gray-500 hover:text-simawa-600 transition-colors text-sm">
            <ion-icon name="arrow-back-outline" class="text-lg"></ion-icon>
            Kembali ke Detail Kegiatan
        </a>
    </div>

    <!-- Alert -->
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg flex items-center gap-3">
            <ion-icon name="close-circle" class="text-xl flex-shrink-0"></ion-icon>
            <span class="text-sm">{{ session('error') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-gray-50/50">
            <h3 class="text-lg font-semibold text-gray-800">Formulir Pendaftaran: {{ $kegiatan->nama }}</h3>
            <p class="text-sm text-gray-500 mt-1">Silakan isi formulir di bawah ini dengan benar untuk mendaftar kegiatan.</p>
        </div>

        <form action="{{ route('mahasiswa.kegiatan.daftar', $kegiatan) }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            
            <div class="space-y-6">


                <!-- Dynamic Questions -->
                @if($kegiatan->pertanyaans && count($kegiatan->pertanyaans) > 0)
                    @foreach($kegiatan->pertanyaans as $q)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                {{ $q->pertanyaan }}
                                @if(isset($q->wajib) && $q->wajib)
                                    <span class="text-red-500">*</span>
                                @endif
                            </label>

                            @if($q->tipe === 'text')
                                <input type="text" name="jawaban[{{ $q->id }}]" 
                                    {{ isset($q->wajib) && $q->wajib ? 'required' : '' }}
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-simawa-500/20 focus:border-simawa-500 transition-all text-sm"
                                    placeholder="Tulis jawaban Anda">
                                    
                            @elseif($q->tipe === 'dropdown')
                                <select name="jawaban[{{ $q->id }}]"
                                    {{ isset($q->wajib) && $q->wajib ? 'required' : '' }}
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-simawa-500/20 focus:border-simawa-500 transition-all text-sm bg-white">
                                    <option value="" disabled selected>Pilih salah satu</option>
                                    @php
                                        // Kadang opsi tersimpan sebagai string array atau decodable
                                        $opsiItems = isset($q->opsi) ? (is_string($q->opsi) ? json_decode($q->opsi, true) : $q->opsi) : [];
                                    @endphp
                                    @if(is_array($opsiItems))
                                        @foreach($opsiItems as $opsi)
                                            <option value="{{ $opsi }}">{{ $opsi }}</option>
                                        @endforeach
                                    @endif
                                </select>

                            @elseif($q->tipe === 'file')
                                <input type="file" name="jawaban[{{ $q->id }}]"
                                    {{ isset($q->wajib) && $q->wajib ? 'required' : '' }}
                                    class="w-full px-3 py-2 text-sm text-gray-500 border border-gray-200 rounded-lg focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-simawa-50 file:text-simawa-600 hover:file:bg-simawa-100 transition-all">
                                <p class="text-xs text-gray-500 mt-1">Upload file</p>
                            @endif
                        </div>
                    @endforeach
                @else
                    <p class="text-sm text-gray-500 italic">Belum ada pertanyaan pada formulir ini.</p>
                @endif
            </div>

            <div class="mt-8 pt-6 border-t border-gray-100 flex gap-3">
                <a href="{{ route('mahasiswa.kegiatan.show', $kegiatan) }}"
                    class="px-6 py-2.5 border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 transition-all text-sm font-medium">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-2.5 bg-gradient-to-r from-simawa-500 to-simawa-700 text-white rounded-lg hover:from-simawa-600 hover:to-simawa-800 transition-all text-sm font-medium flex items-center gap-2">
                    <ion-icon name="save-outline"></ion-icon>
                    Simpan & Daftar
                </button>
            </div>
        </form>
    </div>
@endsection
