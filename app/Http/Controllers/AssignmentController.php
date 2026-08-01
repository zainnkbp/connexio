<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Assignment;
use App\Models\Customer;
use App\Models\Device;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AssignmentController extends Controller
{
    // Admin list of pending assignments for approvals
    public function pendingApprovals(Request $request)
    {
        // 1. Pending deployment requests (status_approval = Pending, tipe_alur = Pengambilan, serial_number = null)
        $deployments = Assignment::where('tipe_alur', 'Pengambilan')
            ->where('status_approval', 'Pending')
            ->whereNull('serial_number')
            ->orderBy('created_at', 'desc')
            ->get();

        // 2. Pending return requests (status_approval = Pending, tipe_alur = Pengembalian)
        $returns = Assignment::where('tipe_alur', 'Pengembalian')
            ->where('status_approval', 'Pending')
            ->orderBy('created_at', 'desc')
            ->get();

        // 3. Pending dismantling requests (status_approval = Pending, tipe_alur = Dismantling)
        $dismantles = Assignment::where('tipe_alur', 'Dismantling')
            ->where('status_approval', 'Pending')
            ->orderBy('created_at', 'desc')
            ->get();

        // 4. History log (status != Pending) with filter
        $historyQuery = Assignment::where('status_approval', '!=', 'Pending')->with(['customer', 'teknisi']);
        
        if ($request->filled('search')) {
            $search = $request->search;
            $historyQuery->where(function($q) use ($search) {
                $q->whereHas('customer', function($q2) use ($search) {
                    $q2->where('nama_pelanggan', 'like', "%{$search}%")
                       ->orWhere('id_pelanggan', 'like', "%{$search}%");
                })->orWhereHas('teknisi', function($q3) use ($search) {
                    $q3->where('nama_jelas', 'like', "%{$search}%");
                });
            });
        }
        
        if ($request->filled('tipe_alur')) {
            $historyQuery->where('tipe_alur', $request->tipe_alur);
        }
        
        $sortBy = $request->input('sort_by', 'newest');
        if ($sortBy === 'newest') {
            $historyQuery->orderBy('updated_at', 'desc');
        } else {
            $historyQuery->orderBy('updated_at', 'asc');
        }
        
        // Paginate history for performance
        $history = $historyQuery->paginate(20)->withQueryString();

        // List of all ready/available devices in gudang for deployment assignment
        $availableDevices = Device::whereNull('status_kondisi')->get();

        // List of technicians for direct assignment dropdown
        $technicians = \App\Models\User::where('role', 'Teknisi')->get();

        return view('admin.approvals', compact('deployments', 'returns', 'dismantles', 'history', 'availableDevices', 'technicians'));
    }

    // Admin approves deployment request (Assigns a device)
    public function approveDeployment(Request $request, $id)
    {
        $firstAssignment = Assignment::findOrFail($id);
        
        $devices = $request->input('devices', []);
        
        if (empty($devices)) {
            return back()->with('error', 'Harap pilih minimal satu perangkat.');
        }

        $isFirst = true;

        foreach ($devices as $index => $dev) {
            $isBypass = isset($dev['bypass_device']) && $dev['bypass_device'] == '1';
            
            if ($isBypass) {
                // Bypass device registration
                $sn = $dev['serial_number_bypass'] ?? null;
                if (!$sn) {
                    return back()->with('error', 'Serial Number Bypass tidak boleh kosong.');
                }
                
                // Cek unik
                if (Device::where('serial_number', $sn)->exists()) {
                    return back()->with('error', "Serial Number $sn sudah terdaftar di database.");
                }

                // Create device in pool as ready/gudang first
                Device::create([
                    'serial_number' => $sn,
                    'jenis_merek' => $dev['jenis_merek'] ?? 'Unknown',
                    'tipe_perangkat' => $dev['tipe_perangkat'] ?? 'Unknown',
                    'status_kondisi' => null, // ready in gudang
                ]);
            } else {
                $sn = $dev['serial_number'] ?? null;
                if (!$sn) {
                    return back()->with('error', 'Pilih perangkat dari gudang.');
                }

                $deviceObj = Device::where('serial_number', $sn)->first();
                if (!$deviceObj || $deviceObj->status_kondisi !== null) {
                    return back()->with('error', "Perangkat $sn tidak tersedia di gudang.");
                }
            }

            if ($isFirst) {
                // Assign serial number, status remains Pending but with SN assigned (Ready to Pick Up)
                $firstAssignment->update([
                    'serial_number' => $sn,
                    'catatan_admin' => $request->input('catatan_admin', null),
                ]);
                $isFirst = false;
            } else {
                // Duplicate the assignment for additional devices
                Assignment::create([
                    'id_pelanggan' => $firstAssignment->id_pelanggan,
                    'id_teknisi' => $firstAssignment->id_teknisi,
                    'serial_number' => $sn,
                    'tipe_alur' => 'Pengambilan',
                    'status_approval' => 'Pending', // Ready to pick up
                    'keterangan' => $firstAssignment->keterangan,
                    'catatan_admin' => $request->input('catatan_admin', null),
                ]);
            }
        }

        return redirect()->route('admin.approvals.index')->with('success', 'Request disetujui. ' . count($devices) . ' perangkat berhasil didaftarkan dan siap diambil teknisi (Ready to Pick Up).');
    }

    // Admin rejects assignment
    public function rejectAssignment(Request $request, $id)
    {
        $assignment = Assignment::findOrFail($id);
        $assignment->update([
            'status_approval' => 'Rejected',
            'catatan_admin' => $request->input('catatan_admin', null),
        ]);

        return redirect()->route('admin.approvals.index')->with('success', 'Transaksi berhasil ditolak.');
    }

    // Admin approves return request (ACC Pengembalian)
    public function approveReturn(Request $request, $id)
    {
        $assignment = Assignment::findOrFail($id);
        
        // Update assignment
        $assignment->update([
            'status_approval' => 'Approved_by_Admin',
            'catatan_admin' => $request->input('catatan_admin', null),
        ]);

        // Update device status to Rusak
        if ($assignment->serial_number) {
            $device = Device::find($assignment->serial_number);
            if ($device) {
                $device->update([
                    'status_kondisi' => 'Rusak',
                    'alasan_rusak' => $assignment->alasan_rusak
                ]);
            }
        }

        return redirect()->route('admin.approvals.index')->with('success', 'Pengembalian barang rusak berhasil disetujui (ACC).');
    }

    // Admin approves dismantle request (ACC Dismantling)
    public function approveDismantle(Request $request, $id)
    {
        $assignment = Assignment::findOrFail($id);
        
        // Update assignment
        $assignment->update([
            'status_approval' => 'Approved_by_Admin',
            'catatan_admin' => $request->input('catatan_admin', null),
        ]);

        // Update device status to Dismantling
        if ($assignment->serial_number) {
            $device = Device::find($assignment->serial_number);
            if ($device) {
                $device->update([
                    'status_kondisi' => 'Dismantling'
                ]);
            }
        }

        // Update customer status to Terminated
        if ($assignment->id_pelanggan) {
            $customer = Customer::find($assignment->id_pelanggan);
            if ($customer) {
                $customer->update([
                    'status_langganan' => 'Terminated'
                ]);
            }
        }

        return redirect()->route('admin.approvals.index')->with('success', 'Dismantling berhasil disetujui (ACC) dan status pelanggan di-terminate.');
    }

    // Admin creates direct assignment (Bypass Request)
    public function storeDirectAssignment(Request $request)
    {
        if ($request->has('bypass_device') && $request->bypass_device == '1') {
            $request->validate([
                'id_pelanggan' => 'required|string|exists:customers,id_pelanggan',
                'id_teknisi' => 'required|string|exists:users,id_user',
                'serial_number' => 'required|string|unique:devices,serial_number',
                'jenis_merek' => 'required|string',
                'tipe_perangkat' => 'required|string',
            ], [
                'serial_number.unique' => 'Serial Number ini sudah terdaftar di database.'
            ]);

            // Create device in pool as ready/gudang first
            Device::create([
                'serial_number' => $request->serial_number,
                'jenis_merek' => $request->jenis_merek,
                'tipe_perangkat' => $request->tipe_perangkat,
                'status_kondisi' => null, // ready in gudang
            ]);
        } else {
            $request->validate([
                'id_pelanggan' => 'required|string|exists:customers,id_pelanggan',
                'id_teknisi' => 'required|string|exists:users,id_user',
                'serial_number' => 'required|string|exists:devices,serial_number',
            ]);

            $device = Device::where('serial_number', $request->serial_number)->first();
            if ($device->status_kondisi !== null) {
                return back()->with('error', 'Perangkat tidak tersedia di gudang.');
            }
        }

        // Create assignment directly set to In_Hand status
        Assignment::create([
            'id_pelanggan' => $request->id_pelanggan,
            'id_teknisi' => $request->id_teknisi,
            'serial_number' => $request->serial_number,
            'tipe_alur' => 'Pengambilan',
            'status_approval' => 'In_Hand',
        ]);

        return redirect()->route('admin.approvals.index')->with('success', 'Direct Assignment berhasil dibuat. Perangkat dipetakan ke teknisi (Status: In Hand).');
    }

    // --- TECHNICIAN FLOWS ---

    // 1. Request Deployment (Alur 1)
    public function storeDeploymentRequest(Request $request)
    {
        $request->validate([
            'id_pelanggan' => 'required|string|exists:customers,id_pelanggan',
            'req_jenis' => 'required|array',
            'req_qty' => 'required|array',
        ]);

        $reqJenis = $request->input('req_jenis');
        $reqQty = $request->input('req_qty');

        $keteranganParts = [];
        foreach ($reqJenis as $index => $jenis) {
            $qty = intval($reqQty[$index] ?? 1);
            $keteranganParts[] = $jenis . ' (' . $qty . ')';
        }
        $keteranganStr = implode(', ', $keteranganParts);

        // Create exactly ONE assignment row for the entire request
        Assignment::create([
            'id_pelanggan' => $request->id_pelanggan,
            'id_teknisi' => Auth::id(),
            'serial_number' => null,
            'tipe_alur' => 'Pengambilan',
            'status_approval' => 'Pending',
            'keterangan' => 'Request: ' . $keteranganStr,
        ]);

        return redirect()->route('teknisi.dashboard')->with('success', 'Request barang berhasil dikirim ke Admin.');
    }

    // 2. Confirm Pickup (Alur 1 - Ready to Pick Up -> In Hand)
    public function confirmPickup($id)
    {
        $assignment = Assignment::findOrFail($id);
        
        if ($assignment->status_approval !== 'Pending' || empty($assignment->serial_number)) {
            return back()->with('error', 'Status transaksi tidak valid untuk konfirmasi pengambilan.');
        }

        $assignment->update([
            'status_approval' => 'In_Hand'
        ]);

        return redirect()->route('teknisi.dashboard')->with('success', 'Konfirmasi pengambilan fisik berhasil. Status barang sekarang: In Hand.');
    }

    public function confirmPickupGroup(Request $request)
    {
        $ids = $request->input('assignment_ids', []);
        
        if (empty($ids)) {
            return back()->with('error', 'Tidak ada assignment yang dipilih.');
        }

        $assignments = Assignment::whereIn('id_transaksi', $ids)->get();

        foreach ($assignments as $assignment) {
            if ($assignment->status_approval === 'Pending' && !empty($assignment->serial_number)) {
                $assignment->update([
                    'status_approval' => 'In_Hand'
                ]);
            }
        }

        return redirect()->route('teknisi.dashboard')->with('success', 'Konfirmasi pengambilan fisik berhasil.');
    }

    // 3. Complete Deployment (Alur 1 - Selesai Pemasangan)
    public function completeDeployment(Request $request, $id)
    {
        $assignment = Assignment::findOrFail($id);

        $request->validate([
            'foto_bukti' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        // Update customer coordinates if provided and customer doesn't have them yet
        $customer = Customer::find($assignment->id_pelanggan);
        if ($customer) {
            if (empty($customer->latitude) && $request->filled('latitude')) {
                $customer->update([
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                ]);
            }
        }

        // Upload photo
        $path = null;
        if ($request->hasFile('foto_bukti')) {
            $file = $request->file('foto_bukti');
            $filename = time() . '_deploy_' . $assignment->id_transaksi . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/assignments'), $filename);
            $path = 'assignments/' . $filename;
        }

        // Complete the assignment (Auto Approve to Approved_by_Admin as per PRD)
        $assignment->update([
            'status_approval' => 'Approved_by_Admin',
            'foto_bukti' => $path
        ]);

        // Update device in pool
        if ($assignment->serial_number) {
            $device = Device::find($assignment->serial_number);
            if ($device) {
                $device->update([
                    'status_kondisi' => 'Terpasang',
                    'tanggal_pasang_awal' => Carbon::now()
                ]);
            }
        }

        return redirect()->route('teknisi.dashboard')->with('success', 'Pemasangan selesai. Bukti foto telah diupload dan perangkat aktif.');
    }

    public function completeDeploymentGroup(Request $request)
    {
        $ids = $request->input('assignment_ids', []);
        
        if (empty($ids)) {
            return back()->with('error', 'Tidak ada assignment yang dipilih.');
        }

        $request->validate([
            'foto_bukti' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $assignments = Assignment::whereIn('id_transaksi', $ids)->get();
        if ($assignments->isEmpty()) {
            return back()->with('error', 'Data tidak ditemukan.');
        }

        // Upload photo ONCE for the group
        $path = null;
        if ($request->hasFile('foto_bukti')) {
            $file = $request->file('foto_bukti');
            $filename = time() . '_deploy_group_' . $assignments->first()->id_transaksi . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/assignments'), $filename);
            $path = 'assignments/' . $filename;
        }

        // Update customer coordinates if provided
        $customer = Customer::find($assignments->first()->id_pelanggan);
        if ($customer) {
            if (empty($customer->latitude) && $request->filled('latitude')) {
                $customer->update([
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                ]);
            }
        }

        foreach ($assignments as $assignment) {
            if ($assignment->status_approval === 'In_Hand') {
                $assignment->update([
                    'status_approval' => 'Approved_by_Admin',
                    'foto_bukti' => $path
                ]);

                if ($assignment->serial_number) {
                    $device = Device::find($assignment->serial_number);
                    if ($device) {
                        $device->update([
                            'status_kondisi' => 'Terpasang',
                            'tanggal_pasang_awal' => Carbon::now()
                        ]);
                    }
                }
            }
        }

        return redirect()->route('teknisi.dashboard')->with('success', 'Pemasangan selesai. Bukti foto telah diupload dan perangkat aktif.');
    }

    // 4. Return Device (Alur 2)
    public function storeReturnRequest(Request $request)
    {
        $request->validate([
            'id_pelanggan'  => 'required|string|exists:customers,id_pelanggan',
            'alasan_rusak'  => 'required|string',
            'foto_bukti'    => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $sns = $request->input('serial_numbers', []);
        $bypassSns = $request->input('bypass_sn', []);
        $bypassJenis = $request->input('bypass_jenis', []);
        $bypassTipe = $request->input('bypass_tipe', []);

        // Filter out empty bypass SNs
        $validBypass = [];
        foreach ($bypassSns as $idx => $sn) {
            if (!empty($sn)) {
                $validBypass[] = [
                    'sn' => $sn,
                    'jenis' => $bypassJenis[$idx] ?? 'STB Huawei',
                    'tipe' => $bypassTipe[$idx] ?? 'Manual Input'
                ];
            }
        }

        if (empty($sns) && empty($validBypass)) {
            return back()->withErrors(['serial_numbers' => 'Anda harus memilih minimal satu perangkat atau mengisi opsi manual.']);
        }

        // Upload photo
        $path = null;
        if ($request->hasFile('foto_bukti')) {
            $file = $request->file('foto_bukti');
            $filename = time() . '_return_' . $request->id_pelanggan . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/assignments'), $filename);
            $path = 'assignments/' . $filename;
        }

        // 1. Process selected checkbox SNs
        foreach ($sns as $sn) {
            Assignment::create([
                'id_pelanggan'   => $request->id_pelanggan,
                'id_teknisi'     => Auth::id(),
                'serial_number'  => $sn,
                'tipe_alur'      => 'Pengembalian',
                'status_approval'=> 'Pending',
                'foto_bukti'     => $path,
                'alasan_rusak'   => $request->alasan_rusak
            ]);
        }

        // 2. Process manual bypass SNs
        foreach ($validBypass as $bypass) {
            $device = Device::find($bypass['sn']);
            if (!$device) {
                Device::create([
                    'serial_number'       => $bypass['sn'],
                    'jenis_merek'         => $bypass['jenis'],
                    'tipe_perangkat'      => $bypass['tipe'],
                    'status_kondisi'      => 'Terpasang',
                    'alasan_rusak'        => null,
                    'tanggal_pasang_awal' => null,
                ]);
            }
            Assignment::create([
                'id_pelanggan'   => $request->id_pelanggan,
                'id_teknisi'     => Auth::id(),
                'serial_number'  => $bypass['sn'],
                'tipe_alur'      => 'Pengembalian',
                'status_approval'=> 'Pending',
                'foto_bukti'     => $path,
                'alasan_rusak'   => $request->alasan_rusak
            ]);
        }

        return redirect()->route('teknisi.dashboard')->with('success', 'Laporan pengembalian barang rusak berhasil dikirim ke Admin.');
    }

    // Get active devices of a customer (for Return & Dismantle selects)
    public function getActiveDevices(Request $request)
    {
        $customerId = $request->query('id_pelanggan');
        
        // Find devices currently installed at customer's house
        // Active devices are: assignments of type 'Pengambilan' with status 'Approved_by_Admin',
        // and whose device currently has status_kondisi = 'Terpasang'
        $activeAssignments = Assignment::where('id_pelanggan', $customerId)
            ->where('tipe_alur', 'Pengambilan')
            ->where('status_approval', 'Approved_by_Admin')
            ->whereNotNull('serial_number')
            ->get();

        $devices = [];
        foreach ($activeAssignments as $assign) {
            $device = Device::find($assign->serial_number);
            if ($device && $device->status_kondisi === 'Terpasang') {
                $devices[] = [
                    'serial_number' => $device->serial_number,
                    'jenis_merek' => $device->jenis_merek,
                    'tipe_perangkat' => $device->tipe_perangkat,
                ];
            }
        }

        return response()->json([
            'success' => true,
            'devices' => $devices
        ]);
    }

    // 5. Dismantle Devices (Alur 3)
    public function storeDismantleRequest(Request $request)
    {
        $request->validate([
            'id_pelanggan' => 'required|string|exists:customers,id_pelanggan',
            'foto_bukti' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $sns = $request->input('serial_numbers', []); 
        $otherSns = $request->input('other_serial_numbers', []);
        $otherJenis = $request->input('other_jenis_merek', []);
        $otherTipe = $request->input('other_tipe_perangkat', []);

        $validOther = [];
        foreach ($otherSns as $idx => $sn) {
            if (!empty($sn)) {
                $validOther[] = [
                    'sn' => $sn,
                    'jenis' => $otherJenis[$idx] ?? 'STB Huawei',
                    'tipe' => $otherTipe[$idx] ?? 'Manual Input'
                ];
            }
        }

        if (empty($sns) && empty($validOther)) {
            return back()->withErrors(['serial_numbers' => 'Anda harus memilih minimal satu perangkat atau mengisi opsi manual Lainnya.']);
        }

        // Upload photo
        $path = null;
        if ($request->hasFile('foto_bukti')) {
            $file = $request->file('foto_bukti');
            $filename = time() . '_dismantle_' . $request->id_pelanggan . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/assignments'), $filename);
            $path = 'assignments/' . $filename;
        }

        // 1. Process selected checkbox SNs
        foreach ($sns as $sn) {
            Assignment::create([
                'id_pelanggan' => $request->id_pelanggan,
                'id_teknisi' => Auth::id(),
                'serial_number' => $sn,
                'tipe_alur' => 'Dismantling',
                'status_approval' => 'Pending',
                'foto_bukti' => $path,
            ]);
        }

        // 2. Process manual other SNs
        foreach ($validOther as $other) {
            $device = Device::find($other['sn']);
            if (!$device) {
                Device::create([
                    'serial_number'       => $other['sn'],
                    'jenis_merek'         => $other['jenis'],
                    'tipe_perangkat'      => $other['tipe'],
                    'status_kondisi'      => 'Terpasang',
                    'alasan_rusak'        => null,
                    'tanggal_pasang_awal' => null
                ]);
            }
            Assignment::create([
                'id_pelanggan'    => $request->id_pelanggan,
                'id_teknisi'      => Auth::id(),
                'serial_number'   => $other['sn'],
                'tipe_alur'       => 'Dismantling',
                'status_approval' => 'Pending',
                'foto_bukti'      => $path,
            ]);
        }

        return redirect()->route('teknisi.dashboard')->with('success', 'Laporan pembongkaran (dismantling) berhasil dikirim ke Admin.');
    }
}
