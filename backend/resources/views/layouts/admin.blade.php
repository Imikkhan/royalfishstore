<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50 dark:bg-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - Royal Fish Store</title>

    <!-- Google Fonts & FontAwesome -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CSS (via Play CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#fff1f1',
                            100: '#ffe1e1',
                            500: '#ef4444',
                            600: '#dc2626',
                            700: '#b91c1c',
                            800: '#991b1b',
                            900: '#7f1d1d',
                        }
                    }
                }
            }
        }
    </script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    <!-- DataTables JS/CSS with Responsive, Buttons (Excel, PDF, CSV, Print), FixedHeader -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Select2 JS/CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Dropzone JS/CSS for multiple image uploads -->
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />

    <!-- Custom DataTables and UI styling override -->
    <style>
        /* Select2 Premium Theme Overrides */
        .select2-container--default .select2-selection--single {
            background-color: rgb(248 250 252) !important;
            border: 1px solid rgb(226 232 240) !important;
            border-radius: 0.75rem !important;
            height: 40px !important;
            display: flex;
            align-items: center;
        }
        .dark .select2-container--default .select2-selection--single {
            background-color: rgba(2, 6, 23, 0.6) !important;
            border: 1px solid rgb(30 41 59) !important;
            color: white !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: rgb(30 41 59) !important;
            font-size: 0.875rem !important;
        }
        .dark .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: rgb(241 245 249) !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 38px !important;
        }
        .select2-dropdown {
            background-color: white !important;
            border: 1px solid rgb(226 232 240) !important;
            border-radius: 0.75rem !important;
            z-index: 9999 !important;
        }
        .dark .select2-dropdown {
            background-color: rgb(15 23 42) !important;
            border: 1px solid rgb(30 41 59) !important;
            color: white !important;
        }
        .select2-results__option {
            font-size: 0.875rem !important;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: rgb(220 38 38) !important;
        }

        /* Modern styling overrides for DataTables */
        .dataTables_wrapper .dataTables_length select {
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            padding: 0.25rem 1.5rem 0.25rem 0.5rem;
            background-color: #fff;
        }
        .dark .dataTables_wrapper .dataTables_length select {
            border-color: #334155;
            background-color: #1e293b;
            color: #fff;
        }
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            padding: 0.25rem 0.75rem;
            background-color: #fff;
            margin-left: 0.5rem;
        }
        .dark .dataTables_wrapper .dataTables_filter input {
            border-color: #334155;
            background-color: #1e293b;
            color: #fff;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #dc2626 !important;
            color: white !important;
            border: 1px solid #dc2626 !important;
            border-radius: 0.5rem !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #ef4444 !important;
            color: white !important;
            border: 1px solid #ef4444 !important;
            border-radius: 0.5rem !important;
        }
        table.dataTable {
            border-collapse: collapse !important;
            width: 100% !important;
            margin-top: 1rem !important;
            margin-bottom: 1rem !important;
        }
        table.dataTable thead th {
            border-bottom: 1px solid #e2e8f0 !important;
            background-color: #f8fafc;
            color: #475569;
            font-weight: 600;
        }
        .dark table.dataTable thead th {
            border-bottom: 1px solid #334155 !important;
            background-color: #0f172a;
            color: #94a3b8;
        }
        .dark table.dataTable tbody td {
            background-color: #1e293b;
            color: #e2e8f0;
            border-bottom: 1px solid #334155 !important;
        }
        table.dataTable.no-footer {
            border-bottom: 1px solid #e2e8f0 !important;
        }
        .dark table.dataTable.no-footer {
            border-bottom: 1px solid #334155 !important;
        }

        /* Collapsed Sidebar styles */
        #sidebar.collapsed {
            width: 5rem !important; /* w-20 equivalent */
        }
        #sidebar.collapsed .sidebar-text {
            display: none !important;
        }
        #sidebar.collapsed .logo-text {
            display: none !important;
        }
        #sidebar.collapsed .px-6 {
            padding-left: 0.75rem !important;
            padding-right: 0.75rem !important;
            justify-content: center !important;
        }
        #sidebar.collapsed nav a {
            justify-content: center !important;
            padding-left: 0.5rem !important;
            padding-right: 0.5rem !important;
        }
        #sidebar.collapsed nav a i {
            margin-right: 0 !important;
        }
        #sidebar.collapsed .p-4 {
            padding: 0.75rem !important;
        }
        #sidebar.collapsed .p-4 .flex {
            justify-content: center !important;
        }
    </style>

    <script>
        // Dark Mode Initialization
        if (localStorage.getItem('dark-mode') === 'true' || 
            (!('dark-mode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="h-full flex overflow-hidden font-sans">

    <!-- Sidebar (Desktop) -->
    <aside id="sidebar" class="hidden md:flex flex-col w-64 bg-slate-900 text-slate-100 flex-shrink-0 border-r border-slate-800 transition-all duration-300">
        <div class="h-16 flex items-center px-6 bg-slate-950 border-b border-slate-800 shrink-0 overflow-hidden">
            <a href="{{ url('/admin/dashboard') }}" class="flex items-center gap-2 shrink-0">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-red-800 to-red-600 flex items-center justify-center border border-amber-400/20 shrink-0">
                    <i class="fa-solid fa-crown text-amber-400 text-xs"></i>
                </div>
                <span class="logo-text font-extrabold tracking-tight text-white uppercase text-sm shrink-0">Royal Fish Admin</span>
            </a>
        </div>

        <nav class="flex-1 overflow-y-auto px-4 py-4 space-y-1">
            @php
                $route = Request::path();
                $hasPerm = function($perm) {
                    $user = Auth::user();
                    return $user && $user->hasPermission($perm);
                };
            @endphp

            <a href="{{ url('/admin/dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors {{ $route == 'admin/dashboard' ? 'bg-red-600 text-white shadow-md' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                <i class="fa-solid fa-chart-pie w-5"></i> <span class="sidebar-text">Dashboard</span>
            </a>

            @if($hasPerm('roles'))
            <a href="{{ url('/admin/roles') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors {{ $route == 'admin/roles' ? 'bg-red-600 text-white shadow-md' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                <i class="fa-solid fa-shield-halved w-5"></i> <span class="sidebar-text">Roles & Permissions</span>
            </a>
            @endif

            @if($hasPerm('users'))
            <a href="{{ url('/admin/users') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors {{ $route == 'admin/users' ? 'bg-red-600 text-white shadow-md' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                <i class="fa-solid fa-users-gear w-5"></i> <span class="sidebar-text">Customers & Staff</span>
            </a>
            @endif

            @if($hasPerm('categories'))
            <a href="{{ url('/admin/categories') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors {{ Str::startsWith($route, 'admin/categories') ? 'bg-red-600 text-white shadow-md' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                <i class="fa-solid fa-folder-tree w-5"></i> <span class="sidebar-text">Categories</span>
            </a>
            @endif

            @if($hasPerm('products'))
            <a href="{{ url('/admin/products') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors {{ Str::startsWith($route, 'admin/products') ? 'bg-red-600 text-white shadow-md' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                <i class="fa-solid fa-shrimp w-5"></i> <span class="sidebar-text">Products CRUD</span>
            </a>
            @endif

            @if($hasPerm('orders'))
            <a href="{{ url('/admin/orders') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors {{ Str::startsWith($route, 'admin/orders') ? 'bg-red-600 text-white shadow-md' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                <i class="fa-solid fa-truck-ramp-box w-5"></i> <span class="sidebar-text">Orders Tracking</span>
            </a>
            @endif

            @if($hasPerm('media'))
            <a href="{{ url('/admin/media') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors {{ $route == 'admin/media' ? 'bg-red-600 text-white shadow-md' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                <i class="fa-solid fa-photo-film w-5"></i> <span class="sidebar-text">Media Manager</span>
            </a>
            @endif

            @if($hasPerm('slides') || $hasPerm('*'))
            <a href="{{ url('/admin/slides') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors {{ Str::startsWith($route, 'admin/slides') ? 'bg-red-600 text-white shadow-md' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                <i class="fa-solid fa-images w-5"></i> <span class="sidebar-text">Hero Banners</span>
            </a>
            @endif

            @if($hasPerm('settings'))
            <a href="{{ url('/admin/settings') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors {{ $route == 'admin/settings' ? 'bg-red-600 text-white shadow-md' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                <i class="fa-solid fa-sliders w-5"></i> <span class="sidebar-text">Settings Module</span>
            </a>
            @endif
        </nav>

        <div class="p-4 border-t border-slate-800 bg-slate-950 shrink-0">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center font-bold text-red-500 uppercase border border-slate-700 shrink-0">
                    {{ substr(Auth::user()->name ?? 'A', 0, 2) }}
                </div>
                <div class="sidebar-text min-w-0 flex-1">
                    <p class="text-xs font-bold text-white truncate">{{ Auth::user()->name ?? 'Admin User' }}</p>
                    <p class="text-[10px] text-slate-500 truncate">{{ Auth::user()->role->name ?? 'Role' }}</p>
                </div>
            </div>
            <a href="{{ url('/admin/logout') }}" class="flex items-center justify-center gap-2 w-full py-2 bg-slate-800 hover:bg-red-700/20 hover:text-red-400 rounded-lg text-xs font-bold text-slate-400 transition-colors">
                <i class="fa-solid fa-right-from-bracket"></i> <span class="sidebar-text">Logout</span>
            </a>
        </div>
    </aside>

    <!-- Main View Frame -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        
        <!-- Header / Navbar -->
        <header class="h-16 flex items-center justify-between px-6 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 shrink-0">
            <div class="flex items-center gap-3">
                <button id="toggle-mobile-sidebar" class="md:hidden text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white">
                    <i class="fa-solid fa-bars-staggered text-xl"></i>
                </button>
                <button id="toggle-sidebar" class="hidden md:block text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white mr-1.5 focus:outline-none">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
                <h1 class="text-lg font-bold text-slate-800 dark:text-white truncate">@yield('page_title', 'Dashboard')</h1>
            </div>

            <div class="flex items-center gap-4">
                <!-- Theme Toggle Button -->
                <button id="theme-toggle" class="p-2 text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors">
                    <i class="fa-solid fa-moon dark:hidden text-lg"></i>
                    <i class="fa-solid fa-sun hidden dark:block text-amber-400 text-lg"></i>
                </button>

                <div class="h-8 w-px bg-slate-200 dark:bg-slate-800"></div>

                <!-- Profile Indicator -->
                <div class="flex items-center gap-3">
                    <span class="text-xs font-bold text-slate-700 dark:text-slate-300 hidden sm:block">Hi, {{ Auth::user()->name ?? 'Admin' }}</span>
                    <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center font-bold text-red-600 dark:text-red-400 uppercase">
                        {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content View -->
        <main class="flex-1 overflow-y-auto p-6 bg-slate-50 dark:bg-slate-950">
            @yield('content')
        </main>
    </div>

    <!-- Sidebar Backdrop for Mobile -->
    <div id="sidebar-backdrop" class="fixed inset-0 bg-slate-950/50 z-40 hidden md:hidden"></div>

    <!-- Mobile Drawer Sidebar -->
    <div id="mobile-sidebar" class="fixed inset-y-0 left-0 w-64 bg-slate-900 text-slate-100 z-50 transform -translate-x-full transition-transform duration-300 md:hidden flex flex-col">
        <div class="h-16 flex items-center justify-between px-6 bg-slate-950 border-b border-slate-800 shrink-0">
            <a href="{{ url('/admin/dashboard') }}" class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-red-800 to-red-600 flex items-center justify-center border border-amber-400/20">
                    <i class="fa-solid fa-crown text-amber-400 text-xs"></i>
                </div>
                <span class="font-extrabold tracking-tight text-white uppercase text-sm">Royal Fish Admin</span>
            </a>
            <button id="close-mobile-sidebar" class="text-slate-400 hover:text-white">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <nav class="flex-1 overflow-y-auto px-4 py-4 space-y-1">
            <a href="{{ url('/admin/dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors {{ $route == 'admin/dashboard' ? 'bg-red-600 text-white shadow-md' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                <i class="fa-solid fa-chart-pie w-5"></i> Dashboard
            </a>

            @if($hasPerm('roles'))
            <a href="{{ url('/admin/roles') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors {{ $route == 'admin/roles' ? 'bg-red-600 text-white shadow-md' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                <i class="fa-solid fa-shield-halved w-5"></i> Roles & Permissions
            </a>
            @endif

            @if($hasPerm('users'))
            <a href="{{ url('/admin/users') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors {{ $route == 'admin/users' ? 'bg-red-600 text-white shadow-md' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                <i class="fa-solid fa-users-gear w-5"></i> Customers & Staff
            </a>
            @endif

            @if($hasPerm('categories'))
            <a href="{{ url('/admin/categories') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors {{ Str::startsWith($route, 'admin/categories') ? 'bg-red-600 text-white shadow-md' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                <i class="fa-solid fa-folder-tree w-5"></i> Categories
            </a>
            @endif

            @if($hasPerm('products'))
            <a href="{{ url('/admin/products') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors {{ Str::startsWith($route, 'admin/products') ? 'bg-red-600 text-white shadow-md' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                <i class="fa-solid fa-shrimp w-5"></i> Products CRUD
            </a>
            @endif

            @if($hasPerm('orders'))
            <a href="{{ url('/admin/orders') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors {{ Str::startsWith($route, 'admin/orders') ? 'bg-red-600 text-white shadow-md' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                <i class="fa-solid fa-truck-ramp-box w-5"></i> Orders Tracking
            </a>
            @endif

            @if($hasPerm('media'))
            <a href="{{ url('/admin/media') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors {{ $route == 'admin/media' ? 'bg-red-600 text-white shadow-md' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                <i class="fa-solid fa-photo-film w-5"></i> Media Manager
            </a>
            @endif

            @if($hasPerm('slides') || $hasPerm('*'))
            <a href="{{ url('/admin/slides') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors {{ Str::startsWith($route, 'admin/slides') ? 'bg-red-600 text-white shadow-md' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                <i class="fa-solid fa-images w-5"></i> Hero Banners
            </a>
            @endif

            @if($hasPerm('settings'))
            <a href="{{ url('/admin/settings') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors {{ $route == 'admin/settings' ? 'bg-red-600 text-white shadow-md' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                <i class="fa-solid fa-sliders w-5"></i> Settings Module
            </a>
            @endif
        </nav>

        <div class="p-4 border-t border-slate-800 bg-slate-950 shrink-0">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center font-bold text-red-500 uppercase border border-slate-700">
                    {{ substr(Auth::user()->name ?? 'A', 0, 2) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-bold text-white truncate">{{ Auth::user()->name ?? 'Admin User' }}</p>
                    <p class="text-[10px] text-slate-500 truncate">{{ Auth::user()->role->name ?? 'Role' }}</p>
                </div>
            </div>
            <a href="{{ url('/admin/logout') }}" class="flex items-center justify-center gap-2 w-full py-2 bg-slate-800 hover:bg-red-700/20 hover:text-red-400 rounded-lg text-xs font-bold text-slate-400 transition-colors">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
        </div>
    </div>

    <!-- UI Scripts -->
    <script>
        $(document).ready(function() {

            // Toggle Sidebar (Desktop)
            $('#toggle-sidebar').click(function() {
                $('#sidebar').toggleClass('collapsed');
            });

            // Mobile Sidebar drawer opening/closing
            $('#toggle-mobile-sidebar').click(function() {
                $('#mobile-sidebar').removeClass('-translate-x-full');
                $('#sidebar-backdrop').removeClass('hidden');
            });

            $('#close-mobile-sidebar, #sidebar-backdrop').click(function() {
                $('#mobile-sidebar').addClass('-translate-x-full');
                $('#sidebar-backdrop').addClass('hidden');
            });

            // Dark Mode Toggling
            $('#theme-toggle').click(function() {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('dark-mode', 'false');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('dark-mode', 'true');
                }
            });
        });
    </script>
    @yield('scripts')
</body>
</html>
