<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0, viewport-fit=cover" name="viewport"/>
    <title>@yield('title', 'Connexio - Dashboard Teknisi')</title>
    <!-- Material Symbols -->
    <link rel="stylesheet" href="{{ asset('css/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('css/material-symbols.css') }}">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "tertiary-container": "#5e6b7b",
                        "tertiary-fixed": "#d6e4f7",
                        "secondary-fixed": "#d4e4fc",
                        "secondary-container": "#d1e1fa",
                        "surface-container-low": "#f1f4f6",
                        "on-primary-fixed-variant": "#004881",
                        "outline": "#727782",
                        "on-tertiary-fixed-variant": "#3b4857",
                        "surface-container": "#ebeef0",
                        "secondary": "#515f74",
                        "error-container": "#ffdad6",
                        "primary": "#005394",
                        "on-primary-fixed": "#001c38",
                        "on-secondary-container": "#556479",
                        "on-error": "#ffffff",
                        "on-secondary": "#ffffff",
                        "on-error-container": "#93000a",
                        "surface-dim": "#d7dadc",
                        "on-tertiary-container": "#dfecff",
                        "on-tertiary": "#ffffff",
                        "primary-fixed-dim": "#a2c9ff",
                        "on-background": "#181c1e",
                        "primary-container": "#2b6cb0",
                        "surface": "#f7fafc",
                        "on-tertiary-fixed": "#0f1d2a",
                        "primary-fixed": "#d3e4ff",
                        "on-surface-variant": "#414750",
                        "surface-tint": "#1960a3",
                        "inverse-on-surface": "#eef1f3",
                        "surface-container-lowest": "#ffffff",
                        "surface-variant": "#e0e3e5",
                        "on-surface": "#181c1e",
                        "surface-bright": "#f7fafc",
                        "tertiary": "#465363",
                        "surface-container-highest": "#e0e3e5",
                        "tertiary-fixed-dim": "#bac8da",
                        "surface-container-high": "#e5e9eb",
                        "inverse-primary": "#a2c9ff",
                        "on-secondary-fixed": "#0d1c2e",
                        "secondary-fixed-dim": "#b8c8e0",
                        "error": "#ba1a1a",
                        "inverse-surface": "#2d3133",
                        "on-primary-container": "#e1ecff",
                        "on-primary": "#ffffff",
                        "background": "#f7fafc",
                        "outline-variant": "#c1c7d2",
                        "on-secondary-fixed-variant": "#39485c"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "spacing": {
                        "margin-desktop": "48px",
                        "lg": "40px",
                        "sm": "12px",
                        "xs": "4px",
                        "xl": "64px",
                        "base": "8px",
                        "md": "24px",
                        "gutter": "24px",
                        "margin-mobile": "16px"
                    },
                    "fontFamily": {
                        "title-lg": ["Inter"],
                        "headline-lg-mobile": ["Inter"],
                        "label-sm": ["Inter"],
                        "body-lg": ["Inter"],
                        "headline-lg": ["Inter"],
                        "body-md": ["Inter"],
                        "headline-md": ["Inter"],
                        "label-md": ["Inter"],
                        "display-lg": ["Inter"]
                    },
                    "fontSize": {
                        "title-lg": ["20px", {"lineHeight": "28px", "fontWeight": "500"}],
                        "headline-lg-mobile": ["24px", {"lineHeight": "32px", "fontWeight": "600"}],
                        "label-sm": ["12px", {"lineHeight": "16px", "letterSpacing": "0.02em", "fontWeight": "500"}],
                        "body-lg": ["18px", {"lineHeight": "28px", "fontWeight": "400"}],
                        "headline-lg": ["32px", {"lineHeight": "40px", "letterSpacing": "-0.01em", "fontWeight": "600"}],
                        "body-md": ["16px", {"lineHeight": "24px", "fontWeight": "400"}],
                        "headline-md": ["24px", {"lineHeight": "32px", "fontWeight": "600"}],
                        "label-md": ["14px", {"lineHeight": "20px", "letterSpacing": "0.01em", "fontWeight": "600"}],
                        "display-lg": ["48px", {"lineHeight": "56px", "letterSpacing": "-0.02em", "fontWeight": "700"}]
                    }
                },
            },
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            display: inline-block;
            vertical-align: middle;
        }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        
        .executive-shadow {
            box-shadow: 0px 4px 20px rgba(0, 83, 148, 0.08);
        }

        .accordion-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out, padding 0.3s ease;
        }
        .accordion-content.active {
            max-height: 800px;
            padding-top: 16px;
            padding-bottom: 16px;
        }
        
        /* Modal bottom sheet design */
        .glass-overlay {
            backdrop-filter: blur(8px);
            background-color: rgba(24, 28, 30, 0.4);
            transition: opacity 0.3s ease-in-out;
        }
        .bottom-sheet {
            transform: translateY(100%);
            transition: transform 0.4s cubic-bezier(0.33, 1, 0.68, 1);
        }
        .bottom-sheet.active {
            transform: translateY(0);
        }
    </style>
    @yield('styles')
</head>
<body class="bg-surface text-on-surface font-body-md min-h-screen pb-32">

<!-- TopAppBar -->
@if(Auth::user()->role === 'Teknisi')
<header class="fixed top-0 left-0 w-full z-50 px-5 py-4 bg-[#005394] rounded-b-[30px] shadow-lg shadow-primary/20">
    <div class="flex justify-between items-center max-w-7xl mx-auto">
        <!-- Left: Profile -->
        <div class="flex items-center gap-3">
            <div class="w-[42px] h-[42px] rounded-full border-[2px] border-[#2b7bc6] overflow-hidden bg-slate-200 flex-shrink-0 shadow-inner">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->nama_jelas ?? 'Teknisi') }}&background=e2e8f0&color=005394&bold=true&size=100" alt="Avatar" class="w-full h-full object-cover">
            </div>
            <div class="flex flex-col justify-center">
                <span class="text-[13px] text-white font-bold leading-tight">{{ Auth::user()->nama_jelas }}</span>
                <span class="text-[10px] text-blue-200/90 mt-0.5 font-medium">Area: Jakarta Pusat</span>
            </div>
        </div>
        
        <!-- Right: Logo and Icons -->
        <div class="flex items-center gap-3">
            <span class="text-[13px] font-bold text-white tracking-wide">Connexio</span>
            
            <div class="flex items-center gap-1.5">
                <button type="button" class="w-[34px] h-[34px] flex items-center justify-center rounded-full bg-[#1c6aa6] text-white hover:bg-white/20 transition-all shadow-inner" title="Notifikasi">
                    <span class="material-symbols-outlined text-[18px]">notifications</span>
                </button>
                <form action="{{ route('logout') }}" method="POST" style="margin: 0;" class="flex items-center">
                    @csrf
                    <button type="submit" class="w-[34px] h-[34px] flex items-center justify-center rounded-full bg-red-500/80 text-white hover:bg-red-600 transition-all shadow-inner" title="Logout">
                        <span class="material-symbols-outlined text-[16px] ml-0.5">logout</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
@else
<header class="fixed top-0 left-0 w-full z-50 px-margin-mobile py-4 bg-white border-b border-surface-container-high">
    <div class="flex justify-between items-center max-w-7xl mx-auto">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full border border-outline-variant overflow-hidden bg-primary/10 text-primary flex items-center justify-center font-bold">
                {{ strtoupper(substr(Auth::user()->nama_jelas ?? 'A', 0, 1)) }}
            </div>
            <div class="flex flex-col">
                <span class="font-label-md text-label-md leading-tight text-on-surface font-semibold">{{ Auth::user()->nama_jelas }}</span>
                <span class="text-[11px] text-on-surface-variant uppercase">{{ Auth::user()->role }}</span>
            </div>
        </div>
        <div class="flex items-center gap-1">
            <span class="font-headline-md font-bold text-primary tracking-tight">Connexio</span>
        </div>
        <div class="flex items-center gap-2">
            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="w-10 h-10 flex items-center justify-center rounded-full text-on-surface-variant hover:bg-surface-container-high transition-colors" title="Logout">
                    <span class="material-symbols-outlined">logout</span>
                </button>
            </form>
        </div>
    </div>
</header>
@endif

<!-- Main content -->
<main class="mt-24 px-margin-mobile md:px-margin-desktop space-y-lg max-w-7xl mx-auto">
    <!-- Success/Error alert alerts with SweetAlert2 -->
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

<!-- Modals Section (Outside of main space-y wrapper to prevent margin bugs on fixed elements) -->
@yield('modals')

<!-- BottomNavBar -->
<nav class="fixed bottom-0 left-0 w-full z-40 flex justify-around items-center px-4 py-3 pb-safe bg-surface-container-lowest border-t border-outline-variant">
    <a class="flex flex-col items-center justify-center px-4 py-2 rounded-xl transition-colors {{ request()->routeIs('teknisi.dashboard') ? 'bg-secondary-container text-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" 
       href="{{ route('teknisi.dashboard') }}">
        <span class="material-symbols-outlined">dashboard</span>
        <span class="font-label-sm text-label-sm">Dashboard</span>
    </a>
    <button class="flex flex-col items-center justify-center text-secondary px-4 py-2 hover:bg-surface-container-high rounded-xl transition-colors" 
       onclick="openTechModal('request-modal')">
        <span class="material-symbols-outlined">inventory_2</span>
        <span class="font-label-sm text-label-sm">Request</span>
    </button>
    <button class="flex flex-col items-center justify-center text-secondary px-4 py-2 hover:bg-surface-container-high rounded-xl transition-colors" 
       onclick="openTechModal('return-modal')">
        <span class="material-symbols-outlined">assignment_return</span>
        <span class="font-label-sm text-label-sm">Return</span>
    </button>
    <button class="flex flex-col items-center justify-center text-secondary px-4 py-2 hover:bg-surface-container-high rounded-xl transition-colors" 
       onclick="openTechModal('dismantle-modal')">
        <span class="material-symbols-outlined">build</span>
        <span class="font-label-sm text-label-sm">Dismantle</span>
    </button>
</nav>

@yield('scripts')
</body>
</html>
