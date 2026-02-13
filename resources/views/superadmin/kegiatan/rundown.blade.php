@extends('layouts.dashboard')

@section('title', 'Rundown Kegiatan')
@section('page-title', 'Rundown Kegiatan')
@section('page-description', 'Kelola rundown kegiatan')

@section('content')
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <a href="{{ route('superadmin.kegiatan.index') }}"
                    class="text-gray-400 hover:text-simawa-600 transition-colors">
                    <ion-icon name="arrow-back-outline" class="text-xl"></ion-icon>
                </a>
                <h3 class="text-lg font-semibold text-gray-800">Rundown Kegiatan</h3>
            </div>
            <p class="text-sm text-gray-500 mt-1">Kelola rundown kegiatan</p>
        </div>
        <button type="button" id="btnTambahRundown"
            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-simawa-500 to-simawa-700 text-white rounded-lg hover:from-simawa-600 hover:to-simawa-800 transition-all shadow-lg shadow-simawa-500/25 text-sm font-medium">
            <ion-icon name="add-circle-outline" class="text-lg"></ion-icon>
            Tambah Rundown
        </button>
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
                    <span class="flex items-center gap-1">
                        <ion-icon name="albums-outline"></ion-icon>
                        {{ $kegiatan->rundowns->count() }} Rundown
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

    @if($kegiatan->rundowns->count() > 0)
        <!-- Mobile Card View -->
        <div class="lg:hidden space-y-3">
            @foreach($kegiatan->rundowns as $index => $rundown)
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-8 h-8 rounded-lg bg-simawa-100 text-simawa-600 flex items-center justify-center text-sm font-bold">
                                {{ $index + 1 }}
                            </div>
                            <span class="text-sm font-semibold text-gray-800">{{ $rundown->nama }}</span>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2 text-xs mb-3">
                        <div>
                            <span class="text-gray-500">Mulai:</span>
                            <p class="font-medium text-gray-700">{{ $rundown->waktu_mulai->format('d M Y H:i') }}</p>
                        </div>
                        <div>
                            <span class="text-gray-500">Selesai:</span>
                            <p class="font-medium text-gray-700">{{ $rundown->waktu_selesai->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-gray-100 flex gap-2">
                        <button type="button" data-edit="{{ $rundown->id }}" data-nama="{{ $rundown->nama }}"
                            data-mulai="{{ $rundown->waktu_mulai->format('Y-m-d\TH:i') }}"
                            data-selesai="{{ $rundown->waktu_selesai->format('Y-m-d\TH:i') }}"
                            class="btn-edit flex-1 inline-flex items-center justify-center gap-2 px-3 py-2 bg-simawa-600 hover:bg-simawa-700 text-white rounded-lg transition-colors text-sm font-medium">
                            <ion-icon name="create-outline"></ion-icon>
                            Edit
                        </button>
                        <button type="button" data-delete="{{ $rundown->id }}"
                            class="btn-delete flex-1 inline-flex items-center justify-center gap-2 px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors text-sm font-medium">
                            <ion-icon name="trash-outline"></ion-icon>
                            Hapus
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Desktop Table View -->
        <div class="hidden lg:block bg-white rounded-md shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-simawa-600">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider w-16">No
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Nama
                                Rundown</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Waktu
                                Mulai</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Waktu
                                Selesai</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-white uppercase tracking-wider w-32">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($kegiatan->rundowns as $index => $rundown)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 text-sm text-gray-600 font-medium">{{ $index + 1 }}</td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-medium text-gray-800">{{ $rundown->nama }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">
                                    {{ $rundown->waktu_mulai->format('d M Y H:i') }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">
                                    {{ $rundown->waktu_selesai->format('d M Y H:i') }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <button type="button" data-edit="{{ $rundown->id }}" data-nama="{{ $rundown->nama }}"
                                            data-mulai="{{ $rundown->waktu_mulai->format('Y-m-d\TH:i') }}"
                                            data-selesai="{{ $rundown->waktu_selesai->format('Y-m-d\TH:i') }}"
                                            class="btn-edit flex h-9 w-9 items-center justify-center text-white bg-simawa-600 hover:bg-simawa-700 rounded-lg transition-colors"
                                            title="Edit">
                                            <ion-icon name="create-outline" class="text-lg"></ion-icon>
                                        </button>
                                        <button type="button" data-delete="{{ $rundown->id }}"
                                            class="btn-delete flex h-9 w-9 items-center justify-center text-white bg-red-500 hover:bg-red-600 rounded-lg transition-colors"
                                            title="Hapus">
                                            <ion-icon name="trash-outline" class="text-lg"></ion-icon>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 py-12 text-center">
            <ion-icon name="list-outline" class="text-5xl text-gray-300 mb-4"></ion-icon>
            <p class="text-gray-500">Belum ada rundown</p>
            <button type="button" id="btnTambahEmpty" class="mt-4 text-simawa-500 hover:text-simawa-700 font-medium text-sm">
                + Tambah Rundown Baru
            </button>
        </div>
    @endif

    <!-- Modal Form -->
    <div id="modal" class="fixed inset-0 z-[9999]" style="display: none;">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" id="modalBackdrop"></div>
        <div class="fixed inset-0 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md relative">
                <div class="p-6 border-b border-gray-100">
                    <h3 id="modalTitle" class="text-lg font-semibold text-gray-800">Tambah Rundown</h3>
                    <p class="text-sm text-gray-500 mt-1">Isi form berikut untuk menambahkan rundown</p>
                </div>
                <form id="rundownForm" method="POST" action="{{ route('superadmin.kegiatan.rundown.store', $kegiatan) }}"
                    class="p-6 space-y-4">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">

                    @if($errors->any())
                        <div class="p-3 bg-red-50 border border-red-200 rounded-lg">
                            @foreach($errors->all() as $error)
                                <p class="text-sm text-red-600 flex items-center gap-1">
                                    <ion-icon name="alert-circle-outline"></ion-icon>
                                    {{ $error }}
                                </p>
                            @endforeach
                        </div>
                    @endif

                    <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-xs text-blue-700 flex items-center gap-1">
                            <ion-icon name="information-circle-outline" class="text-sm flex-shrink-0"></ion-icon>
                            Waktu rundown harus dalam rentang kegiatan: <strong class="ml-1">{{ $kegiatan->waktu_mulai->format('d M Y H:i') }}</strong> &mdash; <strong>{{ $kegiatan->waktu_selesai->format('d M Y H:i') }}</strong>
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Rundown</label>
                        <input type="text" name="nama" id="namaRundown" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-simawa-500/20 focus:border-simawa-500 transition-all text-sm"
                            placeholder="Contoh: Pembukaan, Sesi 1, Penutupan">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Waktu Mulai</label>
                            <input type="datetime-local" name="waktu_mulai" id="waktuMulai" required
                                min="{{ $kegiatan->waktu_mulai->format('Y-m-d\TH:i') }}"
                                max="{{ $kegiatan->waktu_selesai->format('Y-m-d\TH:i') }}"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-simawa-500/20 focus:border-simawa-500 transition-all text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Waktu Selesai</label>
                            <input type="datetime-local" name="waktu_selesai" id="waktuSelesai" required
                                min="{{ $kegiatan->waktu_mulai->format('Y-m-d\TH:i') }}"
                                max="{{ $kegiatan->waktu_selesai->format('Y-m-d\TH:i') }}"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-simawa-500/20 focus:border-simawa-500 transition-all text-sm">
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
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Hapus Rundown?</h3>
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
            const baseUrl = "{{ route('superadmin.kegiatan.rundown', $kegiatan) }}";
            const storeUrl = "{{ route('superadmin.kegiatan.rundown.store', $kegiatan) }}";

            const modal = document.getElementById('modal');
            const deleteModal = document.getElementById('deleteModal');
            const modalTitle = document.getElementById('modalTitle');
            const rundownForm = document.getElementById('rundownForm');
            const formMethod = document.getElementById('formMethod');
            const namaRundown = document.getElementById('namaRundown');
            const waktuMulai = document.getElementById('waktuMulai');
            const waktuSelesai = document.getElementById('waktuSelesai');
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
            const btnTambah = document.getElementById('btnTambahRundown');
            if (btnTambah) {
                btnTambah.addEventListener('click', function () {
                    modalTitle.textContent = 'Tambah Rundown';
                    rundownForm.action = storeUrl;
                    formMethod.value = 'POST';
                    namaRundown.value = '';
                    waktuMulai.value = '';
                    waktuSelesai.value = '';
                    showModal();
                });
            }

            const btnTambahEmpty = document.getElementById('btnTambahEmpty');
            if (btnTambahEmpty) {
                btnTambahEmpty.addEventListener('click', function () {
                    modalTitle.textContent = 'Tambah Rundown';
                    rundownForm.action = storeUrl;
                    formMethod.value = 'POST';
                    namaRundown.value = '';
                    waktuMulai.value = '';
                    waktuSelesai.value = '';
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
                    const id = this.dataset.edit;
                    const nama = this.dataset.nama;
                    const mulai = this.dataset.mulai;
                    const selesai = this.dataset.selesai;

                    modalTitle.textContent = 'Edit Rundown';
                    rundownForm.action = '{{ url("superadmin/kegiatan/rundown") }}/' + id;
                    formMethod.value = 'PUT';
                    namaRundown.value = nama;
                    waktuMulai.value = mulai;
                    waktuSelesai.value = selesai;
                    showModal();
                });
            });

            // Delete buttons
            document.querySelectorAll('.btn-delete').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const id = this.dataset.delete;
                    deleteForm.action = '{{ url("superadmin/kegiatan/rundown") }}/' + id;
                    showDeleteModal();
                });
            });

            // Auto-open modal if validation errors
            @if($errors->any())
                showModal();
            @endif

            // ESC key
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    hideModal();
                    hideDeleteModal();
                }
            });
        });
    </script>
@endpush