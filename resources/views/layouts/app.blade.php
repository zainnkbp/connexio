<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>@yield('title', 'Connexio Executive Console')</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link rel="icon" type="image/png" href="{{ asset('logo') }}/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="{{ asset('logo') }}/favicon.svg" />
    <link rel="shortcut icon" href="{{ asset('logo') }}/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('logo') }}/apple-touch-icon.png" />
    <link rel="manifest" href="{{ asset('logo') }}/site.webmanifest" />
    <link rel="stylesheet" href="{{ asset('css/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('css/material-symbols.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "on-tertiary-container": "#ef9900",
                        "on-surface-variant": "#444651",
                        "inverse-surface": "#213145",
                        "surface-container-low": "#eff4ff",
                        "on-primary-fixed-variant": "#264191",
                        "on-tertiary-fixed": "#2a1700",
                        "secondary-fixed-dim": "#a2c9ff",
                        "on-background": "#0b1c30",
                        "primary-container": "#1e3a8a",
                        "on-tertiary": "#ffffff",
                        "on-surface": "#0b1c30",
                        "on-secondary-container": "#00477f",
                        "error-container": "#ffdad6",
                        "secondary-container": "#7db6ff",
                        "tertiary-container": "#5c3800",
                        "outline": "#757682",
                        "on-primary": "#ffffff",
                        "tertiary-fixed": "#ffddb8",
                        "surface-container-lowest": "#ffffff",
                        "error": "#ba1a1a",
                        "outline-variant": "#c5c5d3",
                        "on-secondary-fixed": "#001c38",
                        "surface": "#f8f9ff",
                        "secondary": "#1960a3",
                        "on-tertiary-fixed-variant": "#653e00",
                        "surface-bright": "#f8f9ff",
                        "inverse-primary": "#b6c4ff",
                        "secondary-fixed": "#d3e4ff",
                        "surface-variant": "#d3e4fe",
                        "on-primary-fixed": "#00164e",
                        "primary-fixed": "#dce1ff",
                        "on-error-container": "#93000a",
                        "on-error": "#ffffff",
                        "surface-tint": "#4059aa",
                        "inverse-on-surface": "#eaf1ff",
                        "background": "#f8f9ff",
                        "surface-dim": "#cbdbf5",
                        "primary": "#00236f",
                        "surface-container": "#e5eeff",
                        "surface-container-highest": "#d3e4fe",
                        "primary-fixed-dim": "#b6c4ff",
                        "on-secondary-fixed-variant": "#004881",
                        "on-secondary": "#ffffff",
                        "tertiary-fixed-dim": "#ffb95f",
                        "on-primary-container": "#90a8ff",
                        "tertiary": "#3e2400",
                        "surface-container-high": "#dce9ff"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "spacing": {
                        "sm": "16px",
                        "md": "24px",
                        "base": "4px",
                        "xl": "48px",
                        "lg": "32px",
                        "xs": "8px",
                        "container-max": "1440px",
                        "gutter": "24px"
                    },
                    "fontFamily": {
                        "title-md": ["Inter"],
                        "label-caps": ["Inter"],
                        "body-lg": ["Inter"],
                        "body-sm": ["Inter"],
                        "headline-lg-mobile": ["Inter"],
                        "display-lg": ["Inter"],
                        "mono-data": ["JetBrains Mono"],
                        "headline-lg": ["Inter"]
                    },
                    "fontSize": {
                        "title-md": ["20px", {"lineHeight": "28px", "letterSpacing": "0em", "fontWeight": "600"}],
                        "label-caps": ["12px", {"lineHeight": "16px", "letterSpacing": "0.05em", "fontWeight": "600"}],
                        "body-lg": ["16px", {"lineHeight": "24px", "letterSpacing": "0em", "fontWeight": "400"}],
                        "body-sm": ["14px", {"lineHeight": "20px", "letterSpacing": "0em", "fontWeight": "400"}],
                        "headline-lg-mobile": ["24px", {"lineHeight": "32px", "letterSpacing": "-0.01em", "fontWeight": "600"}],
                        "display-lg": ["48px", {"lineHeight": "56px", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                        "mono-data": ["14px", {"lineHeight": "20px", "letterSpacing": "-0.01em", "fontWeight": "400"}],
                        "headline-lg": ["32px", {"lineHeight": "40px", "letterSpacing": "-0.01em", "fontWeight": "600"}]
                    }
                }
            }
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            display: inline-block;
            vertical-align: middle;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .chart-gradient {
            background: linear-gradient(180deg, rgba(30, 58, 138, 0.08) 0%, rgba(30, 58, 138, 0) 100%);
        }
        .warning-row {
            background-color: #FFFDF5 !important;
            border-left: 4px solid #D69E2E !important;
        }
    </style>
    @yield('styles')
</head>
<body class="bg-background text-on-surface font-body-lg antialiased">

<!-- SideNavBar -->
<aside class="w-[72px] h-screen fixed left-0 top-0 bg-surface/70 backdrop-blur-xl border-r border-outline-variant/30 shadow-[0px_4px_20px_rgba(30,58,138,0.05)] flex flex-col items-center py-lg z-50">
   <div class="mb-xl">
        <img src="{{ asset('logo') }}/favicon.svg" alt="Description of the image">
    </div>
    <nav class="flex flex-col gap-sm flex-1">
        <!-- Dashboard EWS -->
        <a href="{{ route('admin.dashboard') }}" 
           class="w-12 h-12 flex items-center justify-center rounded-lg transition-all duration-300 ease-in-out hover:-translate-y-0.5 {{ request()->routeIs('admin.dashboard') ? 'text-primary border-r-2 border-primary bg-primary/5' : 'text-on-surface-variant/60 hover:bg-primary/5 hover:text-primary' }}"
           title="Dashboard EWS">
            <span class="material-symbols-outlined">dashboard</span>
        </a>
        <!-- Persetujuan -->
        <a href="{{ route('admin.approvals.index') }}" 
           class="w-12 h-12 flex items-center justify-center rounded-lg transition-all duration-300 ease-in-out hover:-translate-y-0.5 {{ request()->routeIs('admin.approvals.index') ? 'text-primary border-r-2 border-primary bg-primary/5' : 'text-on-surface-variant/60 hover:bg-primary/5 hover:text-primary' }}"
           title="Persetujuan Transaksi">
            <span class="material-symbols-outlined">assignment_turned_in</span>
        </a>
        <!-- Pool Perangkat -->
        <a href="{{ route('admin.devices.index') }}" 
           class="w-12 h-12 flex items-center justify-center rounded-lg transition-all duration-300 ease-in-out hover:-translate-y-0.5 {{ request()->routeIs('admin.devices.index') ? 'text-primary border-r-2 border-primary bg-primary/5' : 'text-on-surface-variant/60 hover:bg-primary/5 hover:text-primary' }}"
           title="Pool Perangkat Gudang">
            <span class="material-symbols-outlined">inventory_2</span>
        </a>
        <!-- Pelanggan -->
        <a href="{{ route('admin.customers.index') }}" 
           class="w-12 h-12 flex items-center justify-center rounded-lg transition-all duration-300 ease-in-out hover:-translate-y-0.5 {{ request()->routeIs('admin.customers.index') ? 'text-primary border-r-2 border-primary bg-primary/5' : 'text-on-surface-variant/60 hover:bg-primary/5 hover:text-primary' }}"
           title="Manajemen Pelanggan">
            <span class="material-symbols-outlined">group</span>
        </a>
        <!-- Pengguna (Admin) -->
        <a href="{{ route('admin.users.index') }}" 
           class="w-12 h-12 flex items-center justify-center rounded-lg transition-all duration-300 ease-in-out hover:-translate-y-0.5 {{ request()->routeIs('admin.users.*') ? 'text-primary border-r-2 border-primary bg-primary/5' : 'text-on-surface-variant/60 hover:bg-primary/5 hover:text-primary' }}"
           title="Kelola Pengguna">
            <span class="material-symbols-outlined">manage_accounts</span>
        </a>
    </nav>
</aside>

<!-- TopAppBar -->
<header class="fixed top-0 left-[72px] right-0 h-16 bg-surface/80 backdrop-blur-md border-b border-outline-variant/20 z-40 flex justify-between items-center px-lg">
    <div class="flex items-center gap-lg">
        <span class="font-title-md text-title-md font-black text-primary">Connexio</span>
        <div class="flex gap-md">
            <a class="{{ request()->routeIs('admin.dashboard') ? 'text-primary font-bold' : 'text-on-surface-variant/80' }} font-body-lg text-body-lg hover:text-primary transition-colors duration-200" href="{{ route('admin.dashboard') }}">Dashboard</a>
            <a class="{{ request()->routeIs('admin.approvals.index') ? 'text-primary font-bold' : 'text-on-surface-variant/80' }} font-body-lg text-body-lg hover:text-primary transition-colors duration-200" href="{{ route('admin.approvals.index') }}">Persetujuan</a>
            <a class="{{ request()->routeIs('admin.devices.index') ? 'text-primary font-bold' : 'text-on-surface-variant/80' }} font-body-lg text-body-lg hover:text-primary transition-colors duration-200" href="{{ route('admin.devices.index') }}">Pool Perangkat</a>
            <a class="{{ request()->routeIs('admin.customers.index') ? 'text-primary font-bold' : 'text-on-surface-variant/80' }} font-body-lg text-body-lg hover:text-primary transition-colors duration-200" href="{{ route('admin.customers.index') }}">Pelanggan</a>
            <a class="{{ request()->routeIs('admin.users.*') ? 'text-primary font-bold' : 'text-on-surface-variant/80' }} font-body-lg text-body-lg hover:text-primary transition-colors duration-200" href="{{ route('admin.users.index') }}">Pengguna</a>
        </div>
    </div>
    
    <div class="flex items-center gap-lg">
        <div class="flex items-center gap-xs">
            <div class="text-right hidden sm:block">
                <p class="font-label-caps text-label-caps text-primary leading-tight">{{ Auth::user()->nama_jelas }}</p>
                <p class="text-[10px] text-on-surface-variant/70 uppercase tracking-tighter">{{ Auth::user()->role }}</p>
            </div>
            <div class="w-10 h-10 rounded-full border-2 border-primary/20 bg-primary/10 text-primary flex items-center justify-center font-bold">
                {{ strtoupper(substr(Auth::user()->nama_jelas ?? 'A', 0, 1)) }}
            </div>
        </div>
        <div class="h-8 w-[1px] bg-outline-variant/30"></div>
        <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
            @csrf
            <button type="submit" class="text-on-surface-variant hover:text-primary transition-all p-2 flex items-center" title="Logout">
                <span class="material-symbols-outlined">logout</span>
            </button>
        </form>
    </div>
</header>

<!-- Main Content Wrapper -->
<main class="ml-[72px] mt-16 p-lg max-w-container-max mx-auto">
    <!-- Notifications / Alerts with SweetAlert2 -->
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#005394'
                });
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#ba1a1a'
                });
            });
        </script>
    @endif

    @if($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan Input',
                    html: `
                        <ul style="text-align: left; margin-left: 20px;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    `,
                    confirmButtonColor: '#ba1a1a'
                });
            });
        </script>
    @endif

    @yield('content')
</main>

@yield('scripts')
</body>
</html>
