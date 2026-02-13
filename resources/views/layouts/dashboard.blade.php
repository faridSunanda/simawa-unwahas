<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') | SIMKATMAWA</title>

    <link rel="icon" type="image/png" href="/image/favicon.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'simawa': {
                            '50': '#BDE8F5',
                            '100': '#9DD9EF',
                            '200': '#7DCAE9',
                            '300': '#4988C4',
                            '400': '#3A7AB8',
                            '500': '#1C4D8D',
                            '600': '#184480',
                            '700': '#143B73',
                            '800': '#0F2854',
                            '900': '#0A1F45',
                        },
                    },
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif']
                    },
                },
            },
        }
    </script>

    <style>
        .sidebar-menu-item {
            color: rgba(255, 255, 255, 0.7);
            transition: all 0.2s ease;
        }

        .sidebar-menu-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: #ffffff;
        }

        .sidebar-menu-item.active {
            background-color: rgba(255, 255, 255, 0.2);
            color: #ffffff;
            font-weight: 500;
        }

        .submenu-parent.open .submenu {
            display: block !important;
        }

        .submenu-parent.open .submenu-arrow {
            transform: rotate(180deg);
        }

        /* Mobile sidebar */
        @media (max-width: 1023px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .sidebar-overlay {
                display: none;
            }

            .sidebar-overlay.open {
                display: block;
            }
        }

        /* Desktop sidebar collapse */
        @media (min-width: 1024px) {
            .sidebar {
                transition: transform 0.3s ease;
            }

            .sidebar.collapsed {
                transform: translateX(-100%);
            }

            .main-content {
                transition: margin-left 0.3s ease;
            }

            .main-content.expanded {
                margin-left: 0 !important;
            }
        }
    </style>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

    <!-- Custom DataTables Styling -->
    <style>
        /* DataTables wrapper styling */
        .dataTables_wrapper {
            padding: 0;
        }

        /* Top section: length & filter */
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            padding: 1rem 1.5rem;
            margin-bottom: 0;
        }

        .dataTables_wrapper .dataTables_length label,
        .dataTables_wrapper .dataTables_filter label {
            font-size: 0.875rem;
            color: #4B5563;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            padding: 0.5rem 0.75rem;
            border: 1px solid #E5E7EB;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            outline: none;
            transition: all 0.2s;
        }

        .dataTables_wrapper .dataTables_length select:focus,
        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: #1C4D8D;
            box-shadow: 0 0 0 3px rgba(28, 77, 141, 0.1);
        }

        .dataTables_wrapper .dataTables_filter input {
            width: 200px;
        }

        /* Table styling */
        table.dataTable {
            border-collapse: collapse !important;
            width: 100% !important;
        }

        table.dataTable thead th {
            background: #184480 !important;
            color: white !important;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 1rem 1.5rem !important;
            border-bottom: none !important;
        }

        table.dataTable thead th:first-child {
            border-top-left-radius: 0;
        }

        table.dataTable thead th:last-child {
            border-top-right-radius: 0;
        }

        table.dataTable thead th.sorting:after,
        table.dataTable thead th.sorting_asc:after,
        table.dataTable thead th.sorting_desc:after {
            opacity: 0.5;
        }

        table.dataTable thead th.sorting_asc:after,
        table.dataTable thead th.sorting_desc:after {
            opacity: 1;
        }

        table.dataTable tbody td {
            padding: 1rem 1.5rem !important;
            border-bottom: 1px solid #F3F4F6 !important;
            font-size: 0.875rem;
        }

        table.dataTable tbody tr:hover {
            background-color: rgba(249, 250, 251, 0.5) !important;
        }

        /* Bottom section: info & pagination */
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            padding: 1rem 1.5rem;
        }

        .dataTables_wrapper .dataTables_info {
            font-size: 0.875rem;
            color: #6B7280;
        }

        .dataTables_wrapper .dataTables_paginate {
            text-align: right;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.5rem 0.875rem !important;
            margin: 0 0.125rem;
            border: 1px solid #E5E7EB !important;
            border-radius: 0.5rem !important;
            background: white !important;
            color: #374151 !important;
            font-size: 0.875rem;
            transition: all 0.2s;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #F3F4F6 !important;
            border-color: #D1D5DB !important;
            color: #1C4D8D !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: linear-gradient(to right, #1C4D8D, #143B73) !important;
            border-color: #1C4D8D !important;
            color: white !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Empty table */
        table.dataTable tbody td.dataTables_empty {
            text-align: center;
            padding: 3rem 1.5rem !important;
            color: #9CA3AF;
        }

        /* Responsive */
        @media (max-width: 768px) {

            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_filter {
                text-align: left;
                float: none;
                padding: 0.75rem 1rem;
            }

            .dataTables_wrapper .dataTables_filter input {
                width: 100%;
                margin-top: 0.5rem;
            }

            .dataTables_wrapper .dataTables_info,
            .dataTables_wrapper .dataTables_paginate {
                text-align: center;
                float: none;
                padding: 0.75rem 1rem;
            }
        }
    </style>

    @stack('styles')
</head>

<body class="bg-gray-50 font-inter">
    <div class="flex min-h-screen">
        <!-- Mobile Overlay -->
        <div id="sidebarOverlay" class="sidebar-overlay fixed inset-0 bg-black/50 z-30 lg:hidden"
            onclick="toggleSidebar()"></div>

        <!-- Sidebar Component -->
        @include('components.sidebar')

        <!-- Main Content -->
        <main class="main-content flex-1 lg:ml-64 min-h-screen flex flex-col">
            <!-- Header Component -->
            @include('components.header')

            <!-- Page Content -->
            <div class="flex-1 p-4 md:p-6 flex flex-col">
                @yield('content')
            </div>

            <!-- Footer Component -->
            @include('components.footer')
        </main>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const mainContent = document.querySelector('.main-content');

            // Check if we're on mobile or desktop
            if (window.innerWidth >= 1024) {
                // Desktop: toggle collapsed state
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('expanded');
            } else {
                // Mobile: toggle open state with overlay
                sidebar.classList.toggle('open');
                overlay.classList.toggle('open');
            }
        }

        // Close submenu when clicking outside
        document.querySelectorAll('.submenu-parent').forEach(parent => {
            const button = parent.querySelector('button');
            button.addEventListener('click', (e) => {
                e.stopPropagation();
                // Close other submenus
                document.querySelectorAll('.submenu-parent.open').forEach(other => {
                    if (other !== parent) other.classList.remove('open');
                });
            });
        });
    </script>

    <!-- jQuery & DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    @stack('scripts')
</body>

</html>