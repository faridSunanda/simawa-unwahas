@extends('layouts.dashboard')

@section('title', 'Edit Sertifikat')
@section('page-title', 'Edit Sertifikat')
@section('page-description', 'Perbaiki data sertifikat Anda')

@section('content')
    <div class="max-w-2xl mx-auto">
        <!-- Back Button -->
        <a href="{{ route('mahasiswa.sertifikat.index') }}"
            class="inline-flex items-center gap-2 text-gray-600 hover:text-simawa-600 mb-6 text-sm">
            <ion-icon name="arrow-back-outline"></ion-icon>
            Kembali ke Daftar
        </a>

        @include('mahasiswa.sertifikat.edit_partial')
    </div>
@endsection

@push('scripts')
<script>
    function updateFileName(input) {
        const fileName = input.files[0]?.name || 'Klik untuk upload file baru';
        document.getElementById('fileName').textContent = fileName;
    }
</script>
@endpush
