@extends('layouts.dashboard')

@section('title', 'Kelola Users')
@section('page-title', 'Kelola Users')
@section('page-description', 'Kelola data pengguna sistem')

@section('content')
    <!-- Page Title -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Kelola Users</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola data pengguna sistem</p>
        </div>
        <a href="{{ route('superadmin.users.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-simawa-500 to-simawa-700 text-white rounded-lg hover:from-simawa-600 hover:to-simawa-800 transition-all text-sm font-medium shadow-sm">
            <ion-icon name="add-outline"></ion-icon>
            Tambah User
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
            <ion-icon name="close-circle" class="text-xl flex-shrink-0"></ion-icon>
            <span class="text-sm">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Filter Tabs -->
    <div class="mb-6 flex flex-wrap gap-2">
        @php
            $currentRole = request('role', 'semua');
        @endphp
        <a href="{{ route('superadmin.users.index', ['role' => 'semua']) }}"
            class="px-3 py-1.5 sm:px-4 sm:py-2 rounded-lg text-xs sm:text-sm font-medium transition-all {{ $currentRole === 'semua' ? 'bg-simawa-500 text-white' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200' }}">
            Semua
        </a>
        @foreach($roles as $role)
            <a href="{{ route('superadmin.users.index', ['role' => $role->name]) }}"
                class="px-3 py-1.5 sm:px-4 sm:py-2 rounded-lg text-xs sm:text-sm font-medium transition-all {{ $currentRole === $role->name ? 'bg-simawa-500 text-white' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200' }}">
                {{ $role->display_name }}
            </a>
        @endforeach
    </div>

    @if($users->count() > 0)
        <!-- Mobile Card View -->
        <div class="lg:hidden space-y-3">
            @foreach($users as $user)
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-gray-800 truncate">{{ $user->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                        </div>
                        <span
                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium flex-shrink-0 {{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                    <div class="space-y-2 text-sm">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500">Role:</span>
                            <span
                                class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                {{ $user->role->display_name ?? '-' }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500">Dibuat:</span>
                            <span class="text-gray-800">{{ $user->created_at->format('d M Y') }}</span>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-gray-100 flex gap-2">
                        <a href="{{ route('superadmin.users.edit', $user) }}"
                            class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2 bg-simawa-600 hover:bg-simawa-700 text-white rounded-lg transition-colors text-sm font-medium">
                            <ion-icon name="create-outline"></ion-icon>
                            Edit
                        </a>
                        <form action="{{ route('superadmin.users.toggle-status', $user) }}" method="POST" class="flex-1">
                            @csrf
                            @method('PUT')
                            <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 {{ $user->is_active ? 'bg-amber-500 hover:bg-amber-600' : 'bg-green-500 hover:bg-green-600' }} text-white rounded-lg transition-colors text-sm font-medium">
                                <ion-icon name="{{ $user->is_active ? 'pause-circle-outline' : 'play-circle-outline' }}"></ion-icon>
                                {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Desktop Table View -->
        <div class="hidden lg:block bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full" id="dataTable">
                    <thead class="bg-simawa-600">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase w-16">No</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase">Nama</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase">Email</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase">Role</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-white uppercase">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase">Dibuat</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-white uppercase w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($users as $index => $user)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $index + 1 }}</td>
                                <td class="px-6 py-4">
                                    <p class="text-sm font-medium text-gray-800">{{ $user->name }}</p>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $user->email }}</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                        {{ $user->role->display_name ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $user->created_at->format('d M Y') }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-1">
                                        <a href="{{ route('superadmin.users.edit', $user) }}"
                                            class="inline-flex h-9 w-9 items-center justify-center text-white bg-simawa-600 hover:bg-simawa-700 rounded-lg transition-colors"
                                            title="Edit">
                                            <ion-icon name="create-outline" class="text-lg"></ion-icon>
                                        </a>
                                        <form action="{{ route('superadmin.users.toggle-status', $user) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit"
                                                class="inline-flex h-9 w-9 items-center justify-center text-white {{ $user->is_active ? 'bg-amber-500 hover:bg-amber-600' : 'bg-green-500 hover:bg-green-600' }} rounded-lg transition-colors"
                                                title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                <ion-icon
                                                    name="{{ $user->is_active ? 'pause-circle-outline' : 'play-circle-outline' }}"
                                                    class="text-lg"></ion-icon>
                                            </button>
                                        </form>
                                        @if(auth()->id() !== $user->id)
                                            <form action="{{ route('superadmin.users.destroy', $user) }}" method="POST" class="inline"
                                                onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex h-9 w-9 items-center justify-center text-white bg-red-500 hover:bg-red-600 rounded-lg transition-colors"
                                                    title="Hapus">
                                                    <ion-icon name="trash-outline" class="text-lg"></ion-icon>
                                                </button>
                                            </form>
                                        @endif
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
            <ion-icon name="people-outline" class="text-5xl text-gray-300 mb-3"></ion-icon>
            <p class="text-gray-500">Belum ada user</p>
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            if ($('#dataTable tbody tr').length > 0) {
                $('#dataTable').DataTable({
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
                    },
                    order: [[5, 'desc']],
                    columnDefs: [
                        { orderable: false, targets: [0, 6] }
                    ]
                });
            }
        });
    </script>
@endpush