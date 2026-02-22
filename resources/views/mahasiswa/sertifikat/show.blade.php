@extends('layouts.dashboard')

@section('title', 'Detail Sertifikat')
@section('page-title', 'Detail Sertifikat')
@section('page-description', 'Lihat detail pengajuan sertifikat')

@section('content')
    <div class="max-w-2xl mx-auto">
        <!-- Back Button -->
        <a href="{{ route('mahasiswa.sertifikat.index') }}"
            class="inline-flex items-center gap-2 text-gray-600 hover:text-simawa-600 mb-6 text-sm">
            <ion-icon name="arrow-back-outline"></ion-icon>
            Kembali ke Daftar
        </a>

        <!-- Detail Card -->
        @include('mahasiswa.sertifikat.show_partial')
    </div>
    </div>
@endsection