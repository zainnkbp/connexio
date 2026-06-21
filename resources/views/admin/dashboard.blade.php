@extends('layouts.app')

@section('title', 'Connexio - Executive Dashboard')

@section('content')
<!-- Page Header -->
<header class="mb-lg flex justify-between items-end border-b border-outline-variant/10 pb-4">
    <div>
        <p class="font-label-caps text-xs text-secondary uppercase tracking-widest mb-1">Enterprise Intelligence</p>
        <h1 class="text-3xl font-black text-primary tracking-tight">Operational EWS Dashboard</h1>
    </div>
    <div class="flex gap-2">
        <button class="flex items-center gap-1 px-4 py-2 border border-outline-variant rounded-lg text-xs font-semibold text-on-surface-variant hover:bg-slate-50 transition-all">
            <span class="material-symbols-outlined text-[18px]">calendar_today</span>
            Real-Time
        </button>
    </div>
</header>

<!-- Bento Grid Stats Card -->
<section class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Pending Card -->
    <div class="glass-card p-6 rounded-xl shadow-[0px_4px_20px_rgba(30,58,138,0.03)] hover:-translate-y-0.5 transition-all group border-t-4 border-yellow-500">
        <div class="flex justify-between items-start mb-4">
            <div class="w-12 h-12 rounded-lg bg-yellow-50 flex items-center justify-center text-yellow-600">
                <span class="material-symbols-outlined text-2xl">pending_actions</span>
            </div>
            <a href="{{ route('admin.approvals.index') }}" class="text-secondary material-symbols-outlined opacity-0 group-hover:opacity-100 transition-opacity">arrow_forward</a>
        </div>
        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Persetujuan Pending</h3>
        <div class="text-4xl font-extrabold text-primary mb-2">{{ sprintf("%02d", $pendingCount) }}</div>
        <div class="flex items-center gap-1.5 text-xs text-yellow-600 font-semibold">
            <span class="w-2 h-2 rounded-full bg-yellow-500 animate-pulse"></span>
            Menunggu Tindakan Admin
        </div>
    </div>

    <!-- In Hand Card -->
    <div class="glass-card p-6 rounded-xl shadow-[0px_4px_20px_rgba(30,58,138,0.03)] hover:-translate-y-0.5 transition-all group border-t-4 border-blue-500">
        <div class="flex justify-between items-start mb-4">
            <div class="w-12 h-12 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                <span class="material-symbols-outlined text-2xl">engineering</span>
            </div>
            <span class="text-secondary material-symbols-outlined opacity-0 group-hover:opacity-100 transition-opacity">arrow_forward</span>
        </div>
        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Perangkat In Hand</h3>
        <div class="text-4xl font-extrabold text-primary mb-2">{{ sprintf("%02d", $inHandCount) }}</div>
        <div class="flex items-center gap-1.5 text-xs text-blue-600 font-semibold">
            <span class="w-2 h-2 rounded-full bg-blue-500"></span>
            Sedang Dibawa Teknisi
        </div>
    </div>

    <!-- Completed Card -->
    <div class="glass-card p-6 rounded-xl shadow-[0px_4px_20px_rgba(30,58,138,0.03)] hover:-translate-y-0.5 transition-all group border-t-4 border-green-500">
        <div class="flex justify-between items-start mb-4">
            <div class="w-12 h-12 rounded-lg bg-green-50 flex items-center justify-center text-green-600">
                <span class="material-symbols-outlined text-2xl">task_alt</span>
            </div>
            <span class="text-secondary material-symbols-outlined opacity-0 group-hover:opacity-100 transition-opacity">arrow_forward</span>
        </div>
        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Penugasan Selesai</h3>
        <div class="text-4xl font-extrabold text-primary mb-2">{{ sprintf("%02d", $completedCount) }}</div>
        <div class="flex items-center gap-1.5 text-xs text-green-600 font-semibold">
            <span class="w-2 h-2 rounded-full bg-green-500"></span>
            Siklus Pemasangan Selesai
        </div>
    </div>
</section>

<!-- Visual Trends Section -->
<section class="mb-8">
    <div class="glass-card rounded-xl p-6 shadow-[0px_4px_20px_rgba(30,58,138,0.03)]">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-lg font-bold text-primary">Deployment vs. Return Trends</h2>
                <p class="text-xs text-slate-400">Enterprise aggregate asset velocity (12 Months)</p>
            </div>
            <div class="flex gap-4 items-center">
                <div class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-primary"></span>
                    <span class="text-xs text-on-surface-variant">Deployments</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-secondary"></span>
                    <span class="text-xs text-on-surface-variant">Returns</span>
                </div>
            </div>
        </div>
        
        <!-- Mock Chart SVG -->
        <div class="h-[200px] w-full relative overflow-hidden border-b border-outline-variant/10">
            <svg class="w-full h-full" viewBox="0 0 1000 200" preserveAspectRatio="none">
                <!-- Deployment Line -->
                <path d="M0,150 Q100,120 200,140 T400,80 T600,60 T800,40 T1000,20" fill="none" stroke="#00236f" stroke-linecap="round" stroke-width="4" id="chart-path-1"></path>
                <!-- Return Line -->
                <path d="M0,180 Q100,170 200,175 T400,150 T600,140 T800,145 T1000,130" fill="none" stroke="#1960a3" stroke-dasharray="8 4" stroke-linecap="round" stroke-width="4" id="chart-path-2"></path>
                <!-- Gradient Fill for Deployment -->
                <path d="M0,150 Q100,120 200,140 T400,80 T600,60 T800,40 T1000,20 V200 H0 Z" fill="url(#blue-grad)" opacity="0.08"></path>
                <defs>
                    <linearGradient id="blue-grad" x1="0%" x2="0%" y1="0%" y2="100%">
                        <stop offset="0%" style="stop-color:#00236f;stop-opacity:1"></stop>
                        <stop offset="100%" style="stop-color:#00236f;stop-opacity:0"></stop>
                    </linearGradient>
                </defs>
            </svg>
            <div class="flex justify-between mt-2 text-[10px] text-slate-400 font-bold uppercase tracking-wider">
                <span>Jan</span><span>Feb</span><span>Mar</span><span>Apr</span><span>May</span><span>Jun</span><span>Jul</span><span>Aug</span><span>Sep</span><span>Oct</span><span>Nov</span><span>Dec</span>
            </div>
        </div>
    </div>
</section>

<!-- Early Warning System Section -->
<section class="glass-card rounded-xl overflow-hidden shadow-[0px_4px_20px_rgba(30,58,138,0.03)] border border-slate-100">
    <div class="p-6 border-b border-outline-variant/10 flex justify-between items-center bg-white">
        <div>
            <h2 class="text-lg font-bold text-primary">Early Warning System (Umur Perangkat Lapangan)</h2>
            <p class="text-xs text-slate-400">Menyorot perangkat terpasang dengan durasi operasional > 3 tahun (36 bulan)</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="w-4 h-4 bg-[#FFFDF5] border border-amber-300 rounded"></span>
            <span class="text-xs text-slate-500 font-semibold">Umur Perangkat > 3 Tahun</span>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-slate-50/50">
                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Serial Number</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Jenis & Merek</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Tipe</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Pelanggan</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Teknisi</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Tanggal Pasang</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider text-right">Durasi Pakai</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($devices as $device)
                    @php
                        $isWarning = $device->months_total >= 36;
                    @endphp
                    <tr class="hover:bg-slate-50/50 transition-colors {{ $isWarning ? 'warning-row' : '' }}">
                        <td class="px-6 py-4 font-mono text-sm font-bold text-primary">{{ $device->serial_number }}</td>
                        <td class="px-6 py-4 text-sm font-semibold text-slate-700">{{ $device->jenis_merek }}</td>
                        <td class="px-6 py-4 text-xs"><code class="bg-slate-100 px-2 py-1 rounded text-slate-600 font-bold font-mono">{{ $device->tipe_perangkat }}</code></td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800">
                                {{ $device->status_kondisi }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($device->customer)
                                <div class="text-sm font-bold text-slate-800">{{ $device->customer->nama_pelanggan }}</div>
                                <div class="text-[11px] text-slate-400 font-semibold">{{ $device->customer->id_pelanggan }} | {{ $device->customer->no_telepon }}</div>
                            @else
                                <span class="text-xs text-slate-400 italic">Tidak terpasang</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">
                            {{ $device->teknisi->nama_jelas ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-500 font-semibold">
                            {{ $device->tanggal_pasang_awal ? \Carbon\Carbon::parse($device->tanggal_pasang_awal)->format('d M Y H:i') : '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm font-extrabold text-right {{ $isWarning ? 'text-amber-700' : 'text-primary' }}">
                            {{ $device->durasi_pakai }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-slate-400 py-12 text-sm">
                            Tidak ada perangkat terpasang di lapangan saat ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection

@section('scripts')
<script>
    // Animation for SVG lines on load
    window.addEventListener('load', () => {
        const path1 = document.getElementById('chart-path-1');
        const path2 = document.getElementById('chart-path-2');
        [path1, path2].forEach(path => {
            if (path) {
                const length = path.getTotalLength();
                path.style.strokeDasharray = length;
                path.style.strokeDashoffset = length;
                path.style.transition = 'stroke-dashoffset 2s ease-out';
                setTimeout(() => {
                    path.style.strokeDashoffset = '0';
                }, 100);
            }
        });
    });
</script>
@endsection
