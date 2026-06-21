@extends('layouts.app')

@section('title', 'Connexio - Bulk Import Pelanggan')

@section('content')
<!-- Page Header -->
<header class="mb-lg flex justify-between items-end border-b border-outline-variant/10 pb-4">
    <div>
        <p class="font-label-caps text-xs text-secondary uppercase tracking-widest mb-1">Data Integration</p>
        <h1 class="text-3xl font-black text-primary tracking-tight">Bulk Import Pelanggan</h1>
    </div>
</header>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
    <!-- Import File Card -->
    <div class="glass-card p-6 rounded-xl shadow-[0px_4px_20px_rgba(30,58,138,0.03)] bg-white border border-slate-100">
        <h3 class="text-lg font-bold text-primary mb-2 flex items-center gap-2">
            <span class="material-symbols-outlined text-secondary">cloud_upload</span>
            Unggah File CSV Pelanggan
        </h3>
        
        <p class="text-xs text-slate-400 mb-6 font-semibold">
            Sistem mendukung pemrosesan data massal via file CSV dengan pemisah koma (,) atau titik koma (;).
        </p>

        <form action="{{ route('admin.import.parse') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div class="space-y-2">
                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Pilih File CSV</label>
                <div class="relative border-2 border-dashed border-slate-200 hover:border-primary bg-slate-50 hover:bg-primary/5 rounded-xl p-8 transition-all duration-300 flex flex-col items-center justify-center cursor-pointer group">
                    <input type="file" id="import_file" name="import_file" accept=".csv,.txt" required 
                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    <span class="material-symbols-outlined text-4xl text-slate-400 group-hover:text-primary transition-colors mb-2">upload_file</span>
                    <span class="text-xs font-bold text-slate-600 group-hover:text-primary transition-colors">Pilih file CSV dari komputer Anda</span>
                    <span class="text-[10px] text-slate-400 mt-1 font-semibold">Ukuran file maksimal 5MB</span>
                </div>
            </div>

            <button type="submit" 
                    class="w-full h-12 bg-primary text-white font-bold rounded-xl flex items-center justify-center gap-2 hover:bg-secondary active:scale-[0.98] transition-all shadow-lg shadow-primary/10">
                <span class="material-symbols-outlined text-lg">cloud_upload</span>
                Unggah & Proses Validasi
            </button>
        </form>
    </div>

    <!-- Instructions Card -->
    <div class="glass-card p-6 rounded-xl shadow-[0px_4px_20px_rgba(30,58,138,0.03)] bg-blue-50/50 border border-blue-100/50">
        <h3 class="text-lg font-bold text-primary mb-4 pb-2 border-b border-blue-100/30 flex items-center gap-2">
            <span class="material-symbols-outlined text-secondary">info</span>
            Format Panduan Struktur Kolom
        </h3>
        
        <div class="text-xs text-slate-600 space-y-4">
            <p class="font-bold text-slate-700">File CSV harus memiliki kolom header persis seperti di bawah:</p>
            
            <ul class="list-disc pl-5 space-y-2 leading-relaxed">
                <li><code class="bg-blue-100 text-blue-800 font-bold px-1.5 py-0.5 rounded font-mono">id_pelanggan</code> : Kode unik ID Pelanggan (misal: PLG-001)</li>
                <li><code class="bg-blue-100 text-blue-800 font-bold px-1.5 py-0.5 rounded font-mono">nama_pelanggan</code> : Nama lengkap pelanggan</li>
                <li><code class="bg-blue-100 text-blue-800 font-bold px-1.5 py-0.5 rounded font-mono">no_telepon</code> : Nomor kontak aktif (misal: 081234...)</li>
                <li><code class="bg-blue-100 text-blue-800 font-bold px-1.5 py-0.5 rounded font-mono">alamat_pemasangan</code> : Alamat lengkap rumah pelanggan</li>
            </ul>

            <div class="pt-2">
                <p class="font-bold text-slate-700 mb-2">Contoh Struktur File CSV:</p>
                <pre class="bg-white p-4 rounded-xl border border-slate-100 overflow-x-auto font-mono text-[11px] leading-relaxed text-slate-500 font-semibold shadow-sm">id_pelanggan,nama_pelanggan,no_telepon,alamat_pemasangan
PLG-2026-101,Ahmad Subarjo,0812334455,Jl. Kemang Raya No. 12
PLG-2026-102,Lina Mariana,0899887766,Gedung Sinarmas Lt. 2</pre>
            </div>
        </div>
    </div>
</div>
@endsection
