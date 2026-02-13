@php
    $user = Auth::user();
    $actualRole = $user->role->name ?? 'user';
    $impersonatedRole = session('impersonated_role');
    $currentRole = $impersonatedRole ?? $actualRole;
    $roleName = $user->role->display_name ?? 'User';
    $userName = $user->name ?? 'User';
    $isImpersonating = $actualRole === 'superadmin' && $impersonatedRole && $impersonatedRole !== 'superadmin';
@endphp

<header class="bg-white border-b border-gray-200 sticky top-0 z-30">
    <div class="flex items-center justify-between px-4 md:px-6 py-4">
        <div class="flex items-center gap-4">
            <!-- Hamburger Button (All Screens) -->
            <button onclick="toggleSidebar()" class="p-2 rounded-lg hover:bg-gray-100 transition-colors" title="Toggle Sidebar">
                <ion-icon name="menu" class="text-2xl text-gray-600"></ion-icon>
            </button>
        </div>

        <div class="flex items-center gap-2 md:gap-4">
            <!-- Impersonation Banner -->
            @if($isImpersonating)
                <a href="{{ route('superadmin.reset-role') }}"
                    class="flex items-center gap-2 px-3 py-1.5 bg-amber-100 text-amber-800 rounded-lg text-sm font-medium hover:bg-amber-200 transition-colors">
                    <span class="hidden text-xs sm:inline">Viewing as {{ ucfirst($impersonatedRole) }}</span>
                    <ion-icon name="close-circle" class="text-lg"></ion-icon>
                </a>
            @endif

            <!-- User Menu with Dropdown -->
            <div class="relative">
                <button onclick="toggleUserDropdown()"
                    class="flex items-center gap-2 md:gap-3 pl-2 md:pl-4 border-l border-gray-200 hover:bg-gray-50 rounded-lg p-2 transition-colors">
                    <div class="w-9 h-9 md:w-10 md:h-10 rounded-full bg-simawa-50 flex items-center justify-center">
                        <ion-icon name="person" class="text-simawa-800 text-lg md:text-xl"></ion-icon>
                    </div>
                    <div class="hidden sm:block text-left">
                        <p class="text-sm font-semibold text-gray-800">{{ $userName }}</p>
                        <p class="text-xs text-gray-500">{{ $roleName }}</p>
                    </div>
                    <ion-icon name="chevron-down-outline" class="text-gray-400 text-sm hidden sm:block"></ion-icon>
                </button>

                <!-- Dropdown Menu -->
                <div id="userDropdown"
                    class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-100 py-2 hidden z-50">

                    <!-- User Info (mobile) -->
                    <div class="px-4 py-3 border-b border-gray-100 sm:hidden">
                        <p class="text-sm font-semibold text-gray-800">{{ $userName }}</p>
                        <p class="text-xs text-gray-500">{{ $roleName }}</p>
                    </div>

                    @if($actualRole === 'superadmin')
                        <!-- Ganti Role (Superadmin Only) -->
                        <div class="px-2 py-1">
                            <p class="px-3 py-1 text-xs text-gray-400 uppercase tracking-wider">Ganti Role</p>

                            @if($currentRole !== 'superadmin')
                                <a href="{{ route('superadmin.reset-role') }}"
                                    class="flex items-center gap-3 px-3 py-2 text-sm text-simawa-600 bg-simawa-50 hover:bg-simawa-100 rounded-lg transition-colors font-medium">
                                    <ion-icon name="shield-checkmark" class="text-lg"></ion-icon>
                                    Kembali ke Superadmin
                                </a>
                            @endif

                            @foreach(['kemahasiswaan', 'pimpinan', 'dekan', 'kaprodi', 'mahasiswa'] as $roleOption)
                                @if($currentRole !== $roleOption)
                                    <a href="{{ route('superadmin.switch-role', $roleOption) }}"
                                        class="flex items-center gap-3 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-lg transition-colors">
                                        <ion-icon name="person-circle-outline" class="text-lg text-gray-400"></ion-icon>
                                        {{ ucfirst($roleOption) }}
                                    </a>
                                @endif
                            @endforeach
                        </div>
                        <div class="border-t border-gray-100 my-1"></div>
                    @endif

                    <!-- Menu Items -->
                    <div class="px-2 py-1">
                        <a href="#"
                            class="flex items-center gap-3 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-lg transition-colors">
                            <ion-icon name="arrow-back-outline" class="text-lg text-gray-400"></ion-icon>
                            Kembali ke SSO
                        </a>
                    </div>

                    <div class="border-t border-gray-100 my-1"></div>

                    <!-- Logout -->
                    <div class="px-2 py-1">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="flex items-center gap-3 px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg transition-colors w-full text-left">
                                <ion-icon name="log-out-outline" class="text-lg"></ion-icon>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
    function toggleUserDropdown() {
        const dropdown = document.getElementById('userDropdown');
        dropdown.classList.toggle('hidden');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function (event) {
        const dropdown = document.getElementById('userDropdown');
        const button = event.target.closest('button');

        if (!event.target.closest('#userDropdown') && !button?.onclick?.toString().includes('toggleUserDropdown')) {
            dropdown?.classList.add('hidden');
        }
    });
</script>