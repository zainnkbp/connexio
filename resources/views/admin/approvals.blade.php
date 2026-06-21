@extends('layouts.app')

@section('title', 'Connexio - Persetujuan Transaksi')

@section('content')
<!-- Page Header -->
<header class="mb-lg flex justify-between items-end border-b border-outline-variant/10 pb-4">
    <div>
        <p class="font-label-caps text-xs text-secondary uppercase tracking-widest mb-1">Queue Control</p>
        <h1 class="text-3xl font-black text-primary tracking-tight">Persetujuan Transaksi Lapangan</h1>
    </div>
    
    <div class="flex gap-2">
        <!-- Direct Assignment Button -->
        <button class="flex items-center gap-2 px-4 py-2 bg-primary text-white hover:bg-secondary transition-all duration-200 text-xs font-bold rounded-xl shadow-sm" 
                onclick="openDirectAssignmentModal()">
            <span class="material-symbols-outlined text-lg">assignment_ind</span>
            Direct Assignment Perangkat
        </button>
        <!-- Emergency Bypass Button -->
        <button class="flex items-center gap-2 px-4 py-2 bg-white border border-primary text-primary hover:bg-primary hover:text-white transition-all duration-200 text-xs font-bold rounded-xl shadow-sm" 
                onclick="openBypassModal()">
            <span class="material-symbols-outlined text-lg">add_moderator</span>
            Bypass Input Pelanggan Baru
        </button>
    </div>
</header>

<!-- SECTION 1: REQUEST PENGAMBILAN (DEPLOYMENT) -->
<div class="glass-card rounded-xl overflow-hidden shadow-[0px_4px_20px_rgba(30,58,138,0.03)] border border-slate-100 mb-8 bg-white">
    <div class="p-6 border-b border-outline-variant/10 flex items-center gap-2">
        <span class="material-symbols-outlined text-primary text-2xl">local_shipping</span>
        <h2 class="text-lg font-bold text-primary">Persetujuan Request Barang (Deployment)</h2>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-slate-50/50">
                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Teknisi</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">ID Pelanggan</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Nama Pelanggan</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Request Perangkat</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Alamat Pemasangan</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Pilih SN Gudang (Modem/STB)</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider text-right" style="width: 240px;">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($deployments as $deploy)
                    <tr class="hover:bg-slate-50/30 transition-colors">
                        <td class="px-6 py-4 text-sm font-bold text-slate-700">{{ $deploy->teknisi->nama_jelas }}</td>
                        <td class="px-6 py-4 font-mono text-sm text-slate-600 font-semibold">{{ $deploy->id_pelanggan }}</td>
                        <td class="px-6 py-4 text-sm text-slate-700">{{ $deploy->customer->nama_pelanggan ?? '-' }}</td>
                        <td class="px-6 py-4 text-xs font-bold text-blue-600">
                            <span class="bg-blue-50 px-2 py-1 rounded">{{ $deploy->keterangan ?? 'Bebas / Tidak Ditentukan' }}</span>
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-500 font-medium leading-relaxed">{{ $deploy->customer->alamat_pemasangan ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <form id="approve-deploy-{{ $deploy->id_transaksi }}" action="{{ route('admin.approvals.approve-deployment', $deploy->id_transaksi) }}" method="POST" style="margin: 0;" class="space-y-4">
                                @csrf
                                <div id="sn-list-{{ $deploy->id_transaksi }}" class="space-y-3">
                                    <div class="sn-row bg-slate-50 p-3 rounded-xl border border-slate-100 relative" id="sn-row-{{ $deploy->id_transaksi }}-0">
                                        <!-- Normal Select Dropdown -->
                                        <div class="normal-select-container-{{ $deploy->id_transaksi }}-0 mb-2">
                                            <select name="devices[0][serial_number]" required id="select-sn-{{ $deploy->id_transaksi }}-0"
                                                    class="w-full h-10 border border-slate-200 rounded-xl px-3 text-xs focus:border-secondary focus:ring-4 focus:ring-secondary/15 transition-all outline-none font-mono font-bold">
                                                <option value="" disabled selected>Pilih perangkat gudang</option>
                                                @foreach($availableDevices as $device)
                                                    <option value="{{ $device->serial_number }}">
                                                        {{ $device->serial_number }} ({{ $device->jenis_merek }} - {{ $device->tipe_perangkat }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Bypass Device Checkbox Toggle -->
                                        <label class="flex items-center gap-1.5 text-[10px] text-slate-500 font-semibold cursor-pointer select-none mb-2">
                                            <input type="checkbox" name="devices[0][bypass_device]" value="1" onchange="toggleAdminSnBypass('{{ $deploy->id_transaksi }}', 0)" class="w-3.5 h-3.5 rounded text-primary focus:ring-primary border-slate-200" id="checkbox-bypass-{{ $deploy->id_transaksi }}-0">
                                            Input SN Baru (Bypass)
                                        </label>

                                        <!-- Bypass Device Fields (Hidden by default) -->
                                        <div class="bypass-fields-{{ $deploy->id_transaksi }}-0 space-y-2" style="display: none;">
                                            <input type="text" id="input-sn-{{ $deploy->id_transaksi }}-0" disabled
                                                   class="w-full h-10 border border-slate-200 rounded-xl px-3 text-xs focus:border-secondary focus:ring-4 focus:ring-secondary/15 transition-all outline-none font-mono font-bold"
                                                   placeholder="Ketik SN Baru...">
                                            
                                            <div class="flex gap-2 w-full">
                                                <select name="devices[0][jenis_merek]" disabled id="input-brand-{{ $deploy->id_transaksi }}-0"
                                                        class="w-1/2 h-10 border border-slate-200 rounded-xl px-3 text-xs focus:border-secondary focus:ring-4 focus:ring-secondary/15 transition-all outline-none">
                                                    <option value="" disabled selected>Merek</option>
                                                    <option value="STB Huawei">STB Huawei</option>
                                                    <option value="STB ZTE">STB ZTE</option>
                                                    <option value="Modem ZTE">Modem ZTE</option>
                                                    <option value="Modem Huawei">Modem Huawei</option>
                                                </select>
                                                <input type="text" name="devices[0][tipe_perangkat]" disabled id="input-type-{{ $deploy->id_transaksi }}-0"
                                                       class="w-1/2 h-10 border border-slate-200 rounded-xl px-3 text-xs focus:border-secondary focus:ring-4 focus:ring-secondary/15 transition-all outline-none"
                                                       placeholder="Tipe...">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" onclick="addAdminSnRow('{{ $deploy->id_transaksi }}')" class="text-xs font-bold text-primary hover:text-secondary flex items-center gap-1 w-max">
                                    <span class="material-symbols-outlined text-sm">add_circle</span> Tambah Perangkat
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex gap-2 justify-end">
                                <form action="{{ route('admin.approvals.reject', $deploy->id_transaksi) }}" method="POST" style="margin: 0;">
                                    @csrf
                                    <button type="submit" 
                                            class="h-10 px-4 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-xl text-xs font-bold transition-all">
                                        Tolak
                                    </button>
                                </form>
                                <button type="submit" form="approve-deploy-{{ $deploy->id_transaksi }}" 
                                        class="h-10 px-4 bg-primary text-white hover:bg-secondary rounded-xl text-xs font-bold transition-all shadow-sm">
                                    Approve & Kirim
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-slate-400 py-12 text-sm">
                            Tidak ada request pengambilan pending saat ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- SECTION 2: RETURN PENGEMBALIAN BARANG RUSAK -->
<div class="glass-card rounded-xl overflow-hidden shadow-[0px_4px_20px_rgba(30,58,138,0.03)] border border-slate-100 mb-8 bg-white">
    <div class="p-6 border-b border-outline-variant/10 flex items-center gap-2">
        <span class="material-symbols-outlined text-red-600 text-2xl">assignment_return</span>
        <h2 class="text-lg font-bold text-primary">Konfirmasi Pengembalian Barang (Return / Rusak)</h2>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-slate-50/50">
                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Teknisi</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Pelanggan</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Serial Number</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Foto Bukti Rusak</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Alasan Rusak</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider text-right" style="width: 240px;">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($returns as $ret)
                    <tr class="hover:bg-slate-50/30 transition-colors">
                        <td class="px-6 py-4 text-sm font-bold text-slate-700">{{ $ret->teknisi->nama_jelas }}</td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-slate-700">{{ $ret->customer->nama_pelanggan ?? '-' }}</div>
                            <div class="text-xs text-slate-400 font-semibold font-mono">{{ $ret->id_pelanggan }}</div>
                        </td>
                        <td class="px-6 py-4 font-mono text-sm font-bold text-primary">{{ $ret->serial_number }}</td>
                        <td class="px-6 py-4">
                            @if($ret->foto_bukti)
                                <a href="{{ asset($ret->foto_bukti) }}" target="_blank" 
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 text-slate-700 rounded-lg text-xs font-bold hover:bg-primary hover:text-white transition-all shadow-sm">
                                    <span class="material-symbols-outlined text-sm">image</span> Lihat Bukti
                                </a>
                            @else
                                <span class="text-xs text-slate-400 italic">Tidak ada foto</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-500 font-semibold italic">"{{ $ret->alasan_rusak }}"</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex gap-2 justify-end">
                                <form action="{{ route('admin.approvals.reject', $ret->id_transaksi) }}" method="POST" style="margin: 0;">
                                    @csrf
                                    <button type="submit" 
                                            class="h-10 px-4 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-xl text-xs font-bold transition-all">
                                        Tolak
                                    </button>
                                </form>
                                <form action="{{ route('admin.approvals.approve-return', $ret->id_transaksi) }}" method="POST" style="margin: 0;">
                                    @csrf
                                    <button type="submit" 
                                            class="h-10 px-4 bg-primary text-white hover:bg-secondary rounded-xl text-xs font-bold transition-all shadow-sm">
                                        ACC Pengembalian
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-slate-400 py-12 text-sm">
                            Tidak ada pengembalian pending saat ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- SECTION 3: DISMANTLING -->
<div class="glass-card rounded-xl overflow-hidden shadow-[0px_4px_20px_rgba(30,58,138,0.03)] border border-slate-100 bg-white">
    <div class="p-6 border-b border-outline-variant/10 flex items-center gap-2">
        <span class="material-symbols-outlined text-amber-500 text-2xl">build</span>
        <h2 class="text-lg font-bold text-primary">Konfirmasi Pembongkaran (Dismantling)</h2>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-slate-50/50">
                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Teknisi</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Pelanggan</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Serial Number</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Foto Bukti Cabut</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider text-right" style="width: 240px;">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($dismantles as $dismantle)
                    <tr class="hover:bg-slate-50/30 transition-colors">
                        <td class="px-6 py-4 text-sm font-bold text-slate-700">{{ $dismantle->teknisi->nama_jelas }}</td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-slate-700">{{ $dismantle->customer->nama_pelanggan ?? '-' }}</div>
                            <div class="text-xs text-slate-400 font-semibold font-mono">{{ $dismantle->id_pelanggan }}</div>
                        </td>
                        <td class="px-6 py-4 font-mono text-sm font-bold text-primary">{{ $dismantle->serial_number }}</td>
                        <td class="px-6 py-4">
                            @if($dismantle->foto_bukti)
                                <a href="{{ asset($dismantle->foto_bukti) }}" target="_blank" 
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 text-slate-700 rounded-lg text-xs font-bold hover:bg-primary hover:text-white transition-all shadow-sm">
                                    <span class="material-symbols-outlined text-sm">image</span> Lihat Bukti
                                </a>
                            @else
                                <span class="text-xs text-slate-400 italic">Tidak ada foto</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex gap-2 justify-end">
                                <form action="{{ route('admin.approvals.reject', $dismantle->id_transaksi) }}" method="POST" style="margin: 0;">
                                    @csrf
                                    <button type="submit" 
                                            class="h-10 px-4 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-xl text-xs font-bold transition-all">
                                        Tolak
                                    </button>
                                </form>
                                <form action="{{ route('admin.approvals.approve-dismantle', $dismantle->id_transaksi) }}" method="POST" style="margin: 0;">
                                    @csrf
                                    <button type="submit" 
                                            class="h-10 px-4 bg-primary text-white hover:bg-secondary rounded-xl text-xs font-bold transition-all shadow-sm">
                                        ACC Dismantling
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-slate-400 py-12 text-sm">
                            Tidak ada dismantling pending saat ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- BYPASS FORM MODAL (Admin On-The-Fly Input) -->
<div id="bypass-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm" style="display: none;">
    <div class="bg-white rounded-2xl shadow-2xl border border-slate-100 max-w-lg w-full overflow-hidden transform scale-95 transition-transform duration-300">
        <div class="bg-gradient-to-r from-primary to-secondary px-6 py-4 text-white flex justify-between items-center">
            <h3 class="text-base font-bold flex items-center gap-2">
                <span class="material-symbols-outlined">add_moderator</span>
                Bypass Input Pelanggan Baru
            </h3>
            <button onclick="closeBypassModal()" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition-all text-white font-bold">&times;</button>
        </div>
        
        <form id="bypass-form" class="p-6 space-y-4">
            @csrf
            
            <div id="bypass-alert" class="p-3 bg-red-50 border border-red-200 text-red-700 rounded-xl text-xs font-semibold flex items-center gap-2" style="display: none;">
                <span class="material-symbols-outlined text-sm">error</span>
                <div id="bypass-error-text"></div>
            </div>

            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block">ID Pelanggan</label>
                <input type="text" name="id_pelanggan" id="bypass_id_pelanggan" required
                       class="w-full h-11 border border-slate-200 rounded-xl px-4 text-sm focus:border-secondary focus:ring-4 focus:ring-secondary/15 transition-all outline-none font-mono" 
                       placeholder="Contoh: PLG-2026-999">
            </div>
            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Nama Pelanggan</label>
                <input type="text" name="nama_pelanggan" id="bypass_nama_pelanggan" required
                       class="w-full h-11 border border-slate-200 rounded-xl px-4 text-sm focus:border-secondary focus:ring-4 focus:ring-secondary/15 transition-all outline-none" 
                       placeholder="Nama lengkap">
            </div>
            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block">No Telepon</label>
                <input type="text" name="no_telepon" id="bypass_no_telepon" required
                       class="w-full h-11 border border-slate-200 rounded-xl px-4 text-sm focus:border-secondary focus:ring-4 focus:ring-secondary/15 transition-all outline-none" 
                       placeholder="Nomor telepon aktif">
            </div>
            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Alamat Lengkap</label>
                <textarea name="alamat_pemasangan" id="bypass_alamat_pemasangan" required rows="3"
                          class="w-full border border-slate-200 rounded-xl p-4 text-sm focus:border-secondary focus:ring-4 focus:ring-secondary/15 transition-all outline-none resize-none" 
                          placeholder="Alamat lengkap pemasangan"></textarea>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Latitude</label>
                    <input type="text" name="latitude" id="bypass_latitude"
                           class="w-full h-11 border border-slate-200 rounded-xl px-4 text-sm focus:border-secondary focus:ring-4 focus:ring-secondary/15 transition-all outline-none" 
                           placeholder="Contoh: -6.2088">
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Longitude</label>
                    <input type="text" name="longitude" id="bypass_longitude"
                           class="w-full h-11 border border-slate-200 rounded-xl px-4 text-sm focus:border-secondary focus:ring-4 focus:ring-secondary/15 transition-all outline-none" 
                           placeholder="Contoh: 106.8456">
                </div>
            </div>

            <div class="pt-4 flex justify-end gap-2 border-t border-slate-100">
                <button type="button" onclick="closeBypassModal()" 
                        class="h-11 px-6 border border-slate-200 rounded-xl text-sm font-semibold hover:bg-slate-50 transition-all text-slate-600">
                    Batal
                </button>
                <button type="submit" 
                        class="h-11 px-6 bg-primary text-white rounded-xl text-sm font-bold hover:bg-secondary transition-all shadow-lg shadow-primary/10">
                    Simpan Pelanggan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- DIRECT ASSIGNMENT MODAL -->
<div id="direct-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm" style="display: none;">
    <div class="bg-white rounded-2xl shadow-2xl border border-slate-100 max-w-lg w-full overflow-hidden transform scale-95 transition-transform duration-300">
        <div class="bg-gradient-to-r from-primary to-secondary px-6 py-4 text-white flex justify-between items-center">
            <h3 class="text-base font-bold flex items-center gap-2">
                <span class="material-symbols-outlined">assignment_ind</span>
                Direct Assignment Perangkat
            </h3>
            <button onclick="closeDirectModal()" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition-all text-white font-bold">&times;</button>
        </div>
        
        <form id="direct-form" action="{{ route('admin.approvals.direct') }}" method="POST" class="p-6 space-y-4">
            @csrf
            
            <div id="direct-alert" class="p-3 bg-red-50 border border-red-200 text-red-700 rounded-xl text-xs font-semibold flex items-center gap-2" style="display: none;">
                <span class="material-symbols-outlined text-sm">error</span>
                <div id="direct-error-text"></div>
            </div>

            <!-- Customer Search Field -->
            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block">ID Pelanggan</label>
                <div class="flex gap-2">
                    <input type="text" name="id_pelanggan" id="direct_id_pelanggan" required
                           class="w-full h-11 border border-slate-200 rounded-xl px-4 text-sm focus:border-secondary focus:ring-4 focus:ring-secondary/15 transition-all outline-none font-mono" 
                           placeholder="Masukkan ID Pelanggan">
                    <button type="button" class="h-11 px-4 bg-slate-100 border border-slate-200 hover:bg-slate-200 rounded-xl text-xs font-bold text-slate-700 transition-all" 
                            onclick="checkCustomerForDirect()">Cek</button>
                </div>
            </div>

            <!-- Hidden Customer details showing if verified -->
            <div id="direct-cust-details" class="p-4 bg-primary/5 border border-primary/10 rounded-xl text-xs text-slate-600 font-semibold space-y-1" style="display: none;">
                <div><strong>Nama:</strong> <span id="direct-cust-name" class="text-slate-800"></span></div>
                <div><strong>Alamat:</strong> <span id="direct-cust-address" class="text-slate-800"></span></div>
                <div><strong>No Telp:</strong> <span id="direct-cust-phone" class="text-slate-800"></span></div>
            </div>

            <!-- BYPASS FORM FOR DIRECT CUSTOMER (If not found) -->
            <div id="direct-bypass-section" class="p-4 border border-dashed border-red-200 bg-red-50/30 rounded-xl space-y-3" style="display: none;">
                <p class="text-xs font-bold text-red-700">Data Pelanggan Tidak Ditemukan! Bypass Pendaftaran:</p>
                
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Nama Pelanggan</label>
                    <input type="text" id="direct_bypass_nama" class="w-full h-10 border border-slate-200 bg-white rounded-lg px-3 text-xs outline-none">
                </div>
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">No Telepon</label>
                    <input type="text" id="direct_bypass_telp" class="w-full h-10 border border-slate-200 bg-white rounded-lg px-3 text-xs outline-none">
                </div>
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Alamat Lengkap</label>
                    <textarea id="direct_bypass_alamat" rows="2" class="w-full border border-slate-200 bg-white rounded-lg p-3 text-xs outline-none resize-none"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <input type="text" id="direct_bypass_lat" placeholder="Lat" class="h-10 border border-slate-200 bg-white rounded-lg px-3 text-xs outline-none">
                    <input type="text" id="direct_bypass_lng" placeholder="Lng" class="h-10 border border-slate-200 bg-white rounded-lg px-3 text-xs outline-none">
                </div>
                <button type="button" 
                        class="w-full h-10 border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 rounded-lg text-xs font-bold transition-all flex items-center justify-center gap-1.5" 
                        onclick="detectDirectBypassGPS()">
                    <span class="material-symbols-outlined text-base">my_location</span> GPS Otomatis
                </button>
                <button type="button" 
                        class="w-full h-10 bg-red-600 text-white rounded-lg text-xs font-bold hover:bg-red-700 transition-all" 
                        onclick="submitBypassForDirect()">
                    Simpan & Bypass Pelanggan
                </button>
            </div>

            <!-- Technician Dropdown -->
            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Pilih Teknisi</label>
                <select name="id_teknisi" id="direct_id_teknisi" required
                        class="w-full h-11 border border-slate-200 rounded-xl px-4 text-sm focus:border-secondary focus:ring-4 focus:ring-secondary/15 transition-all outline-none">
                    <option value="" disabled selected>Pilih teknisi lapangan</option>
                    @foreach($technicians as $tech)
                        <option value="{{ $tech->id_user }}">{{ $tech->nama_jelas }} ({{ $tech->username }})</option>
                    @endforeach
                </select>
            </div>

            <!-- Available Devices Dropdown -->
            <div class="space-y-1">
                <div class="direct-select-container">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Pilih Perangkat Gudang</label>
                    <select name="serial_number" id="direct_serial_number" required
                            class="w-full h-11 border border-slate-200 rounded-xl px-4 text-sm focus:border-secondary focus:ring-4 focus:ring-secondary/15 transition-all outline-none font-mono font-bold">
                        <option value="" disabled selected>Pilih serial number gudang</option>
                        @foreach($availableDevices as $dev)
                            <option value="{{ $dev->serial_number }}">{{ $dev->serial_number }} ({{ $dev->jenis_merek }} - {{ $dev->tipe_perangkat }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Bypass Device Checkbox Toggle -->
                <label class="flex items-center gap-1.5 text-[10px] text-slate-500 font-semibold cursor-pointer select-none mt-2">
                    <input type="checkbox" name="bypass_device" value="1" onchange="toggleDirectBypassDevice()" class="w-3.5 h-3.5 rounded text-primary focus:ring-primary border-slate-200" id="direct-checkbox-bypass">
                    Input SN Baru (Bypass)
                </label>

                <!-- Bypass Device Fields (Hidden by default) -->
                <div class="direct-bypass-fields space-y-2 mt-2" style="display: none;">
                    <input type="text" id="direct-input-sn" disabled
                           class="w-full h-11 border border-slate-200 rounded-xl px-4 text-sm focus:border-secondary focus:ring-4 focus:ring-secondary/15 transition-all outline-none font-mono font-bold"
                           placeholder="Ketik SN Baru...">
                    
                    <div class="flex gap-2">
                        <select name="jenis_merek" disabled id="direct-input-brand"
                                class="w-1/2 h-11 border border-slate-200 rounded-xl px-4 text-sm focus:border-secondary focus:ring-4 focus:ring-secondary/15 transition-all outline-none">
                            <option value="" disabled selected>Merek</option>
                            <option value="STB Huawei">STB Huawei</option>
                            <option value="STB ZTE">STB ZTE</option>
                            <option value="Modem ZTE">Modem ZTE</option>
                            <option value="Modem Huawei">Modem Huawei</option>
                        </select>
                        <input type="text" name="tipe_perangkat" disabled id="direct-input-type"
                               class="w-1/2 h-11 border border-slate-200 rounded-xl px-4 text-sm focus:border-secondary focus:ring-4 focus:ring-secondary/15 transition-all outline-none"
                               placeholder="Tipe...">
                    </div>
                </div>
            </div>

            <div class="pt-4 flex justify-end gap-2 border-t border-slate-100">
                <button type="button" onclick="closeDirectModal()" 
                        class="h-11 px-6 border border-slate-200 rounded-xl text-sm font-semibold hover:bg-slate-50 transition-all text-slate-600">
                    Batal
                </button>
                <button type="submit" id="direct-submit-btn" disabled
                        class="h-11 px-6 bg-primary text-white rounded-xl text-sm font-bold opacity-50 cursor-not-allowed transition-all shadow-lg shadow-primary/10">
                    Simpan & Tugaskan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openBypassModal() {
        $('#bypass-modal').css('display', 'flex').find('.transform').removeClass('scale-95').addClass('scale-100');
        $('#bypass-alert').hide();
    }

    function closeBypassModal() {
        $('#bypass-modal').find('.transform').removeClass('scale-100').addClass('scale-95');
        setTimeout(() => {
            $('#bypass-modal').hide();
            $('#bypass-form')[0].reset();
        }, 150);
    }

    // Submit bypass form using Ajax
    $('#bypass-form').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: "{{ route('admin.customers.bypass') }}",
            type: "POST",
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    alert('Pelanggan ' + response.customer.nama_pelanggan + ' berhasil didaftarkan!');
                    closeBypassModal();
                    location.reload(); // Reload dashboard/approvals
                } else {
                    $('#bypass-error-text').text(response.message);
                    $('#bypass-alert').show();
                }
            },
            error: function(xhr) {
                let errors = xhr.responseJSON.errors;
                let errorMsg = 'Gagal menyimpan data: ';
                if (errors) {
                    errorMsg += Object.values(errors)[0][0];
                } else {
                    errorMsg += xhr.responseJSON.message || 'Terjadi kesalahan.';
                }
                $('#bypass-error-text').text(errorMsg);
                $('#bypass-alert').show();
            }
        });
    });

    // --- DIRECT ASSIGNMENT JS ---
    function openDirectAssignmentModal() {
        $('#direct-modal').css('display', 'flex').find('.transform').removeClass('scale-95').addClass('scale-100');
        $('#direct-alert').hide();
    }

    // Close direct modal
    function closeDirectModal() {
        $('#direct-modal').find('.transform').removeClass('scale-100').addClass('scale-95');
        setTimeout(() => {
            $('#direct-modal').hide();
            $('#direct-form')[0].reset();
            $('#direct-cust-details').hide();
            $('#direct-bypass-section').hide();
            $('#direct-submit-btn').addClass('opacity-50 cursor-not-allowed').prop('disabled', true);
            
            // Reset direct bypass state
            $('#direct-checkbox-bypass').prop('checked', false);
            toggleDirectBypassDevice();
        }, 150);
    }

    function checkCustomerForDirect() {
        const id = $('#direct_id_pelanggan').val();
        if (!id) return alert('Masukkan ID Pelanggan.');

        $.ajax({
            url: "{{ route('admin.customers.search') }}",
            type: "GET",
            data: { id_pelanggan: id },
            success: function(response) {
                if (response.success) {
                    $('#direct-cust-name').text(response.customer.nama_pelanggan);
                    $('#direct-cust-address').text(response.customer.alamat_pemasangan);
                    $('#direct-cust-phone').text(response.customer.no_telepon);
                    $('#direct-cust-details').slideDown();
                    $('#direct-bypass-section').slideUp();
                    $('#direct-submit-btn').removeClass('opacity-50 cursor-not-allowed').prop('disabled', false);
                } else {
                    $('#direct-cust-details').slideUp();
                    $('#direct-bypass-section').slideDown();
                    $('#direct-submit-btn').addClass('opacity-50 cursor-not-allowed').prop('disabled', true);
                }
            }
        });
    }

    function detectDirectBypassGPS() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                $('#direct_bypass_lat').val(position.coords.latitude.toFixed(8));
                $('#direct_bypass_lng').val(position.coords.longitude.toFixed(8));
                alert('GPS Berhasil dideteksi!');
            }, function(error) {
                alert('Gagal mendeteksi GPS: Harap periksa izin lokasi browser Anda.');
            });
        } else {
            alert('Browser Anda tidak mendukung Geolocation.');
        }
    }

    function submitBypassForDirect() {
        const id = $('#direct_id_pelanggan').val();
        const nama = $('#direct_bypass_nama').val();
        const telp = $('#direct_bypass_telp').val();
        const alamat = $('#direct_bypass_alamat').val();
        const lat = $('#direct_bypass_lat').val();
        const lng = $('#direct_bypass_lng').val();

        if (!nama || !telp || !alamat) return alert('Harap isi Nama, Telp, dan Alamat.');

        $.ajax({
            url: "{{ route('admin.customers.bypass') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                id_pelanggan: id,
                nama_pelanggan: nama,
                no_telepon: telp,
                alamat_pemasangan: alamat,
                latitude: lat,
                longitude: lng
            },
            success: function(response) {
                if (response.success) {
                    alert('Pelanggan berhasil dibuat via bypass!');
                    $('#direct-cust-name').text(response.customer.nama_pelanggan);
                    $('#direct-cust-address').text(response.customer.alamat_pemasangan);
                    $('#direct-cust-phone').text(response.customer.no_telepon);
                    
                    $('#direct-bypass-section').slideUp();
                    $('#direct-cust-details').slideDown();
                    $('#direct-submit-btn').removeClass('opacity-50 cursor-not-allowed').prop('disabled', false);
                }
            },
            error: function(xhr) {
                alert('Bypass gagal: ' + (xhr.responseJSON.message || 'ID Pelanggan sudah ada atau format koordinat salah.'));
            }
        });
    }

    // --- TOGGLE BYPASS DEVICE INPUT IN LIST ---
    let adminSnCounters = {};

    function addAdminSnRow(transaksiId) {
        if (!adminSnCounters[transaksiId]) {
            adminSnCounters[transaksiId] = 1;
        }
        let i = adminSnCounters[transaksiId];
        adminSnCounters[transaksiId]++;
        
        // Get the available options from the first select (0)
        let optionsHtml = $('#select-sn-' + transaksiId + '-0').html();
        
        let html = `
            <div class="sn-row bg-slate-50 p-3 rounded-xl border border-slate-100 relative mt-3" id="sn-row-${transaksiId}-${i}">
                <button type="button" onclick="removeAdminSnRow('${transaksiId}', ${i})" class="absolute top-2 right-2 text-slate-400 hover:text-red-500">
                    <span class="material-symbols-outlined text-sm">close</span>
                </button>
                <div class="normal-select-container-${transaksiId}-${i} mb-2">
                    <select name="devices[${i}][serial_number]" required id="select-sn-${transaksiId}-${i}"
                            class="w-full pr-6 h-10 border border-slate-200 rounded-xl px-3 text-xs focus:border-secondary focus:ring-4 focus:ring-secondary/15 transition-all outline-none font-mono font-bold">
                        ${optionsHtml}
                    </select>
                </div>
                <label class="flex items-center gap-1.5 text-[10px] text-slate-500 font-semibold cursor-pointer select-none mb-2">
                    <input type="checkbox" name="devices[${i}][bypass_device]" value="1" onchange="toggleAdminSnBypass('${transaksiId}', ${i})" class="w-3.5 h-3.5 rounded text-primary focus:ring-primary border-slate-200" id="checkbox-bypass-${transaksiId}-${i}">
                    Input SN Baru (Bypass)
                </label>
                <div class="bypass-fields-${transaksiId}-${i} space-y-2" style="display: none;">
                    <input type="text" id="input-sn-${transaksiId}-${i}" disabled
                           class="w-full h-10 border border-slate-200 rounded-xl px-3 text-xs focus:border-secondary focus:ring-4 focus:ring-secondary/15 transition-all outline-none font-mono font-bold"
                           placeholder="Ketik SN Baru...">
                    <div class="flex gap-2 w-full">
                        <select name="devices[${i}][jenis_merek]" disabled id="input-brand-${transaksiId}-${i}"
                                class="w-1/2 h-10 border border-slate-200 rounded-xl px-3 text-xs focus:border-secondary focus:ring-4 focus:ring-secondary/15 transition-all outline-none">
                            <option value="" disabled selected>Merek</option>
                            <option value="STB Huawei">STB Huawei</option>
                            <option value="STB ZTE">STB ZTE</option>
                            <option value="Modem ZTE">Modem ZTE</option>
                            <option value="Modem Huawei">Modem Huawei</option>
                        </select>
                        <input type="text" name="devices[${i}][tipe_perangkat]" disabled id="input-type-${transaksiId}-${i}"
                               class="w-1/2 h-10 border border-slate-200 rounded-xl px-3 text-xs focus:border-secondary focus:ring-4 focus:ring-secondary/15 transition-all outline-none"
                               placeholder="Tipe...">
                    </div>
                </div>
            </div>
        `;
        $('#sn-list-' + transaksiId).append(html);
    }

    function removeAdminSnRow(transaksiId, index) {
        $('#sn-row-' + transaksiId + '-' + index).remove();
    }

    function toggleAdminSnBypass(transaksiId, index) {
        const checked = $('#checkbox-bypass-' + transaksiId + '-' + index).is(':checked');
        const selectSn = $('#select-sn-' + transaksiId + '-' + index);
        const inputSn = $('#input-sn-' + transaksiId + '-' + index);
        const inputBrand = $('#input-brand-' + transaksiId + '-' + index);
        const inputType = $('#input-type-' + transaksiId + '-' + index);
        const normalSelect = $('.normal-select-container-' + transaksiId + '-' + index);
        const bypassFields = $('.bypass-fields-' + transaksiId + '-' + index);

        if (checked) {
            selectSn.prop('disabled', true).removeAttr('name').prop('required', false);
            normalSelect.hide();
            
            inputSn.prop('disabled', false).attr('name', `devices[${index}][serial_number_bypass]`).prop('required', true);
            inputBrand.prop('disabled', false).prop('required', true);
            inputType.prop('disabled', false).prop('required', true);
            bypassFields.show();
        } else {
            selectSn.prop('disabled', false).attr('name', `devices[${index}][serial_number]`).prop('required', true);
            normalSelect.show();
            
            inputSn.prop('disabled', true).removeAttr('name').prop('required', false);
            inputBrand.prop('disabled', true).prop('required', false);
            inputType.prop('disabled', true).prop('required', false);
            bypassFields.hide();
        }
    }

    // --- TOGGLE BYPASS DEVICE INPUT IN DIRECT ASSIGNMENT ---
    function toggleDirectBypassDevice() {
        const checked = $('#direct-checkbox-bypass').is(':checked');
        const selectSn = $('#direct_serial_number');
        const inputSn = $('#direct-input-sn');
        const inputBrand = $('#direct-input-brand');
        const inputType = $('#direct-input-type');
        const normalSelect = $('.direct-select-container');
        const bypassFields = $('.direct-bypass-fields');

        if (checked) {
            // Switch to Bypass Input
            selectSn.prop('disabled', true).removeAttr('name').prop('required', false);
            normalSelect.hide();
            
            inputSn.prop('disabled', false).attr('name', 'serial_number').prop('required', true);
            inputBrand.prop('disabled', false).prop('required', true);
            inputType.prop('disabled', false).prop('required', true);
            bypassFields.show();
        } else {
            // Switch to Normal Select
            selectSn.prop('disabled', false).attr('name', 'serial_number').prop('required', true);
            normalSelect.show();
            
            inputSn.prop('disabled', true).removeAttr('name').prop('required', false);
            inputBrand.prop('disabled', true).prop('required', false);
            inputType.prop('disabled', true).prop('required', false);
            bypassFields.hide();
        }
    }
</script>
@endsection
