@extends('layouts.app')

@section('title', 'Connexio - Pool Perangkat')

@section('content')
<!-- Page Header -->
<header class="mb-lg flex justify-between items-end border-b border-outline-variant/10 pb-4">
    <div>
        <p class="font-label-caps text-xs text-secondary uppercase tracking-widest mb-1">Asset Control</p>
        <h1 class="text-3xl font-black text-primary tracking-tight">Pool Perangkat Gudang</h1>
    </div>
    <div class="flex items-center gap-2 text-xs text-slate-500">
        <span class="material-symbols-outlined text-sm text-secondary">inventory_2</span>
        <span><strong class="text-primary">{{ $devices->count() }}</strong> total perangkat terdaftar</span>
    </div>
</header>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
    <!-- Form Registration (Left Card) -->
    <div class="glass-card p-6 rounded-xl shadow-[0px_4px_20px_rgba(30,58,138,0.03)] bg-white border border-slate-100 lg:col-span-1">
        <h3 class="text-lg font-bold text-primary mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
            <span class="material-symbols-outlined text-secondary">add_box</span>
            Daftarkan Alat Baru
        </h3>

        @if($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-xs font-semibold flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">error</span>
                <div>{{ $errors->first() }}</div>
            </div>
        @endif

        <form action="{{ route('admin.devices.store') }}" method="POST" class="space-y-4">
            @csrf
            
            <div class="space-y-1">
                <label for="serial_number" class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Serial Number (SN)</label>
                <input type="text" id="serial_number" name="serial_number" required
                       value="{{ old('serial_number') }}"
                       class="w-full h-11 border border-slate-200 rounded-xl px-4 text-sm focus:border-secondary focus:ring-4 focus:ring-secondary/15 transition-all outline-none font-mono font-bold"
                       placeholder="Contoh: SN-STB-HUA-888">
            </div>

            <div class="space-y-1">
                <label for="jenis_merek" class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Jenis & Merek</label>
                <select id="jenis_merek" name="jenis_merek" required
                        class="w-full h-11 border border-slate-200 rounded-xl px-4 text-sm focus:border-secondary focus:ring-4 focus:ring-secondary/15 transition-all outline-none">
                    <option value="" disabled selected>Pilih jenis & merek</option>
                    <option value="STB Huawei" {{ old('jenis_merek') === 'STB Huawei' ? 'selected' : '' }}>STB Huawei</option>
                    <option value="STB ZTE" {{ old('jenis_merek') === 'STB ZTE' ? 'selected' : '' }}>STB ZTE</option>
                    <option value="Modem ZTE" {{ old('jenis_merek') === 'Modem ZTE' ? 'selected' : '' }}>Modem ZTE</option>
                    <option value="Modem Huawei" {{ old('jenis_merek') === 'Modem Huawei' ? 'selected' : '' }}>Modem Huawei</option>
                </select>
            </div>

            <div class="space-y-1">
                <label for="tipe_perangkat" class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Tipe Perangkat</label>
                <input type="text" id="tipe_perangkat" name="tipe_perangkat" required
                       value="{{ old('tipe_perangkat') }}"
                       class="w-full h-11 border border-slate-200 rounded-xl px-4 text-sm focus:border-secondary focus:ring-4 focus:ring-secondary/15 transition-all outline-none"
                       placeholder="Contoh: huawei790, F609, B860H">
            </div>

            <button type="submit" 
                    class="w-full h-12 bg-primary text-white font-bold rounded-xl flex items-center justify-center gap-2 hover:bg-secondary active:scale-[0.98] transition-all shadow-lg shadow-primary/10">
                <span class="material-symbols-outlined text-lg">add_box</span>
                Simpan ke Pool Gudang
            </button>
        </form>

        <!-- Legend -->
        <div class="mt-6 pt-4 border-t border-slate-100 space-y-2">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Status Kondisi</p>
            <div class="flex flex-wrap gap-2">
                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-slate-50 border border-slate-200 text-slate-600">⬜ Ready di Gudang</span>
                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-blue-50 border border-blue-200 text-blue-700">🔵 Terpasang</span>
                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-red-50 border border-red-200 text-red-700">🔴 Rusak</span>
                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 border border-amber-200 text-amber-700">🟠 Dismantled</span>
            </div>
        </div>
    </div>

    <!-- List Table (Right Card) -->
    <div class="glass-card p-6 rounded-xl shadow-[0px_4px_20px_rgba(30,58,138,0.03)] bg-white border border-slate-100 lg:col-span-2">
        <div class="flex justify-between items-center mb-4 pb-2 border-b border-slate-100">
            <h3 class="text-lg font-bold text-primary flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary">inventory_2</span>
                Daftar Perangkat Gudang & Lapangan
            </h3>
            <!-- Summary badges -->
            <div class="flex gap-2">
                <span class="px-2 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600">
                    {{ \App\Models\Device::whereNull('status_kondisi')->count() }} Ready
                </span>
                <span class="px-2 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700">
                    {{ \App\Models\Device::where('status_kondisi', 'Terpasang')->count() }} Terpasang
                </span>
                <span class="px-2 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">
                    {{ \App\Models\Device::where('status_kondisi', 'Rusak')->count() }} Rusak
                </span>
            </div>
        </div>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('admin.devices.index') }}" class="mb-4 flex flex-wrap gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari SN / Jenis..." 
                   class="flex-1 min-w-[200px] h-10 border border-slate-200 rounded-xl px-4 text-sm focus:border-secondary outline-none">
            
            <select name="status" class="h-10 border border-slate-200 rounded-xl px-4 text-sm focus:border-secondary outline-none">
                <option value="">Semua Status</option>
                <option value="Ready" {{ request('status') === 'Ready' ? 'selected' : '' }}>Ready</option>
                <option value="Terpasang" {{ request('status') === 'Terpasang' ? 'selected' : '' }}>Terpasang</option>
                <option value="Rusak" {{ request('status') === 'Rusak' ? 'selected' : '' }}>Rusak</option>
                <option value="Dismantling" {{ request('status') === 'Dismantling' ? 'selected' : '' }}>Dismantled</option>
            </select>

            <select name="sort_by" class="h-10 border border-slate-200 rounded-xl px-4 text-sm focus:border-secondary outline-none">
                <option value="default" {{ request('sort_by') === 'default' ? 'selected' : '' }}>Urut EWS (Default)</option>
                <option value="last_edited" {{ request('sort_by') === 'last_edited' ? 'selected' : '' }}>Terakhir Diedit</option>
                <option value="newest" {{ request('sort_by') === 'newest' ? 'selected' : '' }}>Baru Ditambahkan</option>
                <option value="oldest" {{ request('sort_by') === 'oldest' ? 'selected' : '' }}>Lama Ditambahkan</option>
            </select>

            <button type="submit" class="h-10 bg-primary/10 text-primary font-bold px-4 rounded-xl hover:bg-primary/20 transition-all flex items-center gap-1 text-sm">
                <span class="material-symbols-outlined text-[18px]">filter_list</span> Filter
            </button>
            @if(request()->anyFilled(['search', 'status', 'sort_by']) && request('sort_by') !== 'default')
                <a href="{{ route('admin.devices.index') }}" class="h-10 text-slate-500 font-bold px-4 rounded-xl hover:bg-slate-100 transition-all flex items-center gap-1 text-sm">Reset</a>
            @endif
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-4 py-3 text-xs font-bold text-slate-400 uppercase tracking-wider">Serial Number</th>
                        <th class="px-4 py-3 text-xs font-bold text-slate-400 uppercase tracking-wider">Jenis & Merek</th>
                        <th class="px-4 py-3 text-xs font-bold text-slate-400 uppercase tracking-wider">Tipe</th>
                        <th class="px-4 py-3 text-xs font-bold text-slate-400 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-xs font-bold text-slate-400 uppercase tracking-wider">Pelanggan</th>
                        <th class="px-4 py-3 text-xs font-bold text-slate-400 uppercase tracking-wider">Tgl Edit</th>
                        <th class="px-4 py-3 text-xs font-bold text-slate-400 uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($devices as $device)
                        @php
                            $activeAssignment = $device->assignments->where('tipe_alur', 'Pengambilan')
                                ->where('status_approval', 'Approved_by_Admin')->first();
                            $completedAssignment = $device->assignments->where('tipe_alur', 'Pengambilan')
                                ->where('status_approval', 'Approved_by_Admin')
                                ->whereNotNull('foto_bukti')
                                ->first();
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition-colors {{ $device->status_kondisi === 'Rusak' ? 'warning-row' : '' }}">
                            <td class="px-4 py-3 font-mono text-xs font-bold text-primary">{{ $device->serial_number }}</td>
                            <td class="px-4 py-3 text-sm font-semibold text-slate-700">{{ $device->jenis_merek }}</td>
                            <td class="px-4 py-3 text-xs"><code class="bg-slate-100 px-2 py-1 rounded text-slate-600 font-bold font-mono">{{ $device->tipe_perangkat }}</code></td>
                            <td class="px-4 py-3">
                                @if(empty($device->status_kondisi))
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-slate-50 border border-slate-200 text-slate-600">Ready</span>
                                @elseif($device->status_kondisi === 'Terpasang')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-blue-50 border border-blue-200 text-blue-700">Terpasang</span>
                                @elseif($device->status_kondisi === 'Rusak')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-red-50 border border-red-200 text-red-700">Rusak</span>
                                @elseif($device->status_kondisi === 'Dismantling')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 border border-amber-200 text-amber-700">Dismantled</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-xs text-slate-600">
                                @if($activeAssignment && $activeAssignment->customer)
                                    <div class="font-semibold text-primary">{{ $activeAssignment->customer->nama_pelanggan }}</div>
                                    <div class="text-slate-400 font-mono">{{ $activeAssignment->customer->id_pelanggan }}</div>
                                @else
                                    <span class="text-slate-300">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-xs text-slate-500 font-medium">
                                {{ $device->updated_at ? $device->updated_at->format('d M y H:i') : '—' }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-1">
                                    <!-- Detail Button -->
                                    <button type="button"
                                            onclick="openDetailModal({{ json_encode([
                                                'serial_number' => $device->serial_number,
                                                'jenis_merek' => $device->jenis_merek,
                                                'tipe_perangkat' => $device->tipe_perangkat,
                                                'status_kondisi' => $device->status_kondisi ?? 'Ready',
                                                'alasan_rusak' => $device->alasan_rusak ?? '—',
                                                'tanggal_pasang_awal' => $device->tanggal_pasang_awal ? \Carbon\Carbon::parse($device->tanggal_pasang_awal)->format('d M Y') : '—',
                                                'created_at' => $device->created_at->format('d M Y H:i'),
                                                'pelanggan_nama' => $activeAssignment?->customer?->nama_pelanggan ?? '—',
                                                'pelanggan_id' => $activeAssignment?->customer?->id_pelanggan ?? '—',
                                                'pelanggan_alamat' => $activeAssignment?->customer?->alamat_pemasangan ?? '—',
                                                'teknisi_nama' => $completedAssignment?->teknisi?->nama_jelas ?? ($activeAssignment?->teknisi?->nama_jelas ?? '—'),
                                                'foto_bukti' => $completedAssignment?->foto_bukti ?? null,
                                            ]) }})"
                                            class="w-8 h-8 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 flex items-center justify-center transition-all" title="Lihat Detail">
                                        <span class="material-symbols-outlined text-base">visibility</span>
                                    </button>
                                    <!-- Edit Button -->
                                    @if(!in_array($device->status_kondisi, ['Terpasang', 'Dismantling', 'Rusak']))
                                        <button type="button"
                                                onclick="openEditModal({{ json_encode([
                                                    'serial_number' => $device->serial_number,
                                                    'jenis_merek' => $device->jenis_merek,
                                                    'tipe_perangkat' => $device->tipe_perangkat,
                                                    'alasan_rusak' => $device->alasan_rusak ?? '',
                                                ]) }})"
                                                class="w-8 h-8 rounded-lg bg-primary/10 hover:bg-primary/20 text-primary flex items-center justify-center transition-all" title="Edit Data">
                                            <span class="material-symbols-outlined text-base">edit</span>
                                        </button>
                                    @else
                                        <div class="w-8 h-8 rounded-lg bg-slate-50 text-slate-300 flex items-center justify-center cursor-not-allowed" title="Tidak bisa diedit, status perangkat: {{ $device->status_kondisi }}">
                                            <span class="material-symbols-outlined text-base">edit_off</span>
                                        </div>
                                    @endif
                                    <!-- Delete Button -->
                                    @if($device->status_kondisi !== 'Terpasang')
                                        <button type="button"
                                                onclick="openDeleteModal('{{ $device->serial_number }}', '{{ $device->jenis_merek }}')"
                                                class="w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 text-red-500 flex items-center justify-center transition-all" title="Hapus Perangkat">
                                            <span class="material-symbols-outlined text-base">delete</span>
                                        </button>
                                    @else
                                        <div class="w-8 h-8 rounded-lg bg-slate-50 text-slate-300 flex items-center justify-center cursor-not-allowed" title="Tidak bisa dihapus, perangkat sedang terpasang">
                                            <span class="material-symbols-outlined text-base">lock</span>
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-slate-400 py-12 text-sm">
                                <span class="material-symbols-outlined text-4xl block mb-2 text-slate-300">inventory_2</span>
                                Belum ada perangkat terdaftar di sistem.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ============================================================= -->
<!-- MODAL: DETAIL PERANGKAT -->
<!-- ============================================================= -->
<div id="modal-detail" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeDetailModal()"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg border border-slate-100 overflow-hidden max-h-[90vh] overflow-y-auto">
        <div class="bg-gradient-to-r from-primary to-secondary p-5 flex justify-between items-start sticky top-0">
            <div>
                <p class="text-white/70 text-xs uppercase tracking-wider font-semibold mb-1">Detail Perangkat</p>
                <h2 class="text-white text-xl font-black" id="detail-sn">—</h2>
            </div>
            <button onclick="closeDetailModal()" class="text-white/70 hover:text-white transition-colors mt-0.5">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Jenis & Merek</p>
                    <p class="text-sm font-bold text-slate-800" id="detail-jenis">—</p>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Tipe Perangkat</p>
                    <p class="text-sm font-mono font-bold text-slate-800" id="detail-tipe">—</p>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Status Kondisi</p>
                    <p class="text-sm font-bold" id="detail-status">—</p>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Tgl. Terdaftar</p>
                    <p class="text-sm font-semibold text-slate-600" id="detail-created">—</p>
                </div>
                <div class="col-span-2">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Alasan Rusak / Catatan</p>
                    <p class="text-sm text-slate-600" id="detail-alasan">—</p>
                </div>
            </div>
            <!-- Pelanggan & Pemasangan Info -->
            <div class="border-t border-slate-100 pt-4">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Info Pemasangan</p>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <p class="text-xs text-slate-400 mb-0.5">Tgl. Pasang Awal</p>
                        <p class="text-sm font-semibold text-slate-700" id="detail-tgl-pasang">—</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 mb-0.5">Teknisi yang Pasang</p>
                        <p class="text-sm font-bold text-primary flex items-center gap-1" id="detail-teknisi">
                            <span class="material-symbols-outlined text-sm">engineering</span> —
                        </p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-xs text-slate-400 mb-0.5">Pelanggan Aktif</p>
                        <p class="text-sm font-bold text-primary" id="detail-pelanggan-nama">—</p>
                        <p class="text-xs font-mono text-slate-500" id="detail-pelanggan-id">—</p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-xs text-slate-400 mb-0.5">Alamat Pemasangan</p>
                        <p class="text-sm text-slate-600" id="detail-pelanggan-alamat">—</p>
                    </div>
                </div>
            </div>
            <!-- Foto Bukti -->
            <div class="border-t border-slate-100 pt-4" id="detail-foto-section">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Foto Bukti Pemasangan (oleh Teknisi)</p>
                <div id="detail-foto-wrap" class="rounded-xl overflow-hidden border border-slate-100">
                    <img id="detail-foto-img" src="" alt="Foto Bukti Pemasangan"
                         class="w-full object-cover max-h-64 cursor-zoom-in"
                         onclick="window.open(this.src,'_blank')">
                </div>
                <p id="detail-foto-empty" class="text-xs text-slate-400 italic text-center py-4 hidden">
                    Belum ada foto bukti pemasangan yang diupload.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================= -->
<!-- MODAL: EDIT PERANGKAT -->
<!-- ============================================================= -->
<div id="modal-edit" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeEditModal()"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md border border-slate-100">
        <div class="p-5 border-b border-slate-100 flex justify-between items-center">
            <h2 class="text-lg font-black text-primary flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary">edit</span>
                Edit Data Perangkat
            </h2>
            <button onclick="closeEditModal()" class="text-slate-400 hover:text-slate-700 transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form id="form-edit" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Serial Number</label>
                <p id="edit-sn-display" class="font-mono font-bold text-primary text-sm bg-slate-50 rounded-xl px-4 py-2.5 border border-slate-100">—</p>
            </div>
            <div class="space-y-1">
                <label for="edit-jenis_merek" class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Jenis & Merek</label>
                <select id="edit-jenis_merek" name="jenis_merek" required
                        class="w-full h-11 border border-slate-200 rounded-xl px-4 text-sm focus:border-secondary focus:ring-4 focus:ring-secondary/15 transition-all outline-none">
                    <option value="STB Huawei">STB Huawei</option>
                    <option value="STB ZTE">STB ZTE</option>
                    <option value="Modem ZTE">Modem ZTE</option>
                    <option value="Modem Huawei">Modem Huawei</option>
                </select>
            </div>
            <div class="space-y-1">
                <label for="edit-tipe_perangkat" class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Tipe Perangkat</label>
                <input type="text" id="edit-tipe_perangkat" name="tipe_perangkat" required
                       class="w-full h-11 border border-slate-200 rounded-xl px-4 text-sm focus:border-secondary focus:ring-4 focus:ring-secondary/15 transition-all outline-none">
            </div>
            <div class="space-y-1">
                <label for="edit-alasan_rusak" class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Catatan / Alasan Rusak <span class="text-slate-300 font-normal">(opsional)</span></label>
                <textarea id="edit-alasan_rusak" name="alasan_rusak" rows="2"
                          class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:border-secondary focus:ring-4 focus:ring-secondary/15 transition-all outline-none resize-none"
                          placeholder="Kosongkan jika tidak ada catatan"></textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeEditModal()"
                        class="flex-1 h-11 border border-slate-200 text-slate-600 font-semibold rounded-xl hover:bg-slate-50 transition-all">
                    Batal
                </button>
                <button type="submit"
                        class="flex-1 h-11 bg-primary text-white font-bold rounded-xl hover:bg-secondary active:scale-[0.98] transition-all">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================================= -->
<!-- MODAL: DELETE KONFIRMASI -->
<!-- ============================================================= -->
<div id="modal-delete" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeDeleteModal()"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm border border-slate-100">
        <div class="p-6 text-center">
            <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4 border-2 border-red-100">
                <span class="material-symbols-outlined text-3xl text-red-500">delete_forever</span>
            </div>
            <h3 class="text-lg font-black text-slate-800 mb-1">Hapus Perangkat?</h3>
            <p class="text-sm text-slate-500 mb-2">Anda akan menghapus perangkat:</p>
            <p id="delete-device-label" class="text-sm font-bold text-primary bg-slate-50 rounded-lg px-3 py-2 font-mono mb-4">—</p>
            <p class="text-xs text-red-500 font-semibold mb-6">Tindakan ini tidak bisa dibatalkan.</p>
            <div class="flex gap-3">
                <button type="button" onclick="closeDeleteModal()"
                        class="flex-1 h-11 border border-slate-200 text-slate-600 font-semibold rounded-xl hover:bg-slate-50 transition-all">
                    Batal
                </button>
                <form id="form-delete" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="w-full h-11 bg-red-500 text-white font-bold rounded-xl hover:bg-red-600 active:scale-[0.98] transition-all px-6">
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function openDetailModal(data) {
    document.getElementById('detail-sn').textContent = data.serial_number;
    document.getElementById('detail-jenis').textContent = data.jenis_merek;
    document.getElementById('detail-tipe').textContent = data.tipe_perangkat;
    document.getElementById('detail-status').textContent = data.status_kondisi === 'Ready' ? '⬜ Ready di Gudang' : (data.status_kondisi === 'Terpasang' ? '🔵 Terpasang' : (data.status_kondisi === 'Rusak' ? '🔴 Rusak' : '🟠 Dismantled'));
    document.getElementById('detail-created').textContent = data.created_at;
    document.getElementById('detail-alasan').textContent = data.alasan_rusak || '—';
    document.getElementById('detail-tgl-pasang').textContent = data.tanggal_pasang_awal || '—';
    // Teknisi
    const teknisiEl = document.getElementById('detail-teknisi');
    teknisiEl.innerHTML = '<span class="material-symbols-outlined text-sm">engineering</span> ' + (data.teknisi_nama || '—');
    document.getElementById('detail-pelanggan-nama').textContent = data.pelanggan_nama || '—';
    document.getElementById('detail-pelanggan-id').textContent = data.pelanggan_id !== '—' ? '#' + data.pelanggan_id : '—';
    document.getElementById('detail-pelanggan-alamat').textContent = data.pelanggan_alamat || '—';
    // Foto Bukti
    const fotoImg = document.getElementById('detail-foto-img');
    const fotoEmpty = document.getElementById('detail-foto-empty');
    const fotoWrap = document.getElementById('detail-foto-wrap');
    if (data.foto_bukti) {
        fotoImg.src = '/' + data.foto_bukti;
        fotoWrap.classList.remove('hidden');
        fotoEmpty.classList.add('hidden');
    } else {
        fotoImg.src = '';
        fotoWrap.classList.add('hidden');
        fotoEmpty.classList.remove('hidden');
    }
    document.getElementById('modal-detail').classList.remove('hidden');
}

function closeDetailModal() {
    document.getElementById('modal-detail').classList.add('hidden');
}

function openEditModal(data) {
    document.getElementById('edit-sn-display').textContent = data.serial_number;
    document.getElementById('form-edit').action = '/admin/devices/' + encodeURIComponent(data.serial_number);
    document.getElementById('edit-jenis_merek').value = data.jenis_merek;
    document.getElementById('edit-tipe_perangkat').value = data.tipe_perangkat;
    document.getElementById('edit-alasan_rusak').value = data.alasan_rusak || '';
    document.getElementById('modal-edit').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('modal-edit').classList.add('hidden');
}

function openDeleteModal(serial_number, jenis_merek) {
    document.getElementById('delete-device-label').textContent = serial_number + ' — ' + jenis_merek;
    document.getElementById('form-delete').action = '/admin/devices/' + encodeURIComponent(serial_number);
    document.getElementById('modal-delete').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('modal-delete').classList.add('hidden');
}

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDetailModal();
        closeEditModal();
        closeDeleteModal();
    }
});
</script>
@endsection
