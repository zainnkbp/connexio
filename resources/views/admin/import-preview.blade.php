@extends('layouts.app')

@section('title', 'Connexio - Preview Hasil Import')

@section('content')
<!-- Page Header -->
<header class="mb-lg flex justify-between items-end border-b border-outline-variant/10 pb-4">
    <div>
        <p class="font-label-caps text-xs text-secondary uppercase tracking-widest mb-1">Conflict Resolution</p>
        <h1 class="text-3xl font-black text-primary tracking-tight">Preview & Resolusi Konflik Import</h1>
    </div>
</header>

<!-- Summary Stats Cards -->
<section class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="glass-card p-6 bg-white border border-slate-100 rounded-xl shadow-sm text-center">
        <div class="text-3xl font-extrabold text-slate-800">{{ $total_rows }}</div>
        <div class="text-xs text-slate-400 font-bold uppercase tracking-wider mt-1">Total Baris File</div>
    </div>
    <div class="glass-card p-6 bg-white border border-slate-100 rounded-xl shadow-sm text-center">
        <div class="text-3xl font-extrabold text-blue-600">{{ $success_count }}</div>
        <div class="text-xs text-slate-400 font-bold uppercase tracking-wider mt-1">Baris Baru (Siap Impor)</div>
    </div>
    <div class="glass-card p-6 bg-white border border-slate-100 rounded-xl shadow-sm text-center">
        <div class="text-3xl font-extrabold {{ $conflict_count > 0 ? 'text-amber-500' : 'text-slate-400' }}">{{ $conflict_count }}</div>
        <div class="text-xs text-slate-400 font-bold uppercase tracking-wider mt-1">Baris Konflik (Duplikat ID)</div>
    </div>
</section>

<form action="{{ route('admin.import.resolve') }}" method="POST">
    @csrf
    
    @if($conflict_count > 0)
        <!-- Conflict Resolution Strategy Selection -->
        <div class="glass-card p-6 rounded-xl border border-amber-200 bg-amber-50/30 mb-8">
            <h3 class="text-base font-bold text-amber-800 mb-2 flex items-center gap-2">
                <span class="material-symbols-outlined text-amber-500">warning</span>
                Konflik Terdeteksi! Pilih Aksi Resolusi Massal
            </h3>
            <p class="text-xs text-amber-700 font-medium mb-6">
                Terdapat <strong>{{ $conflict_count }}</strong> pelanggan yang ID Pelanggannya sudah terdaftar di database. Pilih strategi penyelesaian untuk seluruh data konflik tersebut:
            </p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Strategy Skip -->
                <label class="flex items-start gap-3 p-4 bg-white border border-slate-200 rounded-xl cursor-pointer hover:border-amber-500 transition-all select-none">
                    <input type="radio" name="strategy" value="skip" checked class="mt-1 text-primary focus:ring-primary border-slate-200">
                    <div>
                        <strong class="block text-sm text-slate-800 font-bold">Skip (Lewati)</strong>
                        <span class="text-[11px] text-slate-400 font-medium leading-relaxed block mt-0.5">Abaikan seluruh data baru dari CSV yang konflik. Pertahankan data lama di database.</span>
                    </div>
                </label>

                <!-- Strategy Overwrite -->
                <label class="flex items-start gap-3 p-4 bg-white border border-slate-200 rounded-xl cursor-pointer hover:border-amber-500 transition-all select-none">
                    <input type="radio" name="strategy" value="overwrite" class="mt-1 text-primary focus:ring-primary border-slate-200">
                    <div>
                        <strong class="block text-sm text-slate-800 font-bold">Overwrite (Timpa)</strong>
                        <span class="text-[11px] text-slate-400 font-medium leading-relaxed block mt-0.5">Perbarui data lama di database dengan data baru dari file CSV.</span>
                    </div>
                </label>

                <!-- Strategy Keep Both -->
                <label class="flex items-start gap-3 p-4 bg-white border border-slate-200 rounded-xl cursor-pointer hover:border-amber-500 transition-all select-none">
                    <input type="radio" name="strategy" value="keep_both" class="mt-1 text-primary focus:ring-primary border-slate-200">
                    <div>
                        <strong class="block text-sm text-slate-800 font-bold">Keep Both (Simpan Semua)</strong>
                        <span class="text-[11px] text-slate-400 font-medium leading-relaxed block mt-0.5">Simpan data CSV dengan memodifikasi ID Pelanggan secara otomatis (misal: <code>PLG001_DUP1</code>).</span>
                    </div>
                </label>
            </div>
        </div>

        <!-- Comparative Side-by-Side Table -->
        <div class="glass-card rounded-xl overflow-hidden shadow-[0px_4px_20px_rgba(30,58,138,0.03)] border border-slate-100 bg-white mb-8">
            <div class="p-6 border-b border-outline-variant/10">
                <h3 class="text-lg font-bold text-primary">Tabel Komparasi Data Konflik (Side-by-Side)</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider w-40">ID Pelanggan</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider bg-slate-50/30">Data Lama (Database)</th>
                            <th class="px-6 py-4 text-xs font-bold text-blue-600 uppercase tracking-wider bg-blue-50/10 border-l border-blue-100">Data Baru (File CSV)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($conflict_rows as $conf)
                            <tr class="hover:bg-slate-50/30 transition-colors">
                                <td class="px-6 py-4 font-mono text-sm font-bold text-primary">{{ $conf['new']['id_pelanggan'] }}</td>
                                <td class="px-6 py-4 text-xs text-slate-600 bg-slate-50/10 space-y-1">
                                    <div><strong>Nama:</strong> {{ $conf['old']['nama_pelanggan'] }}</div>
                                    <div><strong>Telp:</strong> {{ $conf['old']['no_telepon'] }}</div>
                                    <div><strong>Alamat:</strong> {{ $conf['old']['alamat_pemasangan'] }}</div>
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-600 bg-blue-50/5 border-l border-blue-100 space-y-1">
                                    <div class="p-1 rounded {{ $conf['new']['nama_pelanggan'] !== $conf['old']['nama_pelanggan'] ? 'bg-amber-100/50 font-semibold text-amber-900' : '' }}">
                                        <strong>Nama:</strong> {{ $conf['new']['nama_pelanggan'] }}
                                    </div>
                                    <div class="p-1 rounded {{ $conf['new']['no_telepon'] !== $conf['old']['no_telepon'] ? 'bg-amber-100/50 font-semibold text-amber-900' : '' }}">
                                        <strong>Telp:</strong> {{ $conf['new']['no_telepon'] }}
                                    </div>
                                    <div class="p-1 rounded {{ $conf['new']['alamat_pemasangan'] !== $conf['old']['alamat_pemasangan'] ? 'bg-amber-100/50 font-semibold text-amber-900' : '' }}">
                                        <strong>Alamat:</strong> {{ $conf['new']['alamat_pemasangan'] }}
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="p-6 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm font-semibold mb-8 flex items-center gap-2">
            <span class="material-symbols-outlined text-lg">verified</span>
            <div>Tidak ada data konflik terdeteksi. Seluruh data ({{ $success_count }} pelanggan) siap diimpor secara langsung.</div>
            <input type="hidden" name="strategy" value="skip">
        </div>
    @endif

    <div class="flex gap-4 justify-end">
        <a href="{{ route('admin.import.show') }}" 
           class="h-12 px-6 border border-slate-200 rounded-xl text-sm font-semibold flex items-center justify-center hover:bg-slate-50 transition-all text-slate-600">
            Batal
        </a>
        <button type="submit" 
                class="h-12 px-6 bg-primary text-white font-bold rounded-xl flex items-center justify-center gap-2 hover:bg-secondary active:scale-[0.98] transition-all shadow-lg shadow-primary/10">
            <span class="material-symbols-outlined text-lg">save</span>
            Eksekusi & Simpan Data
        </button>
    </div>
</form>
@endsection
