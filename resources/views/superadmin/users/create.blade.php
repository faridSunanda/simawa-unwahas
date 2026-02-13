@extends('layouts.dashboard')

@section('title', 'Tambah User')
@section('page-title', 'Tambah User')
@section('page-description', 'Tambahkan pengguna baru ke sistem')

@section('content')
    <!-- Page Title -->
    <div class="mb-6">
        <div class="flex items-center gap-2 mb-2">
            <a href="{{ route('superadmin.users.index') }}" class="text-gray-400 hover:text-simawa-600 transition-colors">
                <ion-icon name="arrow-back-outline" class="text-xl"></ion-icon>
            </a>
            <h2 class="text-xl font-bold text-gray-800">Tambah User</h2>
        </div>
        <p class="text-sm text-gray-500 ml-7">Tambahkan pengguna baru ke sistem</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-2xl">
        <form action="{{ route('superadmin.users.store') }}" method="POST" id="userForm">
            @csrf

            <div class="space-y-5">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-simawa-500 focus:border-simawa-500 transition-colors @error('name') border-red-500 @enderror"
                        placeholder="Masukkan nama lengkap">
                    @error('name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email <span
                            class="text-red-500">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-simawa-500 focus:border-simawa-500 transition-colors @error('email') border-red-500 @enderror"
                        placeholder="Masukkan email">
                    @error('email')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Role -->
                <div>
                    <label for="role_id" class="block text-sm font-medium text-gray-700 mb-1.5">Role <span
                            class="text-red-500">*</span></label>
                    <select name="role_id" id="role_id" required onchange="toggleRoleFields()"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-simawa-500 focus:border-simawa-500 transition-colors @error('role_id') border-red-500 @enderror">
                        <option value="">Pilih Role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" data-role="{{ $role->name }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                {{ $role->display_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('role_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Mahasiswa Fields -->
                <div id="mahasiswaFields" class="hidden space-y-5 p-4 bg-blue-50 rounded-lg border border-blue-100">
                    <p class="text-sm font-medium text-blue-700 mb-3">Data Mahasiswa</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="nim" class="block text-sm font-medium text-gray-700 mb-1.5">NIM <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="nim" id="nim" value="{{ old('nim') }}"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-simawa-500 focus:border-simawa-500 transition-colors @error('nim') border-red-500 @enderror"
                                placeholder="Masukkan NIM">
                            @error('nim')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="angkatan" class="block text-sm font-medium text-gray-700 mb-1.5">Angkatan</label>
                            <input type="text" name="angkatan" id="angkatan" value="{{ old('angkatan') }}"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-simawa-500 focus:border-simawa-500 transition-colors"
                                placeholder="Contoh: 2024">
                        </div>
                    </div>
                </div>

                <!-- Dosen Fields -->
                <div id="dosenFields" class="hidden space-y-5 p-4 bg-purple-50 rounded-lg border border-purple-100">
                    <p class="text-sm font-medium text-purple-700 mb-3">Data Dosen/Pejabat</p>
                    <div>
                        <label for="nip" class="block text-sm font-medium text-gray-700 mb-1.5">NIP</label>
                        <input type="text" name="nip" id="nip" value="{{ old('nip') }}"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-simawa-500 focus:border-simawa-500 transition-colors"
                            placeholder="Masukkan NIP (opsional)">
                    </div>
                </div>

                <!-- Staff Fields -->
                <div id="staffFields" class="hidden space-y-5 p-4 bg-amber-50 rounded-lg border border-amber-100">
                    <p class="text-sm font-medium text-amber-700 mb-3">Data Staff</p>
                    <div>
                        <label for="bagian" class="block text-sm font-medium text-gray-700 mb-1.5">Bagian</label>
                        <input type="text" name="bagian" id="bagian" value="{{ old('bagian', 'Kemahasiswaan') }}"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-simawa-500 focus:border-simawa-500 transition-colors"
                            placeholder="Masukkan bagian">
                    </div>
                </div>

                <!-- Fakultas & Prodi Fields -->
                <div id="unitFields" class="hidden space-y-5 p-4 bg-green-50 rounded-lg border border-green-100">
                    <p class="text-sm font-medium text-green-700 mb-3">Unit Organisasi</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="fakultas" class="block text-sm font-medium text-gray-700 mb-1.5">Fakultas <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="fakultas" id="fakultas" value="{{ old('fakultas') }}"
                                list="fakultasList"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-simawa-500 focus:border-simawa-500 transition-colors @error('fakultas') border-red-500 @enderror"
                                placeholder="Pilih atau ketik fakultas">
                            <datalist id="fakultasList">
                                @foreach($fakultasList as $fak)
                                    <option value="{{ $fak }}">
                                @endforeach
                            </datalist>
                            @error('fakultas')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div id="prodiContainer">
                            <label for="prodi" class="block text-sm font-medium text-gray-700 mb-1.5">Program Studi <span
                                    id="prodiRequired" class="text-red-500 hidden">*</span></label>
                            <input type="text" name="prodi" id="prodi" value="{{ old('prodi') }}" list="prodiList"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-simawa-500 focus:border-simawa-500 transition-colors @error('prodi') border-red-500 @enderror"
                                placeholder="Pilih atau ketik prodi">
                            <datalist id="prodiList">
                                @foreach($prodiList as $prod)
                                    <option value="{{ $prod }}">
                                @endforeach
                            </datalist>
                            @error('prodi')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Password <span
                            class="text-red-500">*</span></label>
                    <input type="password" name="password" id="password" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-simawa-500 focus:border-simawa-500 transition-colors @error('password') border-red-500 @enderror"
                        placeholder="Masukkan password (min. 8 karakter)">
                    @error('password')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1.5">Konfirmasi
                        Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-simawa-500 focus:border-simawa-500 transition-colors"
                        placeholder="Ulangi password">
                </div>

                <!-- Active Status -->
                <div class="flex items-center gap-3">
                    <input type="checkbox" name="is_active" id="is_active" value="1" checked
                        class="w-5 h-5 text-simawa-600 border-gray-300 rounded focus:ring-simawa-500">
                    <label for="is_active" class="text-sm font-medium text-gray-700">User aktif</label>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex items-center gap-3 mt-8 pt-6 border-t border-gray-100">
                <button type="submit"
                    class="px-6 py-2.5 bg-gradient-to-r from-simawa-500 to-simawa-700 text-white rounded-lg hover:from-simawa-600 hover:to-simawa-800 transition-all font-medium">
                    <span class="flex items-center gap-2">
                        <ion-icon name="save-outline"></ion-icon>
                        Simpan User
                    </span>
                </button>
                <a href="{{ route('superadmin.users.index') }}"
                    class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        function toggleRoleFields() {
            const select = document.getElementById('role_id');
            const selectedOption = select.options[select.selectedIndex];
            const roleName = selectedOption?.dataset?.role || '';

            // Hide all role-specific fields
            document.getElementById('mahasiswaFields').classList.add('hidden');
            document.getElementById('dosenFields').classList.add('hidden');
            document.getElementById('staffFields').classList.add('hidden');
            document.getElementById('unitFields').classList.add('hidden');
            document.getElementById('prodiRequired').classList.add('hidden');

            // Show fields based on role
            if (roleName === 'mahasiswa') {
                document.getElementById('mahasiswaFields').classList.remove('hidden');
                document.getElementById('unitFields').classList.remove('hidden');
                document.getElementById('prodiRequired').classList.remove('hidden');
            } else if (['kaprodi', 'dekan', 'pimpinan'].includes(roleName)) {
                document.getElementById('dosenFields').classList.remove('hidden');
                document.getElementById('unitFields').classList.remove('hidden');
                if (roleName === 'kaprodi') {
                    document.getElementById('prodiRequired').classList.remove('hidden');
                }
            } else if (roleName === 'kemahasiswaan') {
                document.getElementById('staffFields').classList.remove('hidden');
            }
        }

        // Run on page load
        document.addEventListener('DOMContentLoaded', toggleRoleFields);
    </script>
@endpush