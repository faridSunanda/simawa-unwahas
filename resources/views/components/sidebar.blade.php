@php
    use App\Models\PengajuanPrestasi;

    $actualRole = Auth::user()->role->name ?? 'user';
    $impersonatedRole = session('impersonated_role');
    $role = $impersonatedRole ?? $actualRole;
    $roleName = Auth::user()->role->display_name ?? 'User';

    // Count pending verifications for superadmin/kemahasiswaan
    $pendingVerifikasiCount = 0;
    if (in_array($role, ['superadmin', 'kemahasiswaan'])) {
        $pendingVerifikasiCount = PengajuanPrestasi::where('status', 'menunggu')->count();
    }

    // Count revisions needed for mahasiswa
    $revisiCount = 0;
    if ($role === 'mahasiswa') {
        $revisiCount = PengajuanPrestasi::where('mahasiswa_id', Auth::id())->where('status', 'revisi')->count();
    }
@endphp
<aside id="sidebar"
    class="sidebar w-64 bg-gradient-to-b from-simawa-500 to-simawa-800 text-white fixed h-full z-40 overflow-y-auto">
    <!-- Logo -->
    <div class="p-6 border-b border-white/10">
        <div class="flex items-center gap-3 w-full">
            <img src="/image/logo.png" alt="Logo" class="h-16 object-contain bg-white/10 rounded-lg p-2 w-full"
                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
            <div class="hidden h-16 w-full bg-white/10 rounded-lg items-center justify-center">
                <span class="text-xl font-bold">SIMAWA</span>
            </div>
        </div>
    </div>

    <nav class="p-4">
        <!-- Dashboard -->
        <ul class="space-y-1">
            <li>
                <a href="{{ route($role . '.dashboard') }}"
                    class="sidebar-menu-item {{ request()->routeIs($role . '.dashboard') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm">
                    <ion-icon name="grid" class="text-xl"></ion-icon>
                    Dashboard
                </a>
            </li>
        </ul>

        @if($role === 'superadmin')
            <!-- Superadmin Menu -->
            <p class="text-xs text-white/50 uppercase tracking-wider mb-3 px-3 mt-6">Menu</p>
            <ul class="space-y-1">
                <li class="submenu-parent">
                    <button onclick="this.parentElement.classList.toggle('open')"
                        class="sidebar-menu-item flex items-center justify-between w-full px-3 py-2.5 rounded-lg text-sm">
                        <span class="flex items-center gap-3">
                            <ion-icon name="cloud-done-outline" class="text-xl"></ion-icon>SIMKATMAWA
                        </span>
                        <ion-icon name="chevron-down-outline" class="text-sm submenu-arrow transition-transform"></ion-icon>
                    </button>
                    <ul class="submenu pl-8 space-y-1 mt-1 hidden">
                        <li><a href="{{ route('superadmin.kategori-prestasi.index') }}"
                                class="sidebar-menu-item {{ request()->routeIs('superadmin.kategori-prestasi.*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2 rounded-lg text-sm">
                                <ion-icon name="medal-outline" class="text-lg"></ion-icon>Kategori Prestasi</a></li>
                        <li><a href="{{ route('superadmin.formulir-prestasi.index') }}"
                                class="sidebar-menu-item {{ request()->routeIs('superadmin.formulir-prestasi.*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2 rounded-lg text-sm">
                                <ion-icon name="reader-outline" class="text-lg"></ion-icon>Formulir Prestasi</a></li>
                        <li>
                            <a href="{{ route('superadmin.verifikasi-prestasi.index') }}"
                                class="sidebar-menu-item {{ request()->routeIs('superadmin.verifikasi-prestasi.*') ? 'active' : '' }} flex items-center justify-between px-3 py-2 rounded-lg text-sm">
                                <span class="flex items-center gap-3">
                                    <ion-icon name="checkmark-done-outline" class="text-lg"></ion-icon>Verifikasi Prestasi
                                </span>
                                @if($pendingVerifikasiCount > 0)
                                    <span
                                        class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full min-w-[20px] text-center">{{ $pendingVerifikasiCount }}</span>
                                @endif
                            </a>
                        </li>
                        <li><a href="{{ route('superadmin.daftar-prestasi.index') }}"
                                class="sidebar-menu-item {{ request()->routeIs('superadmin.daftar-prestasi.*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2 rounded-lg text-sm">
                                <ion-icon name="school-outline" class="text-lg"></ion-icon>Daftar Prestasi</a></li>
                    </ul>
                </li>

                <li class="submenu-parent">
                    <button onclick="this.parentElement.classList.toggle('open')"
                        class="sidebar-menu-item flex items-center justify-between w-full px-3 py-2.5 rounded-lg text-sm">
                        <span class="flex items-center gap-3">
                            <ion-icon name="podium-outline" class="text-xl"></ion-icon>Internal Universitas
                        </span>
                        <ion-icon name="chevron-down-outline" class="text-sm submenu-arrow transition-transform"></ion-icon>
                    </button>
                    <ul class="submenu pl-8 space-y-1 mt-1 hidden">
                        <li><a href="{{ route('superadmin.jenis-sertifikat.index') }}"
                                class="sidebar-menu-item {{ request()->routeIs('superadmin.jenis-sertifikat.*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2 rounded-lg text-sm">
                                <ion-icon name="library-outline" class="text-lg"></ion-icon>Jenis Sertifikat</a></li>
                        <li><a href="{{ route('superadmin.daftar-mahasiswa.index') }}"
                                class="sidebar-menu-item {{ request()->routeIs('superadmin.daftar-mahasiswa.*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2 rounded-lg text-sm">
                                <ion-icon name="person-circle-outline" class="text-lg"></ion-icon>Daftar Mahasiswa</a></li>
                        <li><a href="{{ route('superadmin.kegiatan.index') }}"
                                class="sidebar-menu-item {{ request()->routeIs('superadmin.kegiatan.*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2 rounded-lg text-sm">
                                <ion-icon name="pricetags-outline" class="text-lg"></ion-icon>Kegiatan</a></li>
                    </ul>
                </li>
            </ul>

            <p class="text-xs text-white/50 uppercase tracking-wider mb-3 px-3 mt-6">User</p>
            <ul class="space-y-1">
                <li><a href="{{ route('superadmin.users.index') }}"
                        class="sidebar-menu-item {{ request()->routeIs('superadmin.users.*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm">
                        <ion-icon name="people-outline" class="text-xl"></ion-icon>Kelola Users</a></li>
                <li><a href="#" class="sidebar-menu-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm">
                        <ion-icon name="shield-checkmark-outline" class="text-xl"></ion-icon>Log Aktivitas</a></li>
            </ul>

        @elseif($role === 'kemahasiswaan')
            <!-- Kemahasiswaan Menu -->
            <p class="text-xs text-white/50 uppercase tracking-wider mb-3 px-3 mt-6">Menu</p>
            <ul class="space-y-1">
                <li><a href="#" class="sidebar-menu-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm">
                        <ion-icon name="calendar-outline" class="text-xl"></ion-icon>Kegiatan Mahasiswa</a></li>
                <li><a href="#" class="sidebar-menu-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm">
                        <ion-icon name="checkmark-done-outline" class="text-xl"></ion-icon>Verifikasi</a></li>
                <li><a href="#" class="sidebar-menu-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm">
                        <ion-icon name="document-text-outline" class="text-xl"></ion-icon>Laporan</a></li>
            </ul>

        @elseif($role === 'pimpinan' || $role === 'dekan')
            <!-- Pimpinan/Dekan Menu -->
            <p class="text-xs text-white/50 uppercase tracking-wider mb-3 px-3 mt-6">Monitoring</p>
            <ul class="space-y-1">
                <li><a href="#" class="sidebar-menu-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm">
                        <ion-icon name="bar-chart-outline" class="text-xl"></ion-icon>Laporan</a></li>
                <li><a href="#" class="sidebar-menu-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm">
                        <ion-icon name="people-outline" class="text-xl"></ion-icon>Data Mahasiswa</a></li>
                <li><a href="#" class="sidebar-menu-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm">
                        <ion-icon name="trophy-outline" class="text-xl"></ion-icon>Prestasi</a></li>
            </ul>

        @elseif($role === 'kaprodi')
            <!-- Kaprodi Menu -->
            <p class="text-xs text-white/50 uppercase tracking-wider mb-3 px-3 mt-6">Verifikasi</p>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('kaprodi.verifikasi-sertifikat.index') }}"
                        class="sidebar-menu-item {{ request()->routeIs('kaprodi.verifikasi-sertifikat.*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm">
                        <ion-icon name="ribbon-outline" class="text-xl"></ion-icon>Verifikasi Sertifikat
                    </a>
                </li>
            </ul>
            <p class="text-xs text-white/50 uppercase tracking-wider mb-3 px-3 mt-6">Monitoring</p>
            <ul class="space-y-1">
                <li><a href="#" class="sidebar-menu-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm">
                        <ion-icon name="bar-chart-outline" class="text-xl"></ion-icon>Laporan</a></li>
                <li><a href="#" class="sidebar-menu-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm">
                        <ion-icon name="people-outline" class="text-xl"></ion-icon>Data Mahasiswa</a></li>
                <li><a href="#" class="sidebar-menu-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm">
                        <ion-icon name="trophy-outline" class="text-xl"></ion-icon>Prestasi</a></li>
            </ul>

        @elseif($role === 'mahasiswa')
            <!-- Mahasiswa Menu -->
            <p class="text-xs text-white/50 uppercase tracking-wider mb-3 px-3 mt-6">Menu</p>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('mahasiswa.kegiatan.index') }}"
                        class="sidebar-menu-item {{ request()->routeIs('mahasiswa.kegiatan.index') || request()->routeIs('mahasiswa.kegiatan.show') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm">
                        <ion-icon name="calendar-outline" class="text-xl"></ion-icon>Daftar Kegiatan
                    </a>
                </li>
                <li>
                    <a href="{{ route('mahasiswa.kegiatan.saya') }}"
                        class="sidebar-menu-item {{ request()->routeIs('mahasiswa.kegiatan.saya') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm">
                        <ion-icon name="checkmark-circle-outline" class="text-xl"></ion-icon>Kegiatan Saya
                    </a>
                </li>
                <li>
                    <a href="{{ route('mahasiswa.prestasi.index') }}"
                        class="sidebar-menu-item {{ request()->routeIs('mahasiswa.prestasi.*') ? 'active' : '' }} flex items-center justify-between px-3 py-2.5 rounded-lg text-sm">
                        <span class="flex items-center gap-3">
                            <ion-icon name="trophy-outline" class="text-xl"></ion-icon>Prestasi
                        </span>
                        @if($revisiCount > 0)
                            <span
                                class="bg-orange-500 text-white text-xs font-bold px-2 py-0.5 rounded-full min-w-[20px] text-center">{{ $revisiCount }}</span>
                        @endif
                    </a>
                </li>
                <li>
                    <a href="{{ route('mahasiswa.sertifikat.index') }}"
                        class="sidebar-menu-item {{ request()->routeIs('mahasiswa.sertifikat.*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm">
                        <ion-icon name="ribbon-outline" class="text-xl"></ion-icon>Sertifikat
                    </a>
                </li>
            </ul>
        @endif
    </nav>
</aside>