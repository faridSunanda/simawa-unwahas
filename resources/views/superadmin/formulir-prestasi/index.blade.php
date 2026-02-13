@extends('layouts.dashboard')

@section('title', 'Formulir Prestasi')
@section('page-title', 'Formulir Prestasi')
@section('page-description', 'Kelola formulir untuk pengajuan prestasi mahasiswa')

@section('content')
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h3 class="text-lg font-semibold text-gray-800">Daftar Formulir Prestasi</h3>
            <p class="text-sm text-gray-500 mt-1">Kelola formulir untuk pengajuan prestasi mahasiswa</p>
        </div>
        <button onclick="openFormModal()"
            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-simawa-500 to-simawa-700 text-white rounded-lg hover:from-simawa-600 hover:to-simawa-800 transition-all shadow-lg shadow-simawa-500/25 text-sm font-medium">
            <ion-icon name="add-circle-outline" class="text-lg"></ion-icon>
            Buat Formulir Baru
        </button>
    </div>

    <!-- Success/Error Alert -->
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center gap-3">
            <ion-icon name="checkmark-circle" class="text-xl flex-shrink-0"></ion-icon>
            <span class="text-sm">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Forms List -->
    @if($formulirs->count() > 0)
        <div id="formsList" class="grid gap-3 sm:gap-4">
            @foreach($formulirs as $formulir)
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 sm:p-5 hover:shadow-md transition-all">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-3 mb-2">
                                <h4 class="font-semibold text-gray-800 truncate">{{ $formulir->judul }}</h4>
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700 w-fit">
                                    {{ $formulir->kategoriPrestasi->nama }}
                                </span>
                            </div>
                            <div class="flex flex-wrap items-center gap-3 sm:gap-4 text-sm text-gray-500 mt-3">
                                <span class="flex items-center gap-1">
                                    <ion-icon name="help-circle-outline"></ion-icon>
                                    {{ $formulir->pertanyaans->count() }} Pertanyaan
                                </span>
                                <span class="flex items-center gap-1">
                                    <ion-icon name="calendar-outline"></ion-icon>
                                    {{ $formulir->created_at->format('Y-m-d') }}
                                </span>
                            </div>
                        </div>
                        <!-- Desktop Actions -->
                        <div class="hidden sm:flex items-center gap-2 flex-shrink-0">
                            <button onclick="viewFormPreview('{{ $formulir->id }}')"
                                class="flex h-9 w-9 items-center justify-center text-white bg-green-600 hover:bg-green-700 rounded-lg transition-colors"
                                title="Preview">
                                <ion-icon name="eye-outline" class="text-lg"></ion-icon>
                            </button>
                            <button onclick="editForm('{{ $formulir->id }}')"
                                class="flex h-9 w-9 items-center justify-center text-white bg-simawa-600 hover:bg-simawa-700 rounded-lg transition-colors"
                                title="Edit">
                                <ion-icon name="create-outline" class="text-lg"></ion-icon>
                            </button>
                            <button onclick="deleteForm('{{ $formulir->id }}')"
                                class="flex h-9 w-9 items-center justify-center text-white bg-red-500 hover:bg-red-600 rounded-lg transition-colors"
                                title="Hapus">
                                <ion-icon name="trash-outline" class="text-lg"></ion-icon>
                            </button>
                        </div>
                    </div>
                    <!-- Mobile Actions -->
                    <div class="sm:hidden mt-4 pt-3 border-t border-gray-100 flex gap-2">
                        <button onclick="viewFormPreview('{{ $formulir->id }}')"
                            class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors text-sm font-medium">
                            <ion-icon name="eye-outline"></ion-icon>
                            Preview
                        </button>
                        <button onclick="editForm('{{ $formulir->id }}')"
                            class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-simawa-600 hover:bg-simawa-700 text-white rounded-lg transition-colors text-sm font-medium">
                            <ion-icon name="create-outline"></ion-icon>
                            Edit
                        </button>
                        <button onclick="deleteForm('{{ $formulir->id }}')"
                            class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors text-sm font-medium">
                            <ion-icon name="trash-outline"></ion-icon>
                            Hapus
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 py-12 text-center">
            <ion-icon name="sad-outline" class="text-5xl text-gray-300 mb-3"></ion-icon>
            <p class="text-gray-500 mb-2">Belum ada formulir prestasi</p>
            <button onclick="openFormModal()" class="text-simawa-500 hover:text-simawa-700 font-medium text-sm">
                + Buat Formulir Baru
            </button>
        </div>
    @endif

    <!-- Create/Edit Form Modal -->
    <div id="formModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeFormModal()"></div>
        <div class="flex items-start justify-center min-h-screen p-4 pt-6 sm:pt-10">
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-3xl transform transition-all">
                <!-- Modal Header -->
                <div class="p-4 sm:p-6 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 id="formModalTitle" class="text-lg font-semibold text-gray-800">Buat Formulir Baru</h3>
                        <p class="text-sm text-gray-500 mt-1 hidden sm:block">Tambahkan pertanyaan dengan berbagai tipe
                            jawaban</p>
                    </div>
                    <button onclick="closeFormModal()" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                        <ion-icon name="close-outline" class="text-xl text-gray-500"></ion-icon>
                    </button>
                </div>

                <!-- Modal Body -->
                <form id="formulirForm" method="POST" action="{{ route('superadmin.formulir-prestasi.store') }}">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">

                    <div class="p-4 sm:p-6 space-y-4 sm:space-y-6 max-h-[60vh] overflow-y-auto">
                        <!-- Form Title & Category -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Formulir</label>
                                <input type="text" name="judul" id="formTitle" required
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-simawa-500/20 focus:border-simawa-500 transition-all text-sm"
                                    placeholder="Contoh: Formulir Prestasi Akademik">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Kategori Prestasi</label>
                                <select name="kategori_prestasi_id" id="formCategory"
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-simawa-500/20 focus:border-simawa-500 transition-all text-sm">
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Questions Section -->
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <label class="block text-sm font-medium text-gray-700">Daftar Pertanyaan</label>
                                <button type="button" onclick="addQuestion()"
                                    class="inline-flex items-center gap-1.5 text-sm text-simawa-500 hover:text-simawa-700 font-medium">
                                    <ion-icon name="add-circle-outline"></ion-icon>
                                    Tambah
                                </button>
                            </div>

                            <div id="questionsContainer" class="space-y-4">
                                <!-- Questions will be rendered here -->
                            </div>

                            <div id="noQuestions"
                                class="py-8 text-center border-2 border-dashed border-gray-200 rounded-xl">
                                <ion-icon name="help-circle-outline" class="text-4xl text-gray-300 mb-2"></ion-icon>
                                <p class="text-gray-400 text-sm">Belum ada pertanyaan</p>
                                <button type="button" onclick="addQuestion()"
                                    class="mt-2 text-simawa-500 hover:text-simawa-700 text-sm font-medium">
                                    + Tambah Pertanyaan Pertama
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="p-4 sm:p-6 border-t border-gray-100 flex flex-col sm:flex-row gap-3">
                        <button type="button" onclick="closeFormModal()"
                            class="w-full sm:flex-1 px-4 py-2.5 border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 transition-all text-sm font-medium order-3 sm:order-1">
                            Batal
                        </button>
                        <button type="button" onclick="previewForm()"
                            class="w-full sm:flex-1 px-4 py-2.5 border border-simawa-500 text-simawa-600 rounded-lg hover:bg-simawa-50 transition-all text-sm font-medium order-2">
                            <ion-icon name="eye-outline" class="mr-1"></ion-icon>
                            Preview
                        </button>
                        <button type="submit"
                            class="w-full sm:flex-1 px-4 py-2.5 bg-gradient-to-r from-simawa-500 to-simawa-700 text-white rounded-lg hover:from-simawa-600 hover:to-simawa-800 transition-all text-sm font-medium order-1 sm:order-3">
                            Simpan Formulir
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Preview Modal -->
    <div id="previewModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closePreviewModal()"></div>
        <div class="flex items-start justify-center min-h-screen p-4 pt-6 sm:pt-10">
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl transform transition-all">
                <div class="p-4 sm:p-6 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Preview Formulir</h3>
                        <p id="previewFormTitle" class="text-sm text-gray-500 mt-1">-</p>
                    </div>
                    <button onclick="closePreviewModal()" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                        <ion-icon name="close-outline" class="text-xl text-gray-500"></ion-icon>
                    </button>
                </div>
                <div id="previewContent" class="p-4 sm:p-6 space-y-4 max-h-[60vh] overflow-y-auto">
                    <!-- Preview content will be rendered here -->
                </div>
                <div class="p-4 sm:p-6 border-t border-gray-100">
                    <button onclick="closePreviewModal()"
                        class="w-full px-4 py-2.5 bg-gradient-to-r from-simawa-500 to-simawa-700 text-white rounded-lg hover:from-simawa-600 hover:to-simawa-800 transition-all text-sm font-medium">
                        Tutup Preview
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Form Modal -->
    <div id="deleteFormModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeDeleteFormModal()"></div>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm transform transition-all">
                <div class="p-6 text-center">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <ion-icon name="trash-outline" class="text-3xl text-red-500"></ion-icon>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Hapus Formulir?</h3>
                    <p class="text-sm text-gray-500 mb-6">Data formulir yang dihapus tidak dapat dikembalikan. Apakah Anda
                        yakin?</p>
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="flex gap-3">
                            <button type="button" onclick="closeDeleteFormModal()"
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
        const baseUrl = "{{ route('superadmin.formulir-prestasi.index') }}";
        let currentQuestions = [];
        let questionIdCounter = 0;

        // Open Form Modal
        function openFormModal() {
            document.getElementById('formModal').classList.remove('hidden');
            document.getElementById('formModalTitle').textContent = 'Buat Formulir Baru';
            document.getElementById('formulirForm').action = "{{ route('superadmin.formulir-prestasi.store') }}";
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('formTitle').value = '';
            document.getElementById('formCategory').selectedIndex = 0;
            currentQuestions = [];
            questionIdCounter = 0;
            renderQuestions();
        }

        // Close Form Modal
        function closeFormModal() {
            document.getElementById('formModal').classList.add('hidden');
        }

        // Add Question
        function addQuestion() {
            questionIdCounter++;
            currentQuestions.push({
                id: questionIdCounter,
                pertanyaan: '',
                tipe: 'text',
                wajib: true,
                opsi: []
            });
            renderQuestions();
        }

        // Remove Question
        function removeQuestion(id) {
            currentQuestions = currentQuestions.filter(q => q.id !== id);
            renderQuestions();
        }

        // Update Question
        function updateQuestion(id, field, value) {
            const question = currentQuestions.find(q => q.id === id);
            if (question) {
                question[field] = value;
                if (field === 'tipe' && value === 'dropdown' && (!question.opsi || question.opsi.length === 0)) {
                    question.opsi = [''];
                }
                if (field === 'tipe') {
                    renderQuestions();
                }
            }
        }

        // Add Option to Dropdown
        function addOption(questionId) {
            const question = currentQuestions.find(q => q.id === questionId);
            if (question) {
                if (!question.opsi) question.opsi = [];
                question.opsi.push('');
                renderQuestions();
            }
        }

        // Update Option
        function updateOption(questionId, optionIndex, value) {
            const question = currentQuestions.find(q => q.id === questionId);
            if (question && question.opsi) {
                question.opsi[optionIndex] = value;
            }
        }

        // Remove Option from Dropdown
        function removeOption(questionId, optionIndex) {
            const question = currentQuestions.find(q => q.id === questionId);
            if (question && question.opsi && question.opsi.length > 1) {
                question.opsi.splice(optionIndex, 1);
                renderQuestions();
            }
        }

        // Render Questions
        function renderQuestions() {
            const container = document.getElementById('questionsContainer');
            const noQuestions = document.getElementById('noQuestions');

            if (currentQuestions.length === 0) {
                container.innerHTML = '';
                noQuestions.classList.remove('hidden');
                return;
            }

            noQuestions.classList.add('hidden');
            container.innerHTML = currentQuestions.map((q, index) => `
                    <div class="bg-gray-50 rounded-xl p-3 sm:p-4 border border-gray-100">
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-simawa-500 text-white text-xs font-medium flex-shrink-0">${index + 1}</span>
                            <button type="button" onclick="removeQuestion(${q.id})" class="p-1 text-red-400 hover:text-red-600 hover:bg-red-50 rounded transition-colors">
                                <ion-icon name="close-outline" class="text-lg"></ion-icon>
                            </button>
                        </div>
                        <div class="space-y-3">
                            <input type="text" name="pertanyaans[${index}][pertanyaan]" value="${q.pertanyaan}" 
                                onchange="updateQuestion(${q.id}, 'pertanyaan', this.value)"
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-simawa-500/20 focus:border-simawa-500 transition-all text-sm"
                                placeholder="Tulis pertanyaan..." required>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">Tipe Jawaban</label>
                                    <select name="pertanyaans[${index}][tipe]" onchange="updateQuestion(${q.id}, 'tipe', this.value)"
                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-simawa-500/20 focus:border-simawa-500 transition-all text-sm">
                                        <option value="text" ${q.tipe === 'text' ? 'selected' : ''}>Teks</option>
                                        <option value="dropdown" ${q.tipe === 'dropdown' ? 'selected' : ''}>Dropdown</option>
                                        <option value="file" ${q.tipe === 'file' ? 'selected' : ''}>File Upload</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">Wajib Diisi</label>
                                    <select name="pertanyaans[${index}][wajib]" onchange="updateQuestion(${q.id}, 'wajib', this.value === 'true')"
                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-simawa-500/20 focus:border-simawa-500 transition-all text-sm">
                                        <option value="true" ${q.wajib ? 'selected' : ''}>Ya, Wajib</option>
                                        <option value="false" ${!q.wajib ? 'selected' : ''}>Tidak</option>
                                    </select>
                                </div>
                            </div>
                            ${q.tipe === 'dropdown' ? renderDropdownOptions(q, index) : ''}
                        </div>
                    </div>
                `).join('');
        }

        // Render Dropdown Options
        function renderDropdownOptions(question, questionIndex) {
            const options = question.opsi && question.opsi.length > 0 ? question.opsi : [''];
            return `
                    <div class="space-y-2">
                        <label class="block text-xs text-gray-500">Opsi Dropdown</label>
                        <div class="space-y-2">
                            ${options.map((opt, optIndex) => `
                                <div class="flex items-center gap-2">
                                    <div class="w-4 h-4 rounded-full border-2 border-gray-300 flex-shrink-0"></div>
                                    <input type="text" name="pertanyaans[${questionIndex}][opsi][]" value="${opt}" 
                                        onchange="updateOption(${question.id}, ${optIndex}, this.value)"
                                        class="flex-1 px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-simawa-500/20 focus:border-simawa-500 transition-all text-sm"
                                        placeholder="Opsi ${optIndex + 1}">
                                    <button type="button" onclick="removeOption(${question.id}, ${optIndex})" 
                                        class="p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors flex-shrink-0"
                                        title="Hapus opsi">
                                        <ion-icon name="close-outline" class="text-lg"></ion-icon>
                                    </button>
                                </div>
                            `).join('')}
                        </div>
                        <button type="button" onclick="addOption(${question.id})" 
                            class="inline-flex items-center gap-1.5 text-sm text-simawa-500 hover:text-simawa-700 font-medium mt-1">
                            <ion-icon name="add-circle-outline"></ion-icon>
                            Tambah Opsi
                        </button>
                    </div>
                `;
        }

        // Edit Form
        function editForm(id) {
            fetch(baseUrl + '/' + id)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('formModal').classList.remove('hidden');
                    document.getElementById('formModalTitle').textContent = 'Edit Formulir';
                    document.getElementById('formulirForm').action = baseUrl + '/' + id;
                    document.getElementById('formMethod').value = 'PUT';
                    document.getElementById('formTitle').value = data.judul;
                    document.getElementById('formCategory').value = data.kategori_prestasi_id;

                    currentQuestions = data.pertanyaans.map((p, i) => ({
                        id: i + 1,
                        pertanyaan: p.pertanyaan,
                        tipe: p.tipe,
                        wajib: p.wajib,
                        opsi: p.opsi || []
                    }));
                    questionIdCounter = currentQuestions.length;
                    renderQuestions();
                });
        }

        // Preview Form
        function previewForm() {
            const title = document.getElementById('formTitle').value || 'Formulir Tanpa Judul';
            document.getElementById('previewFormTitle').textContent = title;
            renderPreview(currentQuestions);
            document.getElementById('previewModal').classList.remove('hidden');
        }

        // View Form Preview from list
        function viewFormPreview(id) {
            fetch(baseUrl + '/' + id)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('previewFormTitle').textContent = data.judul;
                    renderPreview(data.pertanyaans);
                    document.getElementById('previewModal').classList.remove('hidden');
                });
        }

        // Render Preview
        function renderPreview(questions) {
            const container = document.getElementById('previewContent');

            if (!questions || questions.length === 0) {
                container.innerHTML = '<p class="text-center text-gray-500 py-8">Tidak ada pertanyaan untuk ditampilkan</p>';
                return;
            }

            container.innerHTML = questions.map((q, index) => `
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">
                            ${index + 1}. ${q.pertanyaan || 'Pertanyaan belum diisi'}
                            ${q.wajib ? '<span class="text-red-500">*</span>' : ''}
                        </label>
                        ${q.tipe === 'text' ? `
                            <input type="text" disabled
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-gray-50 text-sm"
                                placeholder="Jawaban teks...">
                        ` : ''}
                        ${q.tipe === 'dropdown' ? `
                            <select disabled class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-gray-50 text-sm">
                                <option value="">Pilih salah satu...</option>
                                ${q.opsi ? q.opsi.map(opt => `<option>${opt}</option>`).join('') : ''}
                            </select>
                        ` : ''}
                        ${q.tipe === 'file' ? `
                            <div class="border-2 border-dashed border-gray-200 rounded-lg p-4 text-center bg-gray-50">
                                <ion-icon name="cloud-upload-outline" class="text-3xl text-gray-400 mb-2"></ion-icon>
                                <p class="text-sm text-gray-500">Klik atau drag file untuk upload</p>
                                <p class="text-xs text-gray-400 mt-1">PDF, JPG, PNG (Max. 5MB)</p>
                            </div>
                        ` : ''}
                    </div>
                `).join('');
        }

        // Close Preview Modal
        function closePreviewModal() {
            document.getElementById('previewModal').classList.add('hidden');
        }

        // Delete Form
        function deleteForm(id) {
            document.getElementById('deleteFormModal').classList.remove('hidden');
            document.getElementById('deleteForm').action = baseUrl + '/' + id;
        }

        // Close Delete Form Modal
        function closeDeleteFormModal() {
            document.getElementById('deleteFormModal').classList.add('hidden');
        }

        // Close modal on escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeFormModal();
                closePreviewModal();
                closeDeleteFormModal();
            }
        });
    </script>
@endpush