@extends('layouts.app')

@section('title', 'Connexio - Manajemen Pelanggan')

@section('content')
<!-- Page Header -->
<header class="mb-lg flex justify-between items-end border-b border-outline-variant/10 pb-4">
    <div>
        <p class="font-label-caps text-xs text-secondary uppercase tracking-widest mb-1">Customer Management</p>
        <h1 class="text-3xl font-black text-primary tracking-tight">Data Pelanggan</h1>
    </div>
    <div class="flex items-center gap-3">
        <div class="flex items-center gap-2 text-xs text-slate-500">
            <span class="material-symbols-outlined text-sm text-secondary">group</span>
            <span><strong class="text-primary">{{ $customers->count() }}</strong> total pelanggan terdaftar</span>
        </div>
        <div class="relative">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-lg">search</span>
            <input type="text" id="customer-search" placeholder="Cari nama / ID / telepon..."
                   class="pl-9 pr-4 h-9 border border-slate-200 rounded-xl text-sm focus:border-secondary focus:ring-4 focus:ring-secondary/15 transition-all outline-none w-64">
        </div>
        <!-- Bulk Import Button -->
        <a href="{{ route('admin.import.show') }}" class="flex items-center gap-2 px-4 py-2 bg-secondary/10 text-secondary rounded-xl text-sm font-bold hover:bg-secondary/20 transition-all shadow-sm">
            <span class="material-symbols-outlined text-[18px]">publish</span>
            Bulk Import
        </a>
    </div>
</header>

<!-- Stats Cards -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    @php
        $active = $customers->where('status_langganan', 'Active')->count();
        $suspended = $customers->where('status_langganan', 'Suspended')->count();
        $terminated = $customers->where('status_langganan', 'Terminated')->count();
    @endphp
    <div class="glass-card p-4 rounded-xl bg-white border border-slate-100 shadow-[0px_2px_10px_rgba(30,58,138,0.04)]">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center">
                <span class="material-symbols-outlined text-primary text-lg">group</span>
            </div>
            <div>
                <p class="text-2xl font-black text-primary">{{ $customers->count() }}</p>
                <p class="text-xs text-slate-400 font-semibold">Total Pelanggan</p>
            </div>
        </div>
    </div>
    <div class="glass-card p-4 rounded-xl bg-white border border-slate-100 shadow-[0px_2px_10px_rgba(30,58,138,0.04)]">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center">
                <span class="material-symbols-outlined text-blue-600 text-lg">check_circle</span>
            </div>
            <div>
                <p class="text-2xl font-black text-blue-600">{{ $active }}</p>
                <p class="text-xs text-slate-400 font-semibold">Aktif</p>
            </div>
        </div>
    </div>
    <div class="glass-card p-4 rounded-xl bg-white border border-slate-100 shadow-[0px_2px_10px_rgba(30,58,138,0.04)]">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-amber-50 flex items-center justify-center">
                <span class="material-symbols-outlined text-amber-600 text-lg">pause_circle</span>
            </div>
            <div>
                <p class="text-2xl font-black text-amber-600">{{ $suspended }}</p>
                <p class="text-xs text-slate-400 font-semibold">Suspended</p>
            </div>
        </div>
    </div>
    <div class="glass-card p-4 rounded-xl bg-white border border-slate-100 shadow-[0px_2px_10px_rgba(30,58,138,0.04)]">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center">
                <span class="material-symbols-outlined text-red-500 text-lg">cancel</span>
            </div>
            <div>
                <p class="text-2xl font-black text-red-500">{{ $terminated }}</p>
                <p class="text-xs text-slate-400 font-semibold">Terminated</p>
            </div>
        </div>
    </div>
</div>

<!-- Table Card -->
<div class="glass-card rounded-xl bg-white border border-slate-100 shadow-[0px_4px_20px_rgba(30,58,138,0.03)]">
    <div class="p-6 border-b border-slate-100">
        <h3 class="text-lg font-bold text-primary flex items-center gap-2">
            <span class="material-symbols-outlined text-secondary">person_search</span>
            Daftar Semua Pelanggan
        </h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left" id="customers-table">
            <thead>
                <tr class="bg-slate-50/50">
                    <th class="px-5 py-3 text-xs font-bold text-slate-400 uppercase tracking-wider">ID Pelanggan</th>
                    <th class="px-5 py-3 text-xs font-bold text-slate-400 uppercase tracking-wider">Nama</th>
                    <th class="px-5 py-3 text-xs font-bold text-slate-400 uppercase tracking-wider">No. Telepon</th>
                    <th class="px-5 py-3 text-xs font-bold text-slate-400 uppercase tracking-wider">Alamat</th>
                    <th class="px-5 py-3 text-xs font-bold text-slate-400 uppercase tracking-wider">Status</th>
                    <th class="px-5 py-3 text-xs font-bold text-slate-400 uppercase tracking-wider">Transaksi</th>
                    <th class="px-5 py-3 text-xs font-bold text-slate-400 uppercase tracking-wider text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100" id="customers-tbody">
                @forelse($customers as $customer)
                    @php
                        $hasActive = $customer->assignments
                            ->where('tipe_alur', 'Pengambilan')
                            ->where('status_approval', 'Approved_by_Admin')
                            ->count() > 0;
                    @endphp
                    <tr class="hover:bg-slate-50/50 transition-colors customer-row"
                        data-search="{{ strtolower($customer->id_pelanggan . ' ' . $customer->nama_pelanggan . ' ' . $customer->no_telepon) }}">
                        <td class="px-5 py-3.5 font-mono text-xs font-bold text-primary">{{ $customer->id_pelanggan }}</td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-full bg-primary/10 text-primary font-bold text-xs flex items-center justify-center shrink-0">
                                    {{ strtoupper(substr($customer->nama_pelanggan, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800">{{ $customer->nama_pelanggan }}</p>
                                    @if($hasActive)
                                        <span class="text-[10px] bg-blue-50 text-blue-600 font-bold rounded px-1.5 py-0.5 border border-blue-100">Ada Perangkat Aktif</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3.5 text-sm text-slate-600 font-semibold">{{ $customer->no_telepon }}</td>
                        <td class="px-5 py-3.5 text-xs text-slate-500 max-w-[200px]">
                            <div class="truncate" title="{{ $customer->alamat_pemasangan }}">{{ $customer->alamat_pemasangan }}</div>
                        </td>
                        <td class="px-5 py-3.5">
                            @if($customer->status_langganan === 'Active')
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-blue-50 border border-blue-200 text-blue-700">Active</span>
                            @elseif($customer->status_langganan === 'Suspended')
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 border border-amber-200 text-amber-700">Suspended</span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-red-50 border border-red-200 text-red-600">Terminated</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-xs text-slate-500 font-semibold">
                            {{ $customer->assignments_count ?? 0 }} transaksi
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center justify-center gap-1">
                                <!-- Detail -->
                                <button type="button"
                                        onclick="openDetailCustomer({{ json_encode([
                                            'id_pelanggan' => $customer->id_pelanggan,
                                            'nama_pelanggan' => $customer->nama_pelanggan,
                                            'no_telepon' => $customer->no_telepon,
                                            'alamat_pemasangan' => $customer->alamat_pemasangan,
                                            'status_langganan' => $customer->status_langganan,
                                            'latitude' => $customer->latitude ?? '—',
                                            'longitude' => $customer->longitude ?? '—',
                                            'total_transaksi' => $customer->assignments_count ?? 0,
                                            'has_active' => $hasActive,
                                            'created_at' => $customer->created_at->format('d M Y'),
                                        ]) }})"
                                        class="w-8 h-8 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 flex items-center justify-center transition-all" title="Detail Pelanggan">
                                    <span class="material-symbols-outlined text-base">visibility</span>
                                </button>
                                <!-- Edit -->
                                <button type="button"
                                        onclick="openEditCustomer({{ json_encode([
                                            'id_pelanggan' => $customer->id_pelanggan,
                                            'nama_pelanggan' => $customer->nama_pelanggan,
                                            'no_telepon' => $customer->no_telepon,
                                            'alamat_pemasangan' => $customer->alamat_pemasangan,
                                            'status_langganan' => $customer->status_langganan,
                                            'latitude' => $customer->latitude ?? '',
                                            'longitude' => $customer->longitude ?? '',
                                        ]) }})"
                                        class="w-8 h-8 rounded-lg bg-primary/10 hover:bg-primary/20 text-primary flex items-center justify-center transition-all" title="Edit Pelanggan">
                                    <span class="material-symbols-outlined text-base">edit</span>
                                </button>
                                <!-- Delete -->
                                @if(!$hasActive)
                                    <button type="button"
                                            onclick="openDeleteCustomer('{{ $customer->id_pelanggan }}', '{{ $customer->nama_pelanggan }}')"
                                            class="w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 text-red-500 flex items-center justify-center transition-all" title="Hapus Pelanggan">
                                        <span class="material-symbols-outlined text-base">delete</span>
                                    </button>
                                @else
                                    <div class="w-8 h-8 rounded-lg bg-slate-50 text-slate-300 flex items-center justify-center cursor-not-allowed" title="Tidak bisa dihapus, ada perangkat aktif">
                                        <span class="material-symbols-outlined text-base">lock</span>
                                    </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-slate-400 py-16 text-sm">
                            <span class="material-symbols-outlined text-5xl block mb-3 text-slate-200">group</span>
                            <p class="font-semibold">Belum ada pelanggan terdaftar.</p>
                            <p class="text-xs mt-1">Gunakan menu Bulk Import untuk import data massal.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- ============================================================= -->
<!-- MODAL: DETAIL PELANGGAN -->
<!-- ============================================================= -->
<div id="modal-detail-cust" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeDetailCustomer()"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg border border-slate-100 overflow-hidden">
        <div class="bg-gradient-to-r from-primary to-secondary p-5 flex justify-between items-start">
            <div>
                <p class="text-white/70 text-xs uppercase tracking-wider font-semibold mb-1">Detail Pelanggan</p>
                <h2 class="text-white text-xl font-black" id="dcust-nama">—</h2>
                <p class="text-white/60 text-xs font-mono mt-0.5" id="dcust-id">—</p>
            </div>
            <button onclick="closeDetailCustomer()" class="text-white/70 hover:text-white transition-colors mt-0.5">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">No. Telepon</p>
                    <p class="text-sm font-bold text-slate-800" id="dcust-telp">—</p>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Status Langganan</p>
                    <p class="text-sm font-bold" id="dcust-status">—</p>
                </div>
                <div class="col-span-2">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Alamat Pemasangan</p>
                    <p class="text-sm text-slate-700" id="dcust-alamat">—</p>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Latitude</p>
                    <p class="text-sm font-mono text-slate-600" id="dcust-lat">—</p>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Longitude</p>
                    <p class="text-sm font-mono text-slate-600" id="dcust-lng">—</p>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Total Transaksi</p>
                    <p class="text-sm font-bold text-primary" id="dcust-tx">—</p>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Terdaftar Sejak</p>
                    <p class="text-sm text-slate-600" id="dcust-created">—</p>
                </div>
                <div class="col-span-2">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Perangkat Aktif</p>
                    <p class="text-sm font-bold" id="dcust-active">—</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================= -->
<!-- MODAL: EDIT PELANGGAN -->
<!-- ============================================================= -->
<div id="modal-edit-cust" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeEditCustomer()"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md border border-slate-100">
        <div class="p-5 border-b border-slate-100 flex justify-between items-center">
            <h2 class="text-lg font-black text-primary flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary">edit</span>
                Edit Data Pelanggan
            </h2>
            <button onclick="closeEditCustomer()" class="text-slate-400 hover:text-slate-700 transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form id="form-edit-cust" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block">ID Pelanggan</label>
                <p id="ecust-id-display" class="font-mono font-bold text-primary text-sm bg-slate-50 rounded-xl px-4 py-2.5 border border-slate-100">—</p>
            </div>
            <div class="space-y-1">
                <label for="ecust-nama" class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Nama Pelanggan</label>
                <input type="text" id="ecust-nama" name="nama_pelanggan" required
                       class="w-full h-11 border border-slate-200 rounded-xl px-4 text-sm focus:border-secondary focus:ring-4 focus:ring-secondary/15 transition-all outline-none">
            </div>
            <div class="space-y-1">
                <label for="ecust-telp" class="text-xs font-bold text-slate-500 uppercase tracking-wider block">No. Telepon</label>
                <input type="text" id="ecust-telp" name="no_telepon" required
                       class="w-full h-11 border border-slate-200 rounded-xl px-4 text-sm focus:border-secondary focus:ring-4 focus:ring-secondary/15 transition-all outline-none">
            </div>
            <div class="space-y-1">
                <label for="ecust-alamat" class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Alamat Pemasangan</label>
                <textarea id="ecust-alamat" name="alamat_pemasangan" required rows="2"
                          class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:border-secondary focus:ring-4 focus:ring-secondary/15 transition-all outline-none resize-none"></textarea>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div class="space-y-1">
                    <label for="ecust-lat" class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Latitude <span class="text-slate-300 font-normal">(opsional)</span></label>
                    <input type="number" step="any" id="ecust-lat" name="latitude"
                           class="w-full h-11 border border-slate-200 rounded-xl px-4 text-sm focus:border-secondary focus:ring-4 focus:ring-secondary/15 transition-all outline-none font-mono">
                </div>
                <div class="space-y-1">
                    <label for="ecust-lng" class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Longitude <span class="text-slate-300 font-normal">(opsional)</span></label>
                    <input type="number" step="any" id="ecust-lng" name="longitude"
                           class="w-full h-11 border border-slate-200 rounded-xl px-4 text-sm focus:border-secondary focus:ring-4 focus:ring-secondary/15 transition-all outline-none font-mono">
                </div>
            </div>
            <div class="space-y-1">
                <label for="ecust-status" class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Status Langganan</label>
                <select id="ecust-status" name="status_langganan" required
                        class="w-full h-11 border border-slate-200 rounded-xl px-4 text-sm focus:border-secondary focus:ring-4 focus:ring-secondary/15 transition-all outline-none">
                    <option value="Active">Active</option>
                    <option value="Suspended">Suspended</option>
                    <option value="Terminated">Terminated</option>
                </select>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeEditCustomer()"
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
<!-- MODAL: DELETE PELANGGAN -->
<!-- ============================================================= -->
<div id="modal-delete-cust" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeDeleteCustomer()"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm border border-slate-100">
        <div class="p-6 text-center">
            <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4 border-2 border-red-100">
                <span class="material-symbols-outlined text-3xl text-red-500">person_remove</span>
            </div>
            <h3 class="text-lg font-black text-slate-800 mb-1">Hapus Pelanggan?</h3>
            <p class="text-sm text-slate-500 mb-2">Anda akan menghapus pelanggan:</p>
            <p id="delete-cust-label" class="text-sm font-bold text-primary bg-slate-50 rounded-lg px-3 py-2 mb-4">—</p>
            <p class="text-xs text-red-500 font-semibold mb-6">Semua data transaksi pelanggan ini juga akan terdampak. Tindakan ini tidak bisa dibatalkan.</p>
            <div class="flex gap-3">
                <button type="button" onclick="closeDeleteCustomer()"
                        class="flex-1 h-11 border border-slate-200 text-slate-600 font-semibold rounded-xl hover:bg-slate-50 transition-all">
                    Batal
                </button>
                <form id="form-delete-cust" method="POST">
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
// ─── Detail ───
function openDetailCustomer(data) {
    document.getElementById('dcust-nama').textContent = data.nama_pelanggan;
    document.getElementById('dcust-id').textContent = '#' + data.id_pelanggan;
    document.getElementById('dcust-telp').textContent = data.no_telepon;
    document.getElementById('dcust-alamat').textContent = data.alamat_pemasangan;
    document.getElementById('dcust-lat').textContent = data.latitude || '—';
    document.getElementById('dcust-lng').textContent = data.longitude || '—';
    document.getElementById('dcust-tx').textContent = data.total_transaksi + ' transaksi';
    document.getElementById('dcust-created').textContent = data.created_at;
    document.getElementById('dcust-active').textContent = data.has_active ? '🔵 Memiliki perangkat aktif terpasang' : '⬜ Tidak ada perangkat aktif';

    const statusEl = document.getElementById('dcust-status');
    statusEl.textContent = data.status_langganan;
    statusEl.className = 'text-sm font-bold ' + (data.status_langganan === 'Active' ? 'text-blue-600' : (data.status_langganan === 'Suspended' ? 'text-amber-600' : 'text-red-500'));

    document.getElementById('modal-detail-cust').classList.remove('hidden');
}
function closeDetailCustomer() { document.getElementById('modal-detail-cust').classList.add('hidden'); }

// ─── Edit ───
function openEditCustomer(data) {
    document.getElementById('ecust-id-display').textContent = data.id_pelanggan;
    document.getElementById('form-edit-cust').action = '/admin/customers/' + encodeURIComponent(data.id_pelanggan);
    document.getElementById('ecust-nama').value = data.nama_pelanggan;
    document.getElementById('ecust-telp').value = data.no_telepon;
    document.getElementById('ecust-alamat').value = data.alamat_pemasangan;
    document.getElementById('ecust-lat').value = data.latitude || '';
    document.getElementById('ecust-lng').value = data.longitude || '';
    document.getElementById('ecust-status').value = data.status_langganan;
    document.getElementById('modal-edit-cust').classList.remove('hidden');
}
function closeEditCustomer() { document.getElementById('modal-edit-cust').classList.add('hidden'); }

// ─── Delete ───
function openDeleteCustomer(id, nama) {
    document.getElementById('delete-cust-label').textContent = nama + ' (#' + id + ')';
    document.getElementById('form-delete-cust').action = '/admin/customers/' + encodeURIComponent(id);
    document.getElementById('modal-delete-cust').classList.remove('hidden');
}
function closeDeleteCustomer() { document.getElementById('modal-delete-cust').classList.add('hidden'); }

// ─── Live Search ───
document.getElementById('customer-search').addEventListener('input', function() {
    const q = this.value.toLowerCase().trim();
    document.querySelectorAll('.customer-row').forEach(function(row) {
        const data = row.getAttribute('data-search');
        row.style.display = (!q || data.includes(q)) ? '' : 'none';
    });
});

// Close on Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDetailCustomer();
        closeEditCustomer();
        closeDeleteCustomer();
    }
});
</script>
@endsection
