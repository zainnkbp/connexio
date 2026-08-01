@extends('layouts.mobile')

@section('title', 'Connexio - Dashboard Teknisi')
@section('nav_home_active', 'active')

@section('content')
<!-- Overview Section -->
<div class="mb-6">
    <h2 class="text-2xl font-bold text-slate-800">Overview Operasional</h2>
    <p class="text-xs text-slate-400 font-semibold mt-0.5">Pantau status penugasan Anda hari ini.</p>
</div>

<!-- Horizontal Scrolling Stats Card -->
<section class="flex overflow-x-auto gap-4 pb-4 hide-scrollbar -mx-margin-mobile px-margin-mobile">
    <!-- Pending Task -->
    <div class="flex-shrink-0 w-56 bg-white border border-slate-100 rounded-xl p-4 shadow-[0px_4px_20px_rgba(0,83,148,0.02)] flex flex-col justify-between">
        <div class="flex justify-between items-start mb-2">
            <span class="material-symbols-outlined text-slate-400">calendar_today</span>
            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Hari Ini</span>
        </div>
        <div class="text-4xl font-extrabold text-slate-800">{{ sprintf("%02d", $pendingCount) }}</div>
        <div class="text-xs text-slate-400 font-bold mt-1">Pending Tasks</div>
    </div>
    
    <!-- In Hand Task -->
    <div class="flex-shrink-0 w-56 bg-primary-container border border-primary text-white rounded-xl p-4 shadow-[0px_4px_20px_rgba(0,83,148,0.06)] flex flex-col justify-between">
        <div class="flex justify-between items-start mb-2">
            <span class="material-symbols-outlined text-white">engineering</span>
            <span class="text-[10px] text-white/80 font-bold uppercase tracking-wider">Aktif</span>
        </div>
        <div class="text-4xl font-extrabold">{{ sprintf("%02d", $inHandCount) }}</div>
        <div class="text-xs text-white/80 font-bold mt-1">In Hand Tasks</div>
    </div>

    <!-- Completed Task -->
    <div class="flex-shrink-0 w-56 bg-white border border-slate-100 rounded-xl p-4 shadow-[0px_4px_20px_rgba(0,83,148,0.02)] flex flex-col justify-between">
        <div class="flex justify-between items-start mb-2">
            <span class="material-symbols-outlined text-primary">task_alt</span>
            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Selesai</span>
        </div>
        <div class="text-4xl font-extrabold text-slate-800">{{ sprintf("%02d", $completedCount) }}</div>
        <div class="text-xs text-slate-400 font-bold mt-1">Completed</div>
    </div>
</section>

<!-- Main List: Tugas Hari Ini -->
<section class="space-y-4 mt-2">
    <div class="flex justify-between items-end">
        <h3 class="text-base font-bold text-slate-800 flex items-center gap-1.5">
            <span class="material-symbols-outlined text-primary text-xl">list_alt</span>
            Tugas Hari Ini
        </h3>
    </div>

    <div class="space-y-4">
        @forelse($assignments as $groupKey => $group)
            @php $assign = $group->first(); @endphp
            <!-- Accordion Card -->
            <div class="bg-white border border-slate-100 rounded-2xl overflow-hidden transition-all duration-300 shadow-sm" 
                 id="assign-card-{{ $assign->id_transaksi }}">
                
                <div class="p-4 flex justify-between items-center cursor-pointer select-none" 
                     onclick="toggleAccordion('{{ $assign->id_transaksi }}')">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                            <span class="material-symbols-outlined text-xl">
                                @if($assign->tipe_alur === 'Pengambilan')
                                    local_shipping
                                @elseif($assign->tipe_alur === 'Pengembalian')
                                    assignment_return
                                @else
                                    build
                                @endif
                            </span>
                        </div>
                        <div>
                            <div class="font-bold text-sm text-slate-800 leading-tight">
                                {{ $assign->customer->nama_pelanggan ?? 'Bypass Pelanggan' }}
                            </div>
                            <div class="text-[11px] text-slate-400 font-semibold mt-0.5">
                                {{ $assign->tipe_alur }} ({{ $assign->id_pelanggan }})
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        @if($assign->status_approval === 'Pending')
                            @if($assign->serial_number)
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-amber-100 text-amber-800">Ready PickUp</span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-blue-100 text-blue-800">Pending Approval</span>
                            @endif
                        @elseif($assign->status_approval === 'In_Hand')
                            <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-primary text-white">In Hand</span>
                        @elseif($assign->status_approval === 'Approved_by_Admin')
                            <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-slate-100 text-slate-500">Selesai</span>
                        @elseif($assign->status_approval === 'Rejected')
                            <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-red-100 text-red-800">Ditolak</span>
                        @endif
                        
                        <div class="flex items-center text-slate-400 hover:text-primary transition-colors cursor-pointer">
                            <span class="text-[10px] font-bold mr-0.5 hidden sm:inline-block">Detail</span>
                            <span class="material-symbols-outlined accordion-icon transition-transform duration-200">expand_more</span>
                        </div>
                    </div>
                </div>

                <div class="accordion-content border-t border-slate-50 bg-slate-50/30" 
                     id="assign-body-{{ $assign->id_transaksi }}">
                    <div class="p-4 space-y-3 text-xs text-slate-600 font-medium">
                        <div class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-sm text-slate-400 mt-0.5">location_on</span>
                            <div><strong>Alamat:</strong> {{ $assign->customer->alamat_pemasangan ?? '-' }}</div>
                        </div>
                        <div class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-sm text-slate-400 mt-0.5">call</span>
                            <div><strong>Telepon:</strong> {{ $assign->customer->no_telepon ?? '-' }}</div>
                        </div>

                        @if($assign->keterangan)
                        <div class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-sm text-slate-400 mt-0.5">info</span>
                            <div><strong>Detail Request:</strong> <span class="text-blue-600 font-bold bg-blue-50 px-1 rounded">{{ $assign->keterangan }}</span></div>
                        </div>
                        @endif
                        
                        @if($assign->serial_number)
                            <div class="flex items-start gap-2">
                                <span class="material-symbols-outlined text-sm text-slate-400 mt-0.5">tag</span>
                                <div><strong>Serial Number:</strong> 
                                    <div class="flex flex-wrap gap-1 mt-1">
                                    @foreach($group as $gItem)
                                        @if($gItem->serial_number)
                                            <code class="bg-slate-100 px-1.5 py-0.5 rounded font-mono font-bold">{{ $gItem->serial_number }}</code>
                                        @endif
                                    @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($assign->tipe_alur === 'Pengembalian' && $assign->alasan_rusak)
                            <div class="flex items-start gap-2">
                                <span class="material-symbols-outlined text-sm text-slate-400 mt-0.5">warning</span>
                                <div><strong>Alasan Rusak:</strong> <span class="italic text-red-600">"{{ $assign->alasan_rusak }}"</span></div>
                            </div>
                        @endif

                        @if($assign->catatan_admin)
                            <div class="flex items-start gap-2 p-2 bg-amber-50/50 rounded-lg border border-amber-100 mt-2">
                                <span class="material-symbols-outlined text-sm text-amber-500 mt-0.5">notification_important</span>
                                <div><strong class="text-amber-800">Catatan dari Admin:</strong> <span class="italic text-amber-700 block mt-0.5">{{ $assign->catatan_admin }}</span></div>
                            </div>
                        @endif

                        @if($assign->status_approval === 'Approved_by_Admin')
                            <div class="pt-3 border-t border-slate-100">
                                <span class="px-2 py-1 bg-green-50 text-green-700 font-bold text-[10px] rounded inline-flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[12px]">check_circle</span> 
                                    Telah diselesaikan & di-ACC
                                </span>
                                @if($assign->foto_bukti)
                                <div class="mt-3">
                                    <strong class="text-slate-500">Foto Bukti Lapangan:</strong>
                                    <img src="{{ asset('storage/' . $assign->foto_bukti) }}" class="w-full rounded-xl mt-2 h-40 object-cover border border-slate-200 shadow-sm" alt="Foto Bukti">
                                </div>
                                @endif
                            </div>
                        @endif

                        <!-- ACTIONS FOR PENDING BUT ASSIGNED BY ADMIN (READY TO PICK UP) -->
                        @if($assign->tipe_alur === 'Pengambilan' && $assign->status_approval === 'Pending' && !empty($assign->serial_number))
                            <div class="pt-3 border-t border-slate-100 space-y-3">
                                <p class="text-[10px] text-slate-400 leading-relaxed">Perangkat telah dialokasikan Admin. Silakan ambil fisik perangkat di gudang dan konfirmasi:</p>
                                <form action="{{ route('teknisi.pickup-group') }}" method="POST">
                                    @csrf
                                    @foreach($group as $gItem)
                                        <input type="hidden" name="assignment_ids[]" value="{{ $gItem->id_transaksi }}">
                                    @endforeach
                                    <button type="submit" 
                                            class="w-full h-11 bg-primary text-white font-bold rounded-xl text-xs shadow-md shadow-primary/10 hover:brightness-110 active:scale-[0.98] transition-all">
                                        Konfirmasi Pengambilan Gudang
                                    </button>
                                </form>
                            </div>
                        @endif

                        <!-- ACTIONS FOR IN HAND DEPLOYMENT (MAP & COMPLETION FORM) -->
                        @if($assign->tipe_alur === 'Pengambilan' && $assign->status_approval === 'In_Hand')
                            <div class="pt-3 border-t border-slate-100 space-y-3">
                                <!-- Google Maps Redirect Button -->
                                @if(!empty($assign->customer->latitude) && !empty($assign->customer->longitude))
                                    <a href="https://www.google.com/maps/search/?api=1&query={{ $assign->customer->latitude }},{{ $assign->customer->longitude }}" 
                                       target="_blank" 
                                       class="w-full h-11 border border-primary text-primary font-bold rounded-xl text-xs flex items-center justify-center gap-1.5 hover:bg-primary/5 transition-all">
                                        <span class="material-symbols-outlined text-base">map</span>
                                        Buka Navigasi Google Maps
                                    </a>
                                @else
                                    <button class="w-full h-11 bg-slate-100 text-slate-400 rounded-xl text-xs font-bold cursor-not-allowed flex items-center justify-center gap-1.5" disabled>
                                        <span class="material-symbols-outlined text-base">location_off</span>
                                        Peta Dinonaktifkan (Koordinat Pelanggan Kosong)
                                    </button>
                                @endif

                                <!-- Completion Form -->
                                <form action="{{ route('teknisi.complete-group') }}" 
                                      method="POST" 
                                      enctype="multipart/form-data" 
                                      class="space-y-4 pt-2">
                                    @csrf
                                    @foreach($group as $gItem)
                                        <input type="hidden" name="assignment_ids[]" value="{{ $gItem->id_transaksi }}">
                                    @endforeach
                                    
                                    <!-- GPS Fields if Customer Coordinates are empty -->
                                    @if(empty($assign->customer->latitude) || empty($assign->customer->longitude))
                                        <div class="p-4 bg-primary/5 border border-dashed border-primary/20 rounded-xl space-y-3">
                                            <p class="font-bold text-[11px] text-primary">Koordinat Pelanggan Kosong. Harap Isi Lokasi Pemasangan:</p>
                                            <div class="grid grid-cols-2 gap-2">
                                                <input type="text" name="latitude" id="lat-{{ $assign->id_transaksi }}" required 
                                                       class="w-full h-10 border border-slate-200 bg-white rounded-lg px-3 text-xs outline-none" placeholder="Lat">
                                                <input type="text" name="longitude" id="lng-{{ $assign->id_transaksi }}" required 
                                                       class="w-full h-10 border border-slate-200 bg-white rounded-lg px-3 text-xs outline-none" placeholder="Lng">
                                            </div>
                                            <button type="button" 
                                                    class="w-full h-10 border border-primary text-primary rounded-lg text-xs font-bold hover:bg-primary hover:text-white transition-all flex items-center justify-center gap-1.5" 
                                                    onclick="detectGPS('{{ $assign->id_transaksi }}')">
                                                <span class="material-symbols-outlined text-base">my_location</span> Deteksi GPS Otomatis
                                            </button>
                                        </div>
                                    @endif

                                    <div class="space-y-1">
                                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Upload Foto Bukti Pemasangan</label>
                                        <div class="group relative cursor-pointer border border-slate-200 hover:border-primary bg-white hover:bg-primary/5 rounded-xl p-4 transition-all duration-300 flex flex-col items-center justify-center">
                                            <input type="file" name="foto_bukti" accept="image/*" required 
                                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                                   onchange="previewImage(this, 'preview-img-{{ $assign->id_transaksi }}', 'preview-container-{{ $assign->id_transaksi }}', 'icon-container-{{ $assign->id_transaksi }}')">
                                            
                                            <!-- Default icon container -->
                                            <div id="icon-container-{{ $assign->id_transaksi }}" class="flex flex-col items-center justify-center py-2">
                                                <span class="material-symbols-outlined text-2xl text-slate-400 group-hover:text-primary mb-1">photo_camera</span>
                                                <span class="text-[11px] font-bold text-slate-500 group-hover:text-primary">Ambil Foto Pemasangan</span>
                                            </div>

                                            <!-- Hidden image preview container -->
                                            <div id="preview-container-{{ $assign->id_transaksi }}" class="hidden w-full h-32 relative">
                                                <img id="preview-img-{{ $assign->id_transaksi }}" class="w-full h-full object-cover rounded-lg shadow-sm border border-slate-200">
                                                <div class="absolute inset-0 bg-black/40 hidden items-center justify-center rounded-lg group-hover:flex">
                                                    <span class="text-white font-bold text-xs flex items-center"><span class="material-symbols-outlined text-sm mr-1">sync</span> Ganti Foto</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit" 
                                            class="w-full h-12 bg-primary text-white font-bold rounded-xl text-xs shadow-md shadow-primary/10 hover:brightness-110 active:scale-[0.98] transition-all">
                                        Selesai & Aktifkan Perangkat
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="p-8 text-center text-slate-400 text-sm font-semibold bg-white border border-slate-100 rounded-2xl shadow-sm">
                Belum ada tugas terdaftar untuk Anda hari ini.
            </div>
        @endforelse
    </div>
</section>

<!-- ================= MODALS / BOTTOM SHEETS SECTION ================= -->
@section('modals')

<!-- 1. REQUEST DEPLOYMENT MODAL -->
<div id="request-modal" class="fixed inset-0 z-50 glass-overlay flex items-end justify-center" style="display: none;">
    <div class="bottom-sheet w-full max-w-2xl bg-white rounded-t-[32px] shadow-2xl p-6 md:p-8 max-h-[90vh] overflow-y-auto">
        <!-- Drag Handle -->
        <div class="w-12 h-1.5 bg-slate-200 rounded-full mx-auto mb-6"></div>
        
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg font-bold text-primary flex items-center gap-1.5">
                <span class="material-symbols-outlined text-secondary">inventory_2</span>
                Request Perangkat Baru
            </h2>
            <button class="w-10 h-10 rounded-full flex items-center justify-center hover:bg-slate-100 transition-colors text-slate-400" 
                    onclick="closeTechModal('request-modal')">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <form action="{{ route('teknisi.request-deployment') }}" method="POST" class="space-y-4">
            @csrf
            
            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block">ID Pelanggan</label>
                <div class="flex gap-2">
                    <input type="text" id="req_id_pelanggan" name="id_pelanggan" required 
                           class="w-full h-11 border border-slate-200 rounded-xl px-4 text-sm focus:border-primary focus:ring-4 focus:ring-primary/15 transition-all outline-none font-mono" 
                           placeholder="Masukkan ID Pelanggan">
                    <button type="button" class="h-11 px-6 bg-slate-100 border border-slate-200 hover:bg-slate-200 rounded-xl text-sm font-bold text-slate-700 transition-all" 
                            onclick="checkCustomerForRequest()">Cek</button>
                </div>
            </div>

            <!-- Hidden Customer details showing if verified -->
            <div id="req-cust-details" class="p-4 bg-primary/5 border border-primary/10 rounded-xl text-xs text-slate-600 font-semibold space-y-1" style="display: none;">
                <div><strong>Nama:</strong> <span id="req-cust-name" class="text-slate-800"></span></div>
                <div><strong>Alamat:</strong> <span id="req-cust-address" class="text-slate-800"></span></div>
                <div><strong>No Telp:</strong> <span id="req-cust-phone" class="text-slate-800"></span></div>
            </div>

            <!-- Device Request List -->
            <div id="req-devices-section" class="space-y-3 pt-2">
                <div class="flex justify-between items-center">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Daftar Perangkat Direquest</label>
                    <button type="button" onclick="addRequestDeviceRow()" class="text-[11px] font-bold text-primary hover:underline flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">add_circle</span> Tambah
                    </button>
                </div>
                <div id="req-device-rows" class="space-y-3">
                    <div class="req-device-row flex gap-2 items-center" id="req-device-row-1">
                        <select name="req_jenis[]" class="w-2/3 h-11 border border-slate-200 rounded-xl px-4 text-sm focus:border-primary focus:ring-4 focus:ring-primary/15 transition-all outline-none" required>
                            <option value="" disabled selected>Pilih Jenis Perangkat</option>
                            <option value="Modem">Modem</option>
                            <option value="STB">STB</option>
                            <option value="Router">Router</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                        <input type="number" name="req_qty[]" min="1" max="10" value="1" class="w-1/3 h-11 border border-slate-200 rounded-xl px-4 text-sm focus:border-primary focus:ring-4 focus:ring-primary/15 transition-all outline-none" placeholder="Qty" required>
                        <button type="button" class="w-11 h-11 rounded-xl bg-slate-100 text-slate-400 flex shrink-0 items-center justify-center opacity-50 cursor-not-allowed" disabled>
                            <span class="material-symbols-outlined text-sm">close</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- BYPASS POPUP FORM FOR REQUEST (Triggered if Customer not found) -->
            <div id="req-bypass-section" class="p-4 border border-dashed border-red-200 bg-red-50/30 rounded-xl space-y-4" style="display: none;">
                <p class="text-xs font-bold text-red-700">Data Pelanggan Tidak Ditemukan! Silakan Bypass Input:</p>
                
                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Nama Pelanggan</label>
                    <input type="text" id="req_bypass_nama" class="w-full h-10 border border-slate-200 bg-white rounded-lg px-3 text-xs outline-none">
                </div>
                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">No Telepon</label>
                    <input type="text" id="req_bypass_telp" class="w-full h-10 border border-slate-200 bg-white rounded-lg px-3 text-xs outline-none">
                </div>
                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Alamat Pemasangan</label>
                    <textarea id="req_bypass_alamat" rows="2" class="w-full border border-slate-200 bg-white rounded-lg p-3 text-xs outline-none resize-none"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <input type="text" id="req_bypass_lat" placeholder="Lat" class="h-10 border border-slate-200 bg-white rounded-lg px-3 text-xs outline-none">
                    <input type="text" id="req_bypass_lng" placeholder="Lng" class="h-10 border border-slate-200 bg-white rounded-lg px-3 text-xs outline-none">
                </div>
                <button type="button" 
                        class="w-full h-10 border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 rounded-lg text-xs font-bold transition-all flex items-center justify-center gap-1.5" 
                        onclick="detectBypassGPS('req')">
                    <span class="material-symbols-outlined text-base">my_location</span> Deteksi GPS Otomatis
                </button>
                <button type="button" 
                        class="w-full h-11 bg-red-600 text-white rounded-lg text-xs font-bold hover:bg-red-700 transition-all" 
                        onclick="submitBypassForRequest()">
                    Simpan & Bypass Pelanggan
                </button>
            </div>

            <div class="pt-4 flex justify-end gap-2 border-t border-slate-100">
                <button type="button" class="h-11 px-6 border border-slate-200 rounded-xl text-sm font-semibold hover:bg-slate-50 transition-all text-slate-600" 
                        onclick="closeTechModal('request-modal')">Batal</button>
                <button type="submit" id="req-submit-btn" disabled 
                        class="h-11 px-6 bg-primary text-white rounded-xl text-sm font-bold opacity-50 cursor-not-allowed transition-all shadow-lg shadow-primary/10">
                    Kirim Request
                </button>
            </div>
        </form>
    </div>
</div>

<!-- 2. RETURN DEVICE MODAL -->
<div id="return-modal" class="fixed inset-0 z-50 glass-overlay flex items-end justify-center" style="display: none;">
    <div class="bottom-sheet w-full max-w-2xl bg-white rounded-t-[32px] shadow-2xl p-6 md:p-8 max-h-[90vh] overflow-y-auto">
        <!-- Drag Handle -->
        <div class="w-12 h-1.5 bg-slate-200 rounded-full mx-auto mb-6"></div>
        
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg font-bold text-primary flex items-center gap-1.5">
                <span class="material-symbols-outlined text-secondary">assignment_return</span>
                Lapor Pengembalian (Return)
            </h2>
            <button class="w-10 h-10 rounded-full flex items-center justify-center hover:bg-slate-100 transition-colors text-slate-400" 
                    onclick="closeTechModal('return-modal')">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <form action="{{ route('teknisi.return') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <input type="hidden" name="bypass_device" id="ret_bypass_device" value="0">
            
            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block">ID Pelanggan</label>
                <div class="flex gap-2">
                    <input type="text" id="ret_id_pelanggan" name="id_pelanggan" required 
                           class="w-full h-11 border border-slate-200 rounded-xl px-4 text-sm focus:border-primary focus:ring-4 focus:ring-primary/15 transition-all outline-none font-mono" 
                           placeholder="Masukkan ID Pelanggan">
                    <button type="button" class="h-11 px-6 bg-slate-100 border border-slate-200 hover:bg-slate-200 rounded-xl text-sm font-bold text-slate-700 transition-all" 
                            onclick="checkCustomerForReturn()">Cek</button>
                </div>
            </div>

            <!-- Bypass Section for Return -->
            <div id="ret-bypass-section" class="p-4 border border-dashed border-red-200 bg-red-50/30 rounded-xl space-y-4" style="display: none;">
                <p class="text-xs font-bold text-red-700">Data Pelanggan Tidak Ditemukan! Silakan Bypass Input:</p>
                
                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Nama Pelanggan</label>
                    <input type="text" id="ret_bypass_nama" class="w-full h-10 border border-slate-200 bg-white rounded-lg px-3 text-xs outline-none">
                </div>
                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">No Telepon</label>
                    <input type="text" id="ret_bypass_telp" class="w-full h-10 border border-slate-200 bg-white rounded-lg px-3 text-xs outline-none">
                </div>
                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Alamat Pemasangan</label>
                    <textarea id="ret_bypass_alamat" rows="2" class="w-full border border-slate-200 bg-white rounded-lg p-3 text-xs outline-none resize-none"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <input type="text" id="ret_bypass_lat" placeholder="Lat" class="h-10 border border-slate-200 bg-white rounded-lg px-3 text-xs outline-none">
                    <input type="text" id="ret_bypass_lng" placeholder="Lng" class="h-10 border border-slate-200 bg-white rounded-lg px-3 text-xs outline-none">
                </div>
                <button type="button" 
                        class="w-full h-10 border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 rounded-lg text-xs font-bold transition-all flex items-center justify-center gap-1.5" 
                        onclick="detectBypassGPS('ret')">
                    <span class="material-symbols-outlined text-base">my_location</span> Deteksi GPS Otomatis
                </button>
                <button type="button" 
                        class="w-full h-11 bg-red-600 text-white rounded-lg text-xs font-bold hover:bg-red-700 transition-all" 
                        onclick="submitBypassForReturn()">
                    Simpan & Bypass Pelanggan
                </button>
            </div>

            <!-- Checkboxes of Active Devices -->
            <div id="ret-device-container" class="space-y-4" style="display: none;">
                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Pilih Perangkat Aktif Pelanggan</label>
                <div id="ret-checkbox-list" class="space-y-2">
                    <!-- Populated dynamically via AJAX -->
                </div>
            </div>

            <!-- Bypass Device Section (when device not in DB) -->
            <div id="ret-device-bypass" class="space-y-3 pt-2" style="display: none;">
                <div class="flex justify-between items-center">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Perangkat Manual (Bypass)</label>
                    <button type="button" onclick="addReturnBypassRow()" class="text-[11px] font-bold text-primary hover:underline flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">add_circle</span> Tambah Perangkat
                    </button>
                </div>
                <div id="ret-bypass-rows" class="space-y-3">
                    <!-- Dynamic fields go here -->
                </div>
            </div>

            <div class="space-y-1">
                <label for="ret_alasan_rusak" class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Alasan Pengembalian (Kerusakan)</label>
                <textarea id="ret_alasan_rusak" name="alasan_rusak" required rows="3"
                          class="w-full border border-slate-200 rounded-xl p-4 text-sm focus:border-primary focus:ring-4 focus:ring-primary/15 outline-none transition-all resize-none" 
                          placeholder="Deskripsikan alasan/kerusakan alat..."></textarea>
            </div>

            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Foto Fisik Perangkat Rusak</label>
                <div class="group relative cursor-pointer border border-slate-200 hover:border-primary bg-white hover:bg-primary/5 rounded-xl p-4 transition-all duration-300 flex flex-col items-center justify-center">
                    <input type="file" name="foto_bukti" accept="image/*" required 
                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                           onchange="previewImage(this, 'preview-img-ret', 'preview-container-ret', 'icon-container-ret')">
                    
                    <!-- Default icon container -->
                    <div id="icon-container-ret" class="flex flex-col items-center justify-center py-2">
                        <span class="material-symbols-outlined text-2xl text-slate-400 group-hover:text-primary mb-1">photo_camera</span>
                        <span class="text-[11px] font-bold text-slate-500 group-hover:text-primary">Ambil Foto Kerusakan</span>
                    </div>

                    <!-- Hidden image preview container -->
                    <div id="preview-container-ret" class="hidden w-full h-32 relative">
                        <img id="preview-img-ret" class="w-full h-full object-cover rounded-lg shadow-sm border border-slate-200">
                        <div class="absolute inset-0 bg-black/40 hidden items-center justify-center rounded-lg group-hover:flex">
                            <span class="text-white font-bold text-xs flex items-center"><span class="material-symbols-outlined text-sm mr-1">sync</span> Ganti Foto</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-4 flex justify-end gap-2 border-t border-slate-100">
                <button type="button" class="h-11 px-6 border border-slate-200 rounded-xl text-sm font-semibold hover:bg-slate-50 transition-all text-slate-600" 
                        onclick="closeTechModal('return-modal')">Batal</button>
                <button type="submit" id="ret-submit-btn" disabled 
                        class="h-11 px-6 bg-primary text-white rounded-xl text-sm font-bold opacity-50 cursor-not-allowed transition-all shadow-lg shadow-primary/10">
                    Kirim Laporan Return
                </button>
            </div>
        </form>
    </div>
</div>

<!-- 3. DISMANTLE DEVICE MODAL -->
<div id="dismantle-modal" class="fixed inset-0 z-50 glass-overlay flex items-end justify-center" style="display: none;">
    <div class="bottom-sheet w-full max-w-2xl bg-white rounded-t-[32px] shadow-2xl p-6 md:p-8 max-h-[90vh] overflow-y-auto">
        <!-- Drag Handle -->
        <div class="w-12 h-1.5 bg-slate-200 rounded-full mx-auto mb-6"></div>
        
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg font-bold text-primary flex items-center gap-1.5">
                <span class="material-symbols-outlined text-secondary">build</span>
                Lapor Pembongkaran (Dismantle)
            </h2>
            <button class="w-10 h-10 rounded-full flex items-center justify-center hover:bg-slate-100 transition-colors text-slate-400" 
                    onclick="closeTechModal('dismantle-modal')">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <form action="{{ route('teknisi.dismantle') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            
            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block">ID Pelanggan</label>
                <div class="flex gap-2">
                    <input type="text" id="dis_id_pelanggan" name="id_pelanggan" required 
                           class="w-full h-11 border border-slate-200 rounded-xl px-4 text-sm focus:border-primary focus:ring-4 focus:ring-primary/15 transition-all outline-none font-mono" 
                           placeholder="Masukkan ID Pelanggan">
                    <button type="button" class="h-11 px-6 bg-slate-100 border border-slate-200 hover:bg-slate-200 rounded-xl text-sm font-bold text-slate-700 transition-all" 
                            onclick="checkCustomerForDismantle()">Cek</button>
                </div>
            </div>

            <!-- Bypass Section for Dismantle -->
            <div id="dis-bypass-section" class="p-4 border border-dashed border-red-200 bg-red-50/30 rounded-xl space-y-4" style="display: none;">
                <p class="text-xs font-bold text-red-700">Data Pelanggan Tidak Ditemukan! Silakan Bypass Input:</p>
                
                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Nama Pelanggan</label>
                    <input type="text" id="dis_bypass_nama" class="w-full h-10 border border-slate-200 bg-white rounded-lg px-3 text-xs outline-none">
                </div>
                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">No Telepon</label>
                    <input type="text" id="dis_bypass_telp" class="w-full h-10 border border-slate-200 bg-white rounded-lg px-3 text-xs outline-none">
                </div>
                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Alamat Pemasangan</label>
                    <textarea id="dis_bypass_alamat" rows="2" class="w-full border border-slate-200 bg-white rounded-lg p-3 text-xs outline-none resize-none"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <input type="text" id="dis_bypass_lat" placeholder="Lat" class="h-10 border border-slate-200 bg-white rounded-lg px-3 text-xs outline-none">
                    <input type="text" id="dis_bypass_lng" placeholder="Lng" class="h-10 border border-slate-200 bg-white rounded-lg px-3 text-xs outline-none">
                </div>
                <button type="button" 
                        class="w-full h-10 border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 rounded-lg text-xs font-bold transition-all flex items-center justify-center gap-1.5" 
                        onclick="detectBypassGPS('dis')">
                    <span class="material-symbols-outlined text-base">my_location</span> Deteksi GPS Otomatis
                </button>
                <button type="button" 
                        class="w-full h-11 bg-red-600 text-white rounded-lg text-xs font-bold hover:bg-red-700 transition-all" 
                        onclick="submitBypassForDismantle()">
                    Simpan & Bypass Pelanggan
                </button>
            </div>

            <!-- Checkboxes of Active Devices -->
            <div id="dis-devices-container" class="space-y-4" style="display: none;">
                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Pilih Perangkat Terpasang:</label>
                <div id="dis-checkbox-list" class="space-y-2">
                    <!-- Populated dynamically via AJAX -->
                </div>
            </div>

            <!-- Bypass Device Section (when device not in DB) -->
            <div id="dis-bypass-section-rows" class="space-y-3 pt-2" style="display: none;">
                <div class="flex justify-between items-center">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Perangkat Manual (Bypass)</label>
                    <button type="button" onclick="addDismantleOtherRow()" class="text-[11px] font-bold text-primary hover:underline flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">add_circle</span> Tambah Perangkat
                    </button>
                </div>
                <div id="dis-other-rows" class="space-y-3">
                    <!-- Dynamic fields go here -->
                </div>
            </div>

            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Foto Bukti Pembongkaran</label>
                <div class="group relative cursor-pointer border border-slate-200 hover:border-primary bg-white hover:bg-primary/5 rounded-xl p-4 transition-all duration-300 flex flex-col items-center justify-center">
                    <input type="file" name="foto_bukti" accept="image/*" required 
                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                           onchange="previewImage(this, 'preview-img-dis', 'preview-container-dis', 'icon-container-dis')">
                    
                    <!-- Default icon container -->
                    <div id="icon-container-dis" class="flex flex-col items-center justify-center py-2">
                        <span class="material-symbols-outlined text-2xl text-slate-400 group-hover:text-primary mb-1">photo_camera</span>
                        <span class="text-[11px] font-bold text-slate-500 group-hover:text-primary">Ambil Foto Pembongkaran</span>
                    </div>

                    <!-- Hidden image preview container -->
                    <div id="preview-container-dis" class="hidden w-full h-32 relative">
                        <img id="preview-img-dis" class="w-full h-full object-cover rounded-lg shadow-sm border border-slate-200">
                        <div class="absolute inset-0 bg-black/40 hidden items-center justify-center rounded-lg group-hover:flex">
                            <span class="text-white font-bold text-xs flex items-center"><span class="material-symbols-outlined text-sm mr-1">sync</span> Ganti Foto</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-4 flex justify-end gap-2 border-t border-slate-100">
                <button type="button" class="h-11 px-6 border border-slate-200 rounded-xl text-sm font-semibold hover:bg-slate-50 transition-all text-slate-600" 
                        onclick="closeTechModal('dismantle-modal')">Batal</button>
                <button type="submit" id="dis-submit-btn" disabled 
                        class="h-11 px-6 bg-primary text-white rounded-xl text-sm font-bold opacity-50 cursor-not-allowed transition-all shadow-lg shadow-primary/10">
                    Kirim Laporan Dismantle
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

<script>
    // --- ACCORDION SCRIPT (JQUERY) ---
    function toggleAccordion(id) {
        const body = $('#assign-body-' + id);
        const card = $('#assign-card-' + id);
        const icon = card.find('.accordion-icon');

        if (body.hasClass('active')) {
            body.removeClass('active');
            card.removeClass('ring-2 ring-primary/20');
            icon.css('transform', 'rotate(0deg)');
        } else {
            // Close other accordions
            $('.accordion-content').removeClass('active');
            $('.accordion-icon').css('transform', 'rotate(0deg)');
            $('.bg-white.border').removeClass('ring-2 ring-primary/20');

            body.addClass('active');
            card.addClass('ring-2 ring-primary/20');
            icon.css('transform', 'rotate(180deg)');
        }
    }

    // --- MODAL UTILITIES ---
    function openTechModal(modalId) {
        const modal = $('#' + modalId);
        modal.css('display', 'flex');
        setTimeout(() => {
            modal.find('.bottom-sheet').addClass('active');
        }, 50);
    }

    function closeTechModal(modalId) {
        const modal = $('#' + modalId);
        modal.find('.bottom-sheet').removeClass('active');
        setTimeout(() => {
            modal.hide();
            modal.find('form')[0].reset();
            
            // Hide dynamic blocks
            if (modalId === 'request-modal') {
                $('#req-cust-details').hide();
                $('#req-bypass-section').hide();
                $('#req-submit-btn').addClass('opacity-50 cursor-not-allowed').prop('disabled', true);
                // Reset request device rows to just 1
                $('#req-device-rows').html(`
                    <div class="req-device-row flex gap-2 items-center" id="req-device-row-1">
                        <select name="req_jenis[]" class="w-2/3 h-11 border border-slate-200 rounded-xl px-4 text-sm focus:border-primary focus:ring-4 focus:ring-primary/15 transition-all outline-none" required>
                            <option value="" disabled selected>Pilih Jenis Perangkat</option>
                            <option value="Modem">Modem</option>
                            <option value="STB">STB</option>
                            <option value="Router">Router</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                        <input type="number" name="req_qty[]" min="1" max="10" value="1" class="w-1/3 h-11 border border-slate-200 rounded-xl px-4 text-sm focus:border-primary focus:ring-4 focus:ring-primary/15 transition-all outline-none" placeholder="Qty" required>
                        <button type="button" class="w-11 h-11 rounded-xl bg-slate-100 text-slate-400 flex shrink-0 items-center justify-center opacity-50 cursor-not-allowed" disabled>
                            <span class="material-symbols-outlined text-sm">close</span>
                        </button>
                    </div>
                `);
                reqDeviceRowCount = 1;
            } else if (modalId === 'return-modal') {
                $('#ret-device-container').hide();
                $('#ret-device-bypass').hide();
                $('#ret-bypass-section').hide();
                $('#ret-bypass-rows').empty();
                $('#ret-submit-btn').addClass('opacity-50 cursor-not-allowed').prop('disabled', true);
            } else if (modalId === 'dismantle-modal') {
                $('#dis-devices-container').hide();
                $('#dis-bypass-section').hide();
                $('#dis-bypass-section-rows').hide();
                $('#dis-other-rows').empty();
                $('#dis-submit-btn').addClass('opacity-50 cursor-not-allowed').prop('disabled', true);
            }
        }, 300);
    }

    // --- GEOLOCATION API AUTODETECT ---
    function detectGPS(id) {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                $('#lat-' + id).val(position.coords.latitude.toFixed(8));
                $('#lng-' + id).val(position.coords.longitude.toFixed(8));
                alert('GPS Berhasil dideteksi!');
            }, function(error) {
                alert('Gagal mendeteksi GPS: Harap periksa izin lokasi browser Anda.');
            });
        } else {
            alert('Browser Anda tidak mendukung Geolocation.');
        }
    }

    function detectBypassGPS(prefix) {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                $('#' + prefix + '_bypass_lat').val(position.coords.latitude.toFixed(8));
                $('#' + prefix + '_bypass_lng').val(position.coords.longitude.toFixed(8));
                alert('GPS Berhasil dideteksi!');
            }, function(error) {
                alert('Gagal mendeteksi GPS: Harap periksa izin lokasi browser Anda.');
            });
        } else {
            alert('Browser Anda tidak mendukung Geolocation.');
        }
    }

    // --- REQUEST WORKFLOW & AJAX BYPASS ---
    function checkCustomerForRequest() {
        const id = $('#req_id_pelanggan').val();
        if (!id) return alert('Masukkan ID Pelanggan.');

        $.ajax({
            url: "{{ route('admin.customers.search') }}",
            type: "GET",
            data: { id_pelanggan: id },
            success: function(response) {
                if (response.success) {
                    $('#req-cust-name').text(response.customer.nama_pelanggan);
                    $('#req-cust-address').text(response.customer.alamat_pemasangan);
                    $('#req-cust-phone').text(response.customer.no_telepon);
                    $('#req-cust-details').slideDown();
                    $('#req-bypass-section').slideUp();
                    // Enable submit
                    $('#req-submit-btn').removeClass('opacity-50 cursor-not-allowed').prop('disabled', false);
                } else {
                    // Show bypass form
                    $('#req-cust-details').slideUp();
                    $('#req-bypass-section').slideDown();
                    $('#req-submit-btn').addClass('opacity-50 cursor-not-allowed').prop('disabled', true);
                }
            }
        });
    }

    function submitBypassForRequest() {
        const id = $('#req_bypass_nama').closest('form').find('input[name="id_pelanggan"]').val(); // From main form
        const nama = $('#req_bypass_nama').val();
        const telp = $('#req_bypass_telp').val();
        const alamat = $('#req_bypass_alamat').val();
        const lat = $('#req_bypass_lat').val();
        const lng = $('#req_bypass_lng').val();

        if (!nama || !telp || !alamat) return alert('Harap isi Nama, Telp, dan Alamat.');

        $.ajax({
            url: "{{ route('admin.customers.bypass') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                id_pelanggan: $('#req_id_pelanggan').val(),
                nama_pelanggan: nama,
                no_telepon: telp,
                alamat_pemasangan: alamat,
                latitude: lat,
                longitude: lng
            },
            success: function(response) {
                if (response.success) {
                    alert('Pelanggan berhasil dibuat via bypass!');
                    // Hide bypass form, show details, enable submit
                    $('#req-cust-name').text(response.customer.nama_pelanggan);
                    $('#req-cust-address').text(response.customer.alamat_pemasangan);
                    $('#req-cust-phone').text(response.customer.no_telepon);
                    
                    $('#req-bypass-section').slideUp();
                    $('#req-cust-details').slideDown();
                    $('#req-submit-btn').removeClass('opacity-50 cursor-not-allowed').prop('disabled', false);
                }
            },
            error: function(xhr) {
                alert('Bypass gagal: ' + (xhr.responseJSON.message || 'ID Pelanggan sudah ada atau format koordinat salah.'));
            }
        });
    }

    // --- REQUEST DEVICE DYNAMIC ROWS ---
    let reqDeviceRowCount = 1;
    function addRequestDeviceRow() {
        reqDeviceRowCount++;
        const idx = reqDeviceRowCount;
        const html = `
            <div class="req-device-row flex gap-2 items-center" id="req-device-row-${idx}">
                <select name="req_jenis[]" class="w-2/3 h-11 border border-slate-200 rounded-xl px-4 text-sm focus:border-primary focus:ring-4 focus:ring-primary/15 transition-all outline-none" required>
                    <option value="" disabled selected>Pilih Jenis Perangkat</option>
                    <option value="Modem">Modem</option>
                    <option value="STB">STB</option>
                    <option value="Router">Router</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
                <input type="number" name="req_qty[]" min="1" max="10" value="1" class="w-1/3 h-11 border border-slate-200 rounded-xl px-4 text-sm focus:border-primary focus:ring-4 focus:ring-primary/15 transition-all outline-none" placeholder="Qty" required>
                <button type="button" onclick="removeRequestDeviceRow('req-device-row-${idx}')" class="w-11 h-11 rounded-xl bg-red-50 hover:bg-red-100 text-red-500 flex shrink-0 items-center justify-center transition-all">
                    <span class="material-symbols-outlined text-sm">close</span>
                </button>
            </div>
        `;
        $('#req-device-rows').append(html);
    }

    function removeRequestDeviceRow(rowId) {
        $('#' + rowId).fadeOut(200, function() { $(this).remove(); });
    }

    // --- RETURN WORKFLOW & AJAX BYPASS ---
    function checkCustomerForReturn() {
        const id = $('#ret_id_pelanggan').val();
        if (!id) return alert('Masukkan ID Pelanggan.');

        $.ajax({
            url: "{{ route('admin.customers.search') }}",
            type: "GET",
            data: { id_pelanggan: id },
            success: function(response) {
                if (response.success) {
                    $('#ret-bypass-section').slideUp();
                    fetchActiveDevicesForReturn(id);
                } else {
                    $('#ret-device-container').slideUp();
                    $('#ret-device-bypass').slideDown();
                    $('#ret-bypass-section').slideDown();
                    // Auto-add first bypass row when no customer found
                    if ($('#ret-bypass-rows .bypass-row').length === 0) addReturnBypassRow();
                    $('#ret-submit-btn').removeClass('opacity-50 cursor-not-allowed').prop('disabled', false);
                }
            }
        });
    }

    function fetchActiveDevicesForReturn(customerId) {
        $.ajax({
            url: "{{ route('teknisi.active-devices') }}",
            type: "GET",
            data: { id_pelanggan: customerId },
            success: function(res) {
                if (res.success) {
                    const list = $('#ret-checkbox-list');
                    list.empty();

                    if (res.devices.length === 0) {
                        // No registered devices — show full bypass container
                        $('#ret-device-container').slideUp();
                        $('#ret-device-bypass').slideDown();
                        if ($('#ret-bypass-rows .bypass-row').length === 0) addReturnBypassRow();
                        $('#ret-submit-btn').removeClass('opacity-50 cursor-not-allowed').prop('disabled', false);
                    } else {
                        // Show checkboxes for each active device
                        res.devices.forEach(dev => {
                            list.append(`
                                <label class="flex items-center gap-2 p-3 border border-slate-100 rounded-xl text-xs font-bold text-slate-700 cursor-pointer select-none hover:bg-slate-50 transition-all">
                                    <input type="checkbox" name="serial_numbers[]" value="${dev.serial_number}" class="w-4 h-4 rounded text-primary focus:ring-primary border-slate-200" onchange="checkReturnSelection()">
                                    <span class="material-symbols-outlined text-primary text-sm">router</span>
                                    <span class="font-mono">${dev.serial_number}</span>
                                    <span class="text-slate-400">(${dev.jenis_merek} - ${dev.tipe_perangkat})</span>
                                </label>
                            `);
                        });
                        $('#ret-device-bypass').slideUp();
                        $('#ret-device-container').slideDown();
                        // submit enabled only when at least one checked
                        $('#ret-submit-btn').addClass('opacity-50 cursor-not-allowed').prop('disabled', true);
                    }
                }
            }
        });
    }

    function checkReturnSelection() {
        // Enable submit if at least one checkbox checked or at least one bypass row has SN
        const anyChecked = $('#ret-checkbox-list input[type=checkbox]:checked').length > 0;
        const anyBypass = $('#ret-bypass-rows input[name="bypass_sn[]"]').filter(function() { return $(this).val().trim() !== ''; }).length > 0;
        if (anyChecked || anyBypass) {
            $('#ret-submit-btn').removeClass('opacity-50 cursor-not-allowed').prop('disabled', false);
        } else {
            $('#ret-submit-btn').addClass('opacity-50 cursor-not-allowed').prop('disabled', true);
        }
    }

    // Add a bypass device row to Return modal
    let retBypassRowCount = 0;
    function addReturnBypassRow() {
        retBypassRowCount++;
        const idx = retBypassRowCount;
        const html = `
            <div class="bypass-row p-3 bg-amber-50/60 border border-dashed border-amber-200 rounded-xl space-y-2" id="ret-bypass-row-${idx}">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-[10px] font-bold text-amber-700 uppercase tracking-wider">Perangkat Manual #${idx}</span>
                    <button type="button" onclick="removeBypassRow('ret-bypass-row-${idx}', 'ret')" class="w-6 h-6 rounded-full bg-red-100 text-red-500 hover:bg-red-200 flex items-center justify-center transition-all">
                        <span class="material-symbols-outlined text-sm">close</span>
                    </button>
                </div>
                <input type="text" name="bypass_sn[]" 
                       class="w-full h-9 border border-slate-200 bg-white rounded-lg px-3 text-xs outline-none font-mono font-bold"
                       placeholder="Serial Number (contoh: SN-MDM-001)" oninput="checkReturnSelection()">
                <div class="grid grid-cols-2 gap-2">
                    <select name="bypass_jenis[]" class="h-9 border border-slate-200 bg-white rounded-lg px-2 text-xs outline-none">
                        <option value="STB Huawei">STB Huawei</option>
                        <option value="STB ZTE">STB ZTE</option>
                        <option value="Modem ZTE">Modem ZTE</option>
                        <option value="Modem Huawei">Modem Huawei</option>
                    </select>
                    <input type="text" name="bypass_tipe[]" 
                           class="h-9 border border-slate-200 bg-white rounded-lg px-2 text-xs outline-none"
                           placeholder="Tipe (F609, B860H...)">
                </div>
            </div>
        `;
        $('#ret-bypass-rows').append(html);
        $('#ret-device-bypass').slideDown();
        checkReturnSelection();
    }


    function submitBypassForReturn() {
        const id = $('#ret_id_pelanggan').val();
        const nama = $('#ret_bypass_nama').val();
        const telp = $('#ret_bypass_telp').val();
        const alamat = $('#ret_bypass_alamat').val();
        const lat = $('#ret_bypass_lat').val();
        const lng = $('#ret_bypass_lng').val();

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
                    $('#ret-bypass-section').slideUp();
                    fetchActiveDevicesForReturn(id);
                }
            },
            error: function(xhr) {
                alert('Bypass gagal: ' + (xhr.responseJSON.message || 'ID Pelanggan sudah ada atau format koordinat salah.'));
            }
        });
    }

    // --- DISMANTLE WORKFLOW & AJAX BYPASS ---
    function checkCustomerForDismantle() {
        const id = $('#dis_id_pelanggan').val();
        if (!id) return alert('Masukkan ID Pelanggan.');

        $.ajax({
            url: "{{ route('admin.customers.search') }}",
            type: "GET",
            data: { id_pelanggan: id },
            success: function(response) {
                if (response.success) {
                    // Customer found. Fetch installed devices.
                    $('#dis-bypass-section').slideUp();
                    fetchActiveDevicesForCheckboxes(id);
                } else {
                    // Show bypass form
                    $('#dis-devices-container').slideUp();
                    $('#dis-bypass-section').slideDown();
                    $('#dis-submit-btn').addClass('opacity-50 cursor-not-allowed').prop('disabled', true);
                }
            }
        });
    }

    function fetchActiveDevicesForCheckboxes(customerId) {
        $.ajax({
            url: "{{ route('teknisi.active-devices') }}",
            type: "GET",
            data: { id_pelanggan: customerId },
            success: function(res) {
                if (res.success) {
                    const list = $('#dis-checkbox-list');
                    list.empty();

                    if (res.devices.length === 0) {
                        list.append('<p class="italic text-xs text-slate-400">Tidak ada perangkat terdaftar aktif. Gunakan tombol Tambah di bawah.</p>');
                    } else {
                        res.devices.forEach(dev => {
                            list.append(`
                                <label class="flex items-center gap-2 p-3 border border-slate-100 rounded-xl text-xs font-bold text-slate-700 cursor-pointer select-none hover:bg-slate-50 transition-all">
                                    <input type="checkbox" name="serial_numbers[]" value="${dev.serial_number}" class="w-4 h-4 rounded text-primary focus:ring-primary border-slate-200" onchange="checkDismantleSelection()">
                                    <span class="material-symbols-outlined text-primary text-sm">router</span>
                                    <span class="font-mono">${dev.serial_number}</span>
                                    <span class="text-slate-400">(${dev.jenis_merek} - ${dev.tipe_perangkat})</span>
                                </label>
                            `);
                        });
                    }

                    $('#dis-devices-container').slideDown();
                    $('#dis-bypass-section-rows').slideDown();
                    if ($('#dis-other-rows .dis-other-row').length === 0 && res.devices.length === 0) {
                        addDismantleOtherRow();
                    }
                    checkDismantleSelection();
                }
            }
        });
    }

    function checkDismantleSelection() {
        const anyChecked = $('#dis-checkbox-list input[type=checkbox]:checked').length > 0;
        const anyBypass = $('#dis-other-rows input[name="other_serial_numbers[]"]').filter(function() { return $(this).val().trim() !== ''; }).length > 0;
        if (anyChecked || anyBypass) {
            $('#dis-submit-btn').removeClass('opacity-50 cursor-not-allowed').prop('disabled', false);
        } else {
            $('#dis-submit-btn').addClass('opacity-50 cursor-not-allowed').prop('disabled', true);
        }
    }

    function submitBypassForDismantle() {
        const id = $('#dis_id_pelanggan').val();
        const nama = $('#dis_bypass_nama').val();
        const telp = $('#dis_bypass_telp').val();
        const alamat = $('#dis_bypass_alamat').val();
        const lat = $('#dis_bypass_lat').val();
        const lng = $('#dis_bypass_lng').val();

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
                    $('#dis-bypass-section').slideUp();
                    fetchActiveDevicesForCheckboxes(id);
                }
            },
            error: function(xhr) {
                alert('Bypass gagal: ' + (xhr.responseJSON.message || 'ID Pelanggan sudah ada atau format koordinat salah.'));
            }
        });
    }

    // Add a "Lainnya" row to Dismantle modal
    let disOtherRowCount = 0;
    function addDismantleOtherRow() {
        disOtherRowCount++;
        const idx = disOtherRowCount;
        const html = `
            <div class="dis-other-row p-3 bg-amber-50/60 border border-dashed border-amber-200 rounded-xl space-y-2" id="dis-other-row-${idx}">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-[10px] font-bold text-amber-700 uppercase tracking-wider">Perangkat Manual #${idx}</span>
                    <button type="button" onclick="removeBypassRow('dis-other-row-${idx}', 'dis')" class="w-6 h-6 rounded-full bg-red-100 text-red-500 hover:bg-red-200 flex items-center justify-center transition-all">
                        <span class="material-symbols-outlined text-sm">close</span>
                    </button>
                </div>
                <input type="text" name="other_serial_numbers[]" 
                       class="w-full h-9 border border-slate-200 bg-white rounded-lg px-3 text-xs outline-none font-mono font-bold"
                       placeholder="Serial Number (contoh: SN-MDM-001)" oninput="checkDismantleSelection()">
                <div class="grid grid-cols-2 gap-2">
                    <select name="other_jenis_merek[]" class="h-9 border border-slate-200 bg-white rounded-lg px-2 text-xs outline-none">
                        <option value="STB Huawei">STB Huawei</option>
                        <option value="STB ZTE">STB ZTE</option>
                        <option value="Modem ZTE">Modem ZTE</option>
                        <option value="Modem Huawei">Modem Huawei</option>
                    </select>
                    <input type="text" name="other_tipe_perangkat[]" 
                           class="h-9 border border-slate-200 bg-white rounded-lg px-2 text-xs outline-none"
                           placeholder="Tipe (F609, B860H...)">
                </div>
            </div>
        `;
        $('#dis-other-rows').append(html);
        checkDismantleSelection();
    }

    function removeBypassRow(rowId, formType) {
        $('#' + rowId).fadeOut(200, function() { 
            $(this).remove(); 
            if (formType === 'ret') checkReturnSelection();
            if (formType === 'dis') checkDismantleSelection();
        });
    }

    // --- IMAGE PREVIEW FUNCTION ---
    function previewImage(input, previewImgId, containerId, iconContainerId) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#' + previewImgId).attr('src', e.target.result);
                $('#' + containerId).removeClass('hidden').addClass('flex');
                $('#' + iconContainerId).addClass('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            $('#' + previewImgId).attr('src', '');
            $('#' + containerId).addClass('hidden').removeClass('flex');
            $('#' + iconContainerId).removeClass('hidden');
        }
    }

    // --- HTML5 CANVAS IMAGE COMPRESSOR ---
    function compressImage(file, maxWidth, maxHeight, quality, callback) {
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = function (event) {
            const img = new Image();
            img.src = event.target.result;
            img.onload = function () {
                const canvas = document.createElement('canvas');
                let width = img.width;
                let height = img.height;

                if (width > height) {
                    if (width > maxWidth) {
                        height = Math.round((height * maxWidth) / width);
                        width = maxWidth;
                    }
                } else {
                    if (height > maxHeight) {
                        width = Math.round((width * maxHeight) / height);
                        height = maxHeight;
                    }
                }

                canvas.width = width;
                canvas.height = height;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, width, height);

                canvas.toBlob(function (blob) {
                    callback(blob);
                }, 'image/jpeg', quality);
            };
        };
    }

    // Intercept form submissions containing file uploads to compress them
    $(document).on('submit', 'form', function (e) {
        const form = this;
        const fileInput = $(form).find('input[type="file"][name="foto_bukti"]');

        if (fileInput.length > 0 && fileInput[0].files && fileInput[0].files.length > 0 && !form.dataset.compressed) {
            e.preventDefault(); // Stop native submission
            
            const file = fileInput[0].files[0];
            const submitBtn = $(form).find('button[type="submit"]');
            const originalText = submitBtn.html();
            
            // Show compression state on button
            submitBtn.prop('disabled', true).addClass('opacity-50')
                     .html('<span class="material-symbols-outlined animate-spin mr-1 text-sm">progress_activity</span> Mengompresi Foto...');

            compressImage(file, 1200, 1200, 0.8, function (blob) {
                // Reconstruct File object
                const compressedFile = new File([blob], file.name.replace(/\.[^/.]+$/, "") + ".jpg", {
                    type: 'image/jpeg',
                    lastModified: Date.now()
                });

                // Update input.files with compressed file
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(compressedFile);
                fileInput[0].files = dataTransfer.files;

                // Submit form
                form.dataset.compressed = "true";
                form.submit();
            });
        }
    });
</script>
@endsection
