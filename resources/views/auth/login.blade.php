<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Connexio - Executive Portal Login</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link rel="stylesheet" href="{{ asset('css/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('css/material-symbols.css') }}">
    <script id="tailwind-config">
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#00236f",
                        secondary: "#1960a3",
                        background: "#0b1c30",
                        surface: "#1f2d3d",
                        outline: "#444651"
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-background min-h-screen flex items-center justify-center p-4">

<div class="w-full max-w-md bg-white rounded-2xl shadow-[0_20px_50px_rgba(0,35,111,0.3)] border border-slate-100 overflow-hidden">
    <!-- Header banner -->
    <div class="bg-gradient-to-br from-primary to-secondary p-8 text-center text-white relative">
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-white via-slate-900 to-slate-900"></div>
        <div class="relative z-10">
            <span class="material-symbols-outlined text-4xl mb-2">account_balance</span>
            <h1 class="text-3xl font-extrabold tracking-tight">Connexio</h1>
            <p class="text-xs text-slate-200 mt-1 uppercase tracking-wider">Lifecycle Tracking Portal</p>
        </div>
    </div>

    <!-- Form container -->
    <div class="p-8">
        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-xs font-semibold flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">error</span>
                <div>{{ $errors->first() }}</div>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Username Field -->
            <div class="space-y-1">
                <label for="username" class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Username</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-lg">person</span>
                    <input type="text" id="username" name="username" required autofocus value="{{ old('username') }}"
                           class="w-full h-11 pl-10 pr-4 rounded-xl border border-slate-200 focus:border-secondary focus:ring-4 focus:ring-secondary/15 text-sm transition-all outline-none"
                           placeholder="Enter your username">
                </div>
            </div>

            <!-- Password Field -->
            <div class="space-y-1">
                <label for="password" class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Password</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-lg">lock</span>
                    <input type="password" id="password" name="password" required
                           class="w-full h-11 pl-10 pr-4 rounded-xl border border-slate-200 focus:border-secondary focus:ring-4 focus:ring-secondary/15 text-sm transition-all outline-none"
                           placeholder="Enter your password">
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                    class="w-full h-12 bg-primary text-white rounded-xl font-bold text-sm tracking-wide shadow-lg shadow-primary/20 hover:bg-secondary transition-all active:scale-[0.98]">
                Sign In to Account
            </button>
        </form>

        <!-- Credentials Demo Helper -->
        <div class="mt-8 pt-6 border-t border-slate-100 text-center">
            <p class="text-xs text-slate-400 font-semibold mb-2">Default demo accounts:</p>
            <div class="grid grid-cols-2 gap-2 text-[11px]">
                <div class="bg-slate-50 p-2 rounded-lg border border-slate-100">
                    <span class="font-bold text-primary block">Admin</span>
                    <code>admin</code> / <code>password</code>
                </div>
                <div class="bg-slate-50 p-2 rounded-lg border border-slate-100">
                    <span class="font-bold text-primary block">Technician</span>
                    <code>teknisi</code> / <code>password</code>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
