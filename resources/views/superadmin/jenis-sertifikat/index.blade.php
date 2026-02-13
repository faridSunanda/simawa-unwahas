@extends('layouts.dashboard')

@section('title', 'Jenis Sertifikat')
@section('page-title', 'Jenis Sertifikat')
@section('page-description', 'Kelola jenis sertifikat mahasiswa')

@section('content')
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h3 class="text-lg font-semibold text-gray-800">Daftar Jenis Sertifikat</h3>
            <p class="text-sm text-gray-500 mt-1">Master data jenis sertifikat untuk pengajuan mahasiswa</p>
        </div>
        <button type="button" id="btnTambah"
            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-simawa-500 to-simawa-700 text-white rounded-lg hover:from-simawa-600 hover:to-simawa-800 transition-all shadow-lg shadow-simawa-500/25 text-sm font-medium">
            <ion-icon name="add-circle-outline" class="text-lg"></ion-icon>
            Tambah Jenis
        </button>
    </div>

    <!-- Success/Error Alert -->
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center gap-3">
            <ion-icon name="checkmark-circle" class="text-xl flex-shrink-0"></ion-icon>
            <span class="text-sm">{{ session('success') }}</span>
        </div>
    @endif

    @if($jenisSertifikats->count() > 0)
        <!-- Mobile Card View -->
        <div class="lg:hidden space-y-3">
            @foreach($jenisSertifikats as $index => $jenis)
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <div class="min-w-0 flex-1">
                            <span
                                class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-simawa-500 text-white text-xs font-medium mr-2">{{ $index + 1 }}</span>
                            <span class="text-sm font-semibold text-gray-800">{{ $jenis->nama }}</span>
                        </div>
                    </div>
                    @if($jenis->keterangan)
                        <p class="text-sm text-gray-600 mt-2 line-clamp-2">{{ $jenis->keterangan }}</p>
                    @endif
                    <div class="mt-4 pt-3 border-t border-gray-100 flex gap-2">
                        <button type="button" data-edit="{{ $jenis->id }}" data-nama="{{ $jenis->nama }}"
                            data-keterangan="{{ $jenis->keterangan }}"
                            class="btn-edit flex-1 inline-flex items-center justify-center gap-2 px-3 py-2 bg-simawa-600 hover:bg-simawa-700 text-white rounded-lg transition-colors text-sm font-medium">
                            <ion-icon name="create-outline"></ion-icon>
                            Edit
                        </button>
                        <button type="button" data-delete="{{ $jenis->id }}"
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
                <table id="dataTable" class="w-full">
                    <thead class="bg-simawa-600">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider w-16">No
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Nama Jenis
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Keterangan
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-white uppercase tracking-wider w-32">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($jenisSertifikats as $index => $jenis)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 text-sm text-gray-600 font-medium">{{ $index + 1 }}</td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-medium text-gray-800">{{ $jenis->nama }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 max-w-md">
                                    <p class="line-clamp-2">{{ $jenis->keterangan ?? '-' }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <button type="button" data-edit="{{ $jenis->id }}" data-nama="{{ $jenis->nama }}"
                                            data-keterangan="{{ $jenis->keterangan }}"
                                            class="btn-edit flex h-9 w-9 items-center justify-center text-white bg-simawa-600 hover:bg-simawa-700 rounded-lg transition-colors"
                                            title="Edit">
                                            <ion-icon name="create-outline" class="text-lg"></ion-icon>
                                        </button>
                                        <button type="button" data-delete="{{ $jenis->id }}"
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
            <p class="text-gray-500">Belum ada data jenis sertifikat</p>
            <button type="button" id="btnTambahEmpty" class="mt-4 text-simawa-500 hover:text-simawa-700 font-medium text-sm">
                + Tambah Jenis Baru
            </button>
        </div>
    @endif

    <!-- Modal Form -->
    <div id="modal" class="fixed inset-0 z-[9999]" style="display: none;">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" id="modalBackdrop"></div>
        <div class="fixed inset-0 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md relative">
                <div class="p-6 border-b border-gray-100">
                    <h3 id="modalTitle" class="text-lg font-semibold text-gray-800">Tambah Jenis Sertifikat</h3>
                    <p class="text-sm text-gray-500 mt-1">Isi form berikut untuk menambahkan jenis sertifikat baru</p>
                </div>
                <form id="jenisForm" method="POST" action="{{ route('superadmin.jenis-sertifikat.store') }}"
                    class="p-6 space-y-4">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Jenis</label>
                        <input type="text" name="nama" id="namaJenis" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-simawa-500/20 focus:border-simawa-500 transition-all text-sm"
                            placeholder="Contoh: TOEFL, IELTS, Kominfo">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" rows="3"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-simawa-500/20 focus:border-simawa-500 transition-all text-sm resize-none"
                            placeholder="Masukkan keterangan jenis sertifikat"></textarea>
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
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Hapus Jenis Sertifikat?</h3>
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
            const baseUrl = "{{ route('superadmin.jenis-sertifikat.index') }}";
            const storeUrl = "{{ route('superadmin.jenis-sertifikat.store') }}";

            const modal = document.getElementById('modal');
            const deleteModal = document.getElementById('deleteModal');
            const modalTitle = document.getElementById('modalTitle');
            const jenisForm = document.getElementById('jenisForm');
            const formMethod = document.getElementById('formMethod');
            const namaJenis = document.getElementById('namaJenis');
            const keteranganInput = document.getElementById('keterangan');
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
                    modalTitle.textContent = 'Tambah Jenis Sertifikat';
                    jenisForm.action = storeUrl;
                    formMethod.value = 'POST';
                    namaJenis.value = '';
                    keteranganInput.value = '';
                    showModal();
                });
            }

            const btnTambahEmpty = document.getElementById('btnTambahEmpty');
            if (btnTambahEmpty) {
                btnTambahEmpty.addEventListener('click', function () {
                    modalTitle.textContent = 'Tambah Jenis Sertifikat';
                    jenisForm.action = storeUrl;
                    formMethod.value = 'POST';
                    namaJenis.value = '';
                    keteranganInput.value = '';
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
                    const ket = this.dataset.keterangan || '';

                    modalTitle.textContent = 'Edit Jenis Sertifikat';
                    jenisForm.action = baseUrl + '/' + id;
                    formMethod.value = 'PUT';
                    namaJenis.value = nama;
                    keteranganInput.value = ket;
                    showModal();
                });
            });

            // Delete buttons
            document.querySelectorAll('.btn-delete').forEach(function (btn) {
                btn.addEventListener('click', function () {
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
                }
            });

            // DataTable
            if (typeof $ !== 'undefined' && $('#dataTable tbody tr').length > 0) {
                $('#dataTable').DataTable({
                    responsive: true,
                    pageLength: 10,
                    order: [[1, 'asc']],
                    columnDefs: [
                        { orderable: false, targets: [0, 3] },
                        { searchable: false, targets: [0, 3] }
                    ],
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
                    }
                });
            }
        });
    </script>
@endpush