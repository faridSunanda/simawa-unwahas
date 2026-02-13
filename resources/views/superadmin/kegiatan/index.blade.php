@extends('layouts.dashboard')

@section('title', 'Kegiatan')
@section('page-title', 'Kegiatan')
@section('page-description', 'Kelola semua kegiatan universitas')

@section('content')
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h3 class="text-lg font-semibold text-gray-800">Daftar Kegiatan</h3>
            <p class="text-sm text-gray-500 mt-1">Kelola semua kegiatan universitas</p>
        </div>
        <button type="button" id="btnTambah"
            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-simawa-500 to-simawa-700 text-white rounded-lg hover:from-simawa-600 hover:to-simawa-800 transition-all shadow-lg shadow-simawa-500/25 text-sm font-medium">
            <ion-icon name="add-circle-outline" class="text-lg"></ion-icon>
            Tambah Kegiatan
        </button>
    </div>

    <!-- Success/Error Alert -->
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center gap-3">
            <ion-icon name="checkmark-circle" class="text-xl flex-shrink-0"></ion-icon>
            <span class="text-sm">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Mobile Filter & Search -->
    <div class="lg:hidden bg-white rounded-xl p-4 border border-gray-100 shadow-sm mb-6">
        <div class="flex flex-col gap-4">
            <div class="flex-1">
                <div class="relative">
                    <ion-icon name="search-outline"
                        class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></ion-icon>
                    <input type="text" id="searchKegiatan" placeholder="Cari kegiatan..."
                        class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-simawa-500/20 focus:border-simawa-500">
                </div>
            </div>
            <select id="filterTampilkan"
                class="px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-simawa-500/20 focus:border-simawa-500 bg-white">
                <option value="">Semua Status Tampil</option>
                <option value="1">Tampilkan: Ya</option>
                <option value="0">Tampilkan: Tidak</option>
            </select>
            <select id="filterPendaftaran"
                class="px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-simawa-500/20 focus:border-simawa-500 bg-white">
                <option value="">Semua Status Pendaftaran</option>
                <option value="buka">Buka</option>
                <option value="tutup">Tutup</option>
            </select>
        </div>
    </div>

    @if($kegiatans->count() > 0)
        <!-- Mobile Card View -->
        <div class="lg:hidden space-y-3">
            @foreach($kegiatans as $kegiatan)
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 kegiatan-row"
                    data-nama="{{ strtolower($kegiatan->nama) }}" data-detail="{{ strtolower($kegiatan->detail ?? '') }}"
                    data-tampilkan="{{ $kegiatan->tampilkan ? '1' : '0' }}" data-pendaftaran="{{ $kegiatan->pendaftaran }}">
                    <div class="flex justify-between items-start mb-3">
                        <h4 class="font-semibold text-gray-800">{{ $kegiatan->nama }}</h4>
                        <div class="relative">
                            <button onclick="toggleDropdown('{{ $kegiatan->id }}')"
                                class="p-2 text-gray-500 hover:bg-gray-100 rounded-lg transition-colors">
                                <ion-icon name="ellipsis-vertical" class="text-lg"></ion-icon>
                            </button>
                            <div id="dropdown-{{ $kegiatan->id }}"
                                class="dropdown-menu hidden absolute right-0 mt-1 w-48 bg-white rounded-xl shadow-2xl border border-gray-200 py-2 z-50">
                                <button type="button" data-edit="{{ $kegiatan->id }}" data-nama="{{ $kegiatan->nama }}"
                                    data-detail="{{ $kegiatan->detail }}"
                                    data-mulai="{{ $kegiatan->waktu_mulai->format('Y-m-d\TH:i') }}"
                                    data-selesai="{{ $kegiatan->waktu_selesai->format('Y-m-d\TH:i') }}"
                                    data-tampilkan="{{ $kegiatan->tampilkan ? '1' : '0' }}"
                                    data-pendaftaran="{{ $kegiatan->pendaftaran }}"
                                    class="btn-edit w-full flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                    <ion-icon name="create-outline" class="text-amber-500"></ion-icon>Edit
                                </button>
                                <button type="button" data-delete="{{ $kegiatan->id }}"
                                    class="btn-delete w-full flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                    <ion-icon name="trash-outline" class="text-red-500"></ion-icon>Hapus
                                </button>
                                <div class="border-t border-gray-100 my-1"></div>
                                <a href="{{ route('superadmin.kegiatan.peserta', $kegiatan) }}"
                                    class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                    <ion-icon name="people-outline" class="text-blue-500"></ion-icon>Lihat Peserta
                                </a>
                                <a href="{{ route('superadmin.kegiatan.sertifikat', $kegiatan) }}"
                                    class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                    <ion-icon name="ribbon-outline" class="text-purple-500"></ion-icon>Sertifikat
                                </a>
                                <a href="{{ route('superadmin.kegiatan.rundown', $kegiatan) }}"
                                    class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                    <ion-icon name="list-outline" class="text-emerald-500"></ion-icon>Rundown
                                </a>
                            </div>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $kegiatan->detail ?? '-' }}</p>
                    <div class="grid grid-cols-2 gap-2 text-xs mb-3">
                        <div>
                            <span class="text-gray-500">Mulai:</span>
                            <p class="font-medium text-gray-700">{{ $kegiatan->waktu_mulai->format('d M Y H:i') }}</p>
                        </div>
                        <div>
                            <span class="text-gray-500">Selesai:</span>
                            <p class="font-medium text-gray-700">{{ $kegiatan->waktu_selesai->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <span
                            class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium {{ $kegiatan->tampilkan ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                            {{ $kegiatan->tampilkan ? 'Tampil' : 'Sembunyi' }}
                        </span>
                        <span
                            class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium {{ $kegiatan->pendaftaran === 'buka' ? 'bg-blue-100 text-blue-700' : 'bg-red-100 text-red-700' }}">
                            {{ ucfirst($kegiatan->pendaftaran) }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Desktop Table View -->
        <div class="hidden lg:block bg-white rounded-md shadow-sm border border-gray-100 overflow-hidden">
            <table id="kegiatanTable" class="w-full">
                <thead>
                    <tr>
                        <th class="text-left w-16">No</th>
                        <th class="text-left">Nama Kegiatan</th>
                        <th class="text-left">Detail</th>
                        <th class="text-left">Waktu Mulai</th>
                        <th class="text-left">Waktu Selesai</th>
                        <th class="text-center">Tampilkan</th>
                        <th class="text-center">Pendaftaran</th>
                        <th class="text-center w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kegiatans as $index => $kegiatan)
                        <tr>
                            <td class="text-sm text-gray-600 font-medium">{{ $index + 1 }}</td>
                            <td>
                                <span class="text-sm font-medium text-gray-800">{{ $kegiatan->nama }}</span>
                            </td>
                            <td class="text-sm text-gray-600 max-w-xs">
                                <p class="line-clamp-2">{{ Str::limit($kegiatan->detail ?? '-', 50) }}</p>
                            </td>
                            <td class="text-sm text-gray-600 whitespace-nowrap">
                                {{ $kegiatan->waktu_mulai->format('d M Y H:i') }}</td>
                            <td class="text-sm text-gray-600 whitespace-nowrap">
                                {{ $kegiatan->waktu_selesai->format('d M Y H:i') }}</td>
                            <td class="text-center">
                                <span
                                    class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium {{ $kegiatan->tampilkan ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $kegiatan->tampilkan ? 'Ya' : 'Tidak' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span
                                    class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium {{ $kegiatan->pendaftaran === 'buka' ? 'bg-blue-100 text-blue-700' : 'bg-red-100 text-red-700' }}">
                                    {{ ucfirst($kegiatan->pendaftaran) }}
                                </span>
                            </td>
                            <td>
                                <div class="flex justify-center">
                                    <button onclick="toggleDropdown(event, '{{ $kegiatan->id }}-desktop')"
                                        class="dropdown-trigger p-2 text-gray-500 hover:bg-gray-100 rounded-lg transition-colors">
                                        <ion-icon name="ellipsis-vertical" class="text-lg"></ion-icon>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Floating Dropdown Menus (Desktop) -->
        @foreach($kegiatans as $kegiatan)
            <div id="dropdown-{{ $kegiatan->id }}-desktop"
                class="dropdown-menu hidden fixed w-48 bg-white rounded-xl shadow-2xl border border-gray-200 py-2 z-[9999]">
                <button type="button" data-edit="{{ $kegiatan->id }}" data-nama="{{ $kegiatan->nama }}"
                    data-detail="{{ $kegiatan->detail }}"
                    data-mulai="{{ $kegiatan->waktu_mulai->format('Y-m-d\TH:i') }}"
                    data-selesai="{{ $kegiatan->waktu_selesai->format('Y-m-d\TH:i') }}"
                    data-tampilkan="{{ $kegiatan->tampilkan ? '1' : '0' }}"
                    data-pendaftaran="{{ $kegiatan->pendaftaran }}"
                    class="btn-edit w-full flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                    <ion-icon name="create-outline" class="text-amber-500"></ion-icon>Edit
                </button>
                <button type="button" data-delete="{{ $kegiatan->id }}"
                    class="btn-delete w-full flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                    <ion-icon name="trash-outline" class="text-red-500"></ion-icon>Hapus
                </button>
                <div class="border-t border-gray-100 my-1"></div>
                <a href="{{ route('superadmin.kegiatan.peserta', $kegiatan) }}"
                    class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                    <ion-icon name="people-outline" class="text-blue-500"></ion-icon>Peserta
                    ({{ $kegiatan->peserta_count }})
                </a>
                <a href="{{ route('superadmin.kegiatan.sertifikat', $kegiatan) }}"
                    class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                    <ion-icon name="ribbon-outline" class="text-purple-500"></ion-icon>Sertifikat
                </a>
                <a href="{{ route('superadmin.kegiatan.rundown', $kegiatan) }}"
                    class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                    <ion-icon name="list-outline" class="text-emerald-500"></ion-icon>Rundown
                    ({{ $kegiatan->rundowns_count }})
                </a>
            </div>
        @endforeach
    @else
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 py-12 text-center">
            <ion-icon name="calendar-outline" class="text-5xl text-gray-300 mb-4"></ion-icon>
            <p class="text-gray-500">Belum ada kegiatan</p>
            <button type="button" id="btnTambahEmpty" class="mt-4 text-simawa-500 hover:text-simawa-700 font-medium text-sm">
                + Tambah Kegiatan Baru
            </button>
        </div>
    @endif

    <!-- Modal Form -->
    <div id="modal" class="fixed inset-0 z-[9999]" style="display: none;">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" id="modalBackdrop"></div>
        <div class="fixed inset-0 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl relative max-h-[90vh] overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 id="modalTitle" class="text-lg font-semibold text-gray-800">Tambah Kegiatan</h3>
                    <p class="text-sm text-gray-500 mt-1">Isi form berikut untuk menambahkan kegiatan baru</p>
                </div>
                <form id="kegiatanForm" method="POST" action="{{ route('superadmin.kegiatan.store') }}"
                    class="p-6 space-y-4 overflow-y-auto max-h-[calc(90vh-180px)]">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Kegiatan <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="nama" id="namaKegiatan" required
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-simawa-500/20 focus:border-simawa-500 transition-all text-sm"
                                placeholder="Masukkan nama kegiatan">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Detail Kegiatan</label>
                            <textarea name="detail" id="detailKegiatan" rows="3"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-simawa-500/20 focus:border-simawa-500 transition-all text-sm resize-none"
                                placeholder="Masukkan detail kegiatan"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Waktu Mulai <span
                                    class="text-red-500">*</span></label>
                            <input type="datetime-local" name="waktu_mulai" id="waktuMulai" required
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-simawa-500/20 focus:border-simawa-500 transition-all text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Waktu Selesai <span
                                    class="text-red-500">*</span></label>
                            <input type="datetime-local" name="waktu_selesai" id="waktuSelesai" required
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-simawa-500/20 focus:border-simawa-500 transition-all text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tampilkan?</label>
                            <select name="tampilkan" id="tampilkanKegiatan"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-simawa-500/20 focus:border-simawa-500 transition-all text-sm bg-white">
                                <option value="1">Ya</option>
                                <option value="0">Tidak</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pendaftaran</label>
                            <select name="pendaftaran" id="pendaftaranKegiatan"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-simawa-500/20 focus:border-simawa-500 transition-all text-sm bg-white">
                                <option value="buka">Buka</option>
                                <option value="tutup">Tutup</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex gap-3 pt-4">
                        <button type="button" id="btnBatal"
                            class="flex-1 px-4 py-2.5 border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 transition-all text-sm font-medium">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-1 px-4 py-2.5 bg-gradient-to-r from-simawa-500 to-simawa-700 text-white rounded-lg hover:from-simawa-600 hover:to-simawa-800 transition-all text-sm font-medium">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 z-[9999]" style="display: none;">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" id="deleteModalBackdrop"></div>
        <div class="fixed inset-0 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm relative">
                <div class="p-6 text-center">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <ion-icon name="warning-outline" class="text-3xl text-red-500"></ion-icon>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Hapus Kegiatan?</h3>
                    <p class="text-sm text-gray-500 mb-6">Data yang dihapus tidak dapat dikembalikan. Apakah Anda yakin?</p>
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="flex gap-3">
                            <button type="button" id="btnBatalHapus"
                                class="flex-1 px-4 py-2.5 border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 transition-all text-sm font-medium">
                                Batal
                            </button>
                            <button type="submit"
                                class="flex-1 px-4 py-2.5 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition-all text-sm font-medium">
                                Hapus
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const baseUrl = "{{ route('superadmin.kegiatan.index') }}";
            const storeUrl = "{{ route('superadmin.kegiatan.store') }}";

            const modal = document.getElementById('modal');
            const deleteModal = document.getElementById('deleteModal');
            const modalTitle = document.getElementById('modalTitle');
            const kegiatanForm = document.getElementById('kegiatanForm');
            const formMethod = document.getElementById('formMethod');
            const namaKegiatan = document.getElementById('namaKegiatan');
            const detailKegiatan = document.getElementById('detailKegiatan');
            const waktuMulai = document.getElementById('waktuMulai');
            const waktuSelesai = document.getElementById('waktuSelesai');
            const tampilkanKegiatan = document.getElementById('tampilkanKegiatan');
            const pendaftaranKegiatan = document.getElementById('pendaftaranKegiatan');
            const deleteForm = document.getElementById('deleteForm');

            function showModal() {
                modal.style.display = 'block';
            }

            function hideModal() {
                modal.style.display = 'none';
            }

            function showDeleteModal() {
                deleteModal.style.display = 'block';
            }

            function hideDeleteModal() {
                deleteModal.style.display = 'none';
            }

            // Tambah button
            const btnTambah = document.getElementById('btnTambah');
            if (btnTambah) {
                btnTambah.addEventListener('click', function () {
                    modalTitle.textContent = 'Tambah Kegiatan';
                    kegiatanForm.action = storeUrl;
                    formMethod.value = 'POST';
                    namaKegiatan.value = '';
                    detailKegiatan.value = '';
                    waktuMulai.value = '';
                    waktuSelesai.value = '';
                    tampilkanKegiatan.value = '1';
                    pendaftaranKegiatan.value = 'buka';
                    showModal();
                });
            }

            const btnTambahEmpty = document.getElementById('btnTambahEmpty');
            if (btnTambahEmpty) {
                btnTambahEmpty.addEventListener('click', function () {
                    modalTitle.textContent = 'Tambah Kegiatan';
                    kegiatanForm.action = storeUrl;
                    formMethod.value = 'POST';
                    namaKegiatan.value = '';
                    detailKegiatan.value = '';
                    waktuMulai.value = '';
                    waktuSelesai.value = '';
                    tampilkanKegiatan.value = '1';
                    pendaftaranKegiatan.value = 'buka';
                    showModal();
                });
            }

            // Batal buttons
            document.getElementById('btnBatal').addEventListener('click', hideModal);
            document.getElementById('modalBackdrop').addEventListener('click', hideModal);
            document.getElementById('btnBatalHapus').addEventListener('click', hideDeleteModal);
            document.getElementById('deleteModalBackdrop').addEventListener('click', hideDeleteModal);

            // Edit buttons
            document.querySelectorAll('.btn-edit').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    closeAllDropdowns();
                    const id = this.dataset.edit;
                    const nama = this.dataset.nama;
                    const detail = this.dataset.detail || '';
                    const mulai = this.dataset.mulai;
                    const selesai = this.dataset.selesai;
                    const tampilkan = this.dataset.tampilkan;
                    const pendaftaran = this.dataset.pendaftaran;

                    modalTitle.textContent = 'Edit Kegiatan';
                    kegiatanForm.action = baseUrl + '/' + id;
                    formMethod.value = 'PUT';
                    namaKegiatan.value = nama;
                    detailKegiatan.value = detail;
                    waktuMulai.value = mulai;
                    waktuSelesai.value = selesai;
                    tampilkanKegiatan.value = tampilkan;
                    pendaftaranKegiatan.value = pendaftaran;
                    showModal();
                });
            });

            // Delete buttons
            document.querySelectorAll('.btn-delete').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    closeAllDropdowns();
                    const id = this.dataset.delete;
                    deleteForm.action = baseUrl + '/' + id;
                    showDeleteModal();
                });
            });

            // ESC key
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    hideModal();
                    hideDeleteModal();
                    closeAllDropdowns();
                }
            });

            // Dropdown toggle with fixed positioning (supports both mobile and desktop)
            window.toggleDropdown = function (eventOrId, id) {
                let dropdownId, button;
                
                // Check if first argument is an event or just the ID (mobile)
                if (typeof eventOrId === 'string') {
                    // Mobile: called as toggleDropdown('id')
                    dropdownId = eventOrId;
                    button = null;
                } else {
                    // Desktop: called as toggleDropdown(event, 'id')
                    eventOrId.stopPropagation();
                    dropdownId = id;
                    button = eventOrId.currentTarget;
                }
                
                closeAllDropdowns();
                
                const dropdown = document.getElementById('dropdown-' + dropdownId);
                
                if (dropdown) {
                    // If button exists (desktop), use fixed positioning
                    if (button) {
                        const rect = button.getBoundingClientRect();
                        
                        // Position dropdown below button, aligned to right
                        dropdown.style.top = (rect.bottom + 4) + 'px';
                        dropdown.style.left = (rect.right - 192) + 'px'; // 192 = w-48 (12rem)
                        
                        // Check if dropdown would go off-screen bottom
                        const dropdownHeight = 280; // approximate height
                        if (rect.bottom + dropdownHeight > window.innerHeight) {
                            dropdown.style.top = (rect.top - dropdownHeight - 4) + 'px';
                        }
                        
                        // Check if dropdown would go off-screen left
                        if (rect.right - 192 < 0) {
                            dropdown.style.left = rect.left + 'px';
                        }
                    }
                    
                    dropdown.classList.toggle('hidden');
                }
            }

            window.closeAllDropdowns = function () {
                document.querySelectorAll('.dropdown-menu').forEach(d => d.classList.add('hidden'));
            }

            // Close dropdowns on outside click
            document.addEventListener('click', function (e) {
                if (!e.target.closest('.relative')) {
                    closeAllDropdowns();
                }
            });

            // Initialize DataTables for desktop
            var table = $('#kegiatanTable').DataTable({
                responsive: false,
                ordering: true,
                order: [[0, 'asc']],
                pageLength: 10,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ kegiatan",
                    infoEmpty: "Tidak ada data",
                    infoFiltered: "(disaring dari _MAX_ total data)",
                    zeroRecords: "Tidak ada kegiatan yang ditemukan",
                    paginate: {
                        first: "Awal",
                        last: "Akhir",
                        next: "Berikutnya",
                        previous: "Sebelumnya"
                    }
                },
                columnDefs: [
                    { orderable: false, targets: [7] },
                    { searchable: false, targets: [0, 7] }
                ]
            });

            // Make search & filter sit inline
            $('#kegiatanTable_filter label').css({
                'display': 'flex',
                'align-items': 'center',
                'gap': '0.5rem'
            });
            $('#kegiatanTable_filter').css({
                'display': 'flex',
                'align-items': 'center',
                'gap': '0.75rem',
                'flex-wrap': 'nowrap'
            });

            // Add filter dropdowns next to DataTables search
            var filterHtml = '<select id="filterTampilkanDT" style="padding:0.5rem 0.75rem;border:1px solid #E5E7EB;border-radius:0.5rem;font-size:0.875rem;outline:none;white-space:nowrap;">' +
                '<option value="">Semua Tampil</option>' +
                '<option value="Ya">Ya</option>' +
                '<option value="Tidak">Tidak</option>' +
                '</select>' +
                '<select id="filterPendaftaranDT" style="padding:0.5rem 0.75rem;border:1px solid #E5E7EB;border-radius:0.5rem;font-size:0.875rem;outline:none;white-space:nowrap;">' +
                '<option value="">Semua Pendaftaran</option>' +
                '<option value="Buka">Buka</option>' +
                '<option value="Tutup">Tutup</option>' +
                '</select>';
            $('#kegiatanTable_filter').append(filterHtml);

            // Custom search functions for filters
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                if (settings.nTable.id !== 'kegiatanTable') return true;
                var selectedTampil = $('#filterTampilkanDT').val();
                var selectedPendaftaran = $('#filterPendaftaranDT').val();

                var tampilkan = (data[5] || '').trim();
                var pendaftaran = (data[6] || '').trim();

                var matchTampil = !selectedTampil || tampilkan === selectedTampil;
                var matchPendaftaran = !selectedPendaftaran || pendaftaran === selectedPendaftaran;

                return matchTampil && matchPendaftaran;
            });

            $('#filterTampilkanDT, #filterPendaftaranDT').on('change', function() {
                table.draw();
            });

            // Mobile card search & filter
            document.getElementById('searchKegiatan').addEventListener('input', filterMobileCards);
            document.getElementById('filterTampilkan').addEventListener('change', filterMobileCards);
            document.getElementById('filterPendaftaran').addEventListener('change', filterMobileCards);

            function filterMobileCards() {
                const search = document.getElementById('searchKegiatan').value.toLowerCase();
                const tampilkan = document.getElementById('filterTampilkan').value;
                const pendaftaran = document.getElementById('filterPendaftaran').value;

                document.querySelectorAll('.kegiatan-row').forEach(row => {
                    const nama = row.dataset.nama;
                    const detail = row.dataset.detail;
                    const rowTampilkan = row.dataset.tampilkan;
                    const rowPendaftaran = row.dataset.pendaftaran;

                    const matchSearch = nama.includes(search) || detail.includes(search);
                    const matchTampil = !tampilkan || rowTampilkan === tampilkan;
                    const matchPendaftaran = !pendaftaran || rowPendaftaran === pendaftaran;

                    row.style.display = matchSearch && matchTampil && matchPendaftaran ? '' : 'none';
                });
            }
        });
    </script>
@endpush