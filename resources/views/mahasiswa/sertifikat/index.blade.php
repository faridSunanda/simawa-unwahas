@extends('layouts.dashboard')

@section('title', 'Sertifikat Saya')
@section('page-title', 'Sertifikat Saya')
@section('page-description', 'Kelola pengajuan sertifikat Anda')

@section('content')
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h3 class="text-lg font-semibold text-gray-800">Riwayat Pengajuan Sertifikat</h3>
            <p class="text-sm text-gray-500 mt-1">Daftar sertifikat yang sudah diajukan</p>
        </div>
        <a href="{{ route('mahasiswa.sertifikat.create') }}"
            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-simawa-500 to-simawa-700 text-white rounded-lg hover:from-simawa-600 hover:to-simawa-800 transition-all shadow-lg shadow-simawa-500/25 text-sm font-medium">
            <ion-icon name="add-circle-outline" class="text-lg"></ion-icon>
            Ajukan Sertifikat
        </a>
    </div>

    <!-- Success/Error Alert -->
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center gap-3">
            <ion-icon name="checkmark-circle" class="text-xl flex-shrink-0"></ion-icon>
            <span class="text-sm">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg flex items-center gap-3">
            <ion-icon name="alert-circle" class="text-xl flex-shrink-0"></ion-icon>
            <span class="text-sm">{{ session('error') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
        <div class="overflow-x-auto">
            <table id="pengajuan-table" class="w-full">
                <thead>
                    <tr>
                        <th class="text-left whitespace-nowrap">No</th>
                        <th class="text-left whitespace-nowrap">Nama Sertifikat</th>
                        <th class="text-left whitespace-nowrap">Jenis</th>
                        <th class="text-left whitespace-nowrap">Penerbit</th>
                        <th class="text-left whitespace-nowrap">Tanggal Terbit</th>
                        <th class="text-left whitespace-nowrap">Status</th>
                        <th class="text-center whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <!-- Detail Modal -->
    <div id="modal-detail" tabindex="-1" aria-hidden="true" class="fixed inset-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto h-[calc(100%-1rem)] max-h-full">
        <div class="relative w-full max-w-2xl max-h-full mx-auto">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow-2xl">
                 <!-- Modal header -->
                <div class="flex items-start justify-between p-4 border-b rounded-t">
                    <h3 class="text-xl font-semibold text-gray-900">
                        Detail Pengajuan
                    </h3>
                    <button type="button" onclick="closeModal('modal-detail')" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center">
                        <ion-icon name="close-outline" class="text-xl"></ion-icon>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-6 space-y-6" id="modal-detail-content">
                    <div class="flex justify-center py-4">
                        <svg class="animate-spin h-8 w-8 text-simawa-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        <div class="fixed inset-0 bg-black/50 -z-10" onclick="closeModal('modal-detail')"></div>
    </div>

    <!-- Edit Modal -->
    <div id="modal-edit" tabindex="-1" aria-hidden="true" class="fixed inset-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto h-[calc(100%-1rem)] max-h-full">
        <div class="relative w-full max-w-2xl max-h-full mx-auto">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow-2xl">
                 <!-- Modal header -->
                <div class="flex items-start justify-between p-4 border-b rounded-t">
                    <h3 class="text-xl font-semibold text-gray-900">
                        Edit Pengajuan
                    </h3>
                    <button type="button" onclick="closeModal('modal-edit')" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center">
                        <ion-icon name="close-outline" class="text-xl"></ion-icon>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-6 space-y-6" id="modal-edit-content">
                    <div class="flex justify-center py-4">
                        <svg class="animate-spin h-8 w-8 text-simawa-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        <div class="fixed inset-0 bg-black/50 -z-10" onclick="closeModal('modal-edit')"></div>
    </div>

    @push('scripts')
    <script>
        $(function() {
            $('#pengajuan-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('mahasiswa.sertifikat.index') }}',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'nama_sertifikat', name: 'nama_sertifikat' },
                    { data: 'jenis_sertifikat', name: 'jenisSertifikat.nama', orderable: false },
                    { data: 'penerbit', name: 'penerbit' },
                    { data: 'tanggal_terbit', name: 'tanggal_terbit' },
                    { data: 'status', name: 'status', orderable: false, searchable: false },
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' },
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json",
                    emptyTable: "Belum ada pengajuan sertifikat",
                    zeroRecords: "Tidak ditemukan data yang sesuai"
                }
            });
        });

        function showDetail(url) {
            const modal = document.getElementById('modal-detail');
            const content = document.getElementById('modal-detail-content');
            
            modal.classList.remove('hidden');
            // Show loading
            content.innerHTML = `
                <div class="flex justify-center py-12">
                    <svg class="animate-spin h-10 w-10 text-simawa-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            `;

            $.get(url, function(data) {
                content.innerHTML = data;
            }).fail(function() {
                content.innerHTML = '<div class="text-center text-red-500 p-4">Gagal memuat data. Silakan coba lagi.</div>';
            });
        }

        function showEdit(url) {
            const modal = document.getElementById('modal-edit');
            const content = document.getElementById('modal-edit-content');
            
            modal.classList.remove('hidden');
            // Show loading
            content.innerHTML = `
                <div class="flex justify-center py-12">
                    <svg class="animate-spin h-10 w-10 text-simawa-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            `;

            $.get(url, function(data) {
                content.innerHTML = data;
            }).fail(function() {
                content.innerHTML = '<div class="text-center text-red-500 p-4">Gagal memuat data. Silakan coba lagi.</div>';
            });
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        // Close modal on escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal('modal-detail');
                closeModal('modal-edit');
            }
        });

        // Handle Edit Form Submission
        $(document).on('submit', '#modal-edit-content form', function(e) {
            e.preventDefault();
            const form = $(this);
            const submitBtn = form.find('button[type="submit"]');
            const originalBtnText = submitBtn.html();
            
            // Disable button and show loading
            submitBtn.prop('disabled', true).html('<span class="inline-flex items-center gap-2"><svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Menyimpan...</span>');

            // Clear previous errors
            form.find('.text-red-500').remove();

            const formData = new FormData(this);

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    // Close modal
                    closeModal('modal-edit');
                    
                    // Reload table
                    $('#pengajuan-table').DataTable().ajax.reload();
                    
                    // Show success message (using sweetalert or simple toast if available, otherwise built-in alert)
                    // Assuming no toast library, let's inject a success alert at top of page or just simple alert
                   
                    // Inject alert into page
                     const alertHtml = `
                        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center gap-3 fade-in">
                            <ion-icon name="checkmark-circle" class="text-xl flex-shrink-0"></ion-icon>
                            <span class="text-sm">Pengajuan berhasil diperbarui</span>
                        </div>
                    `;
                    $('.bg-white.rounded-lg.shadow-sm.border.border-gray-100.p-6').before(alertHtml);
                    
                    // Remove alert after 3 seconds
                    setTimeout(function() {
                        $('.fade-in').fadeOut(function() { $(this).remove(); });
                    }, 3000);
                },
                error: function(xhr) {
                    submitBtn.prop('disabled', false).html(originalBtnText);
                    
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        $.each(errors, function(field, messages) {
                            const input = form.find(`[name="${field}"]`);
                            input.after(`<p class="text-red-500 text-xs mt-1">${messages[0]}</p>`);
                        });
                    } else {
                        alert('Terjadi kesalahan. Silakan coba lagi.');
                    }
                }
            });
        });
    </script>
    @endpush
@endsection