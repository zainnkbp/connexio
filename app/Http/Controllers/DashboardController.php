<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Device;
use App\Models\Assignment;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function adminDashboard()
    {
        if (Auth::user()->role === 'Teknisi') {
            return redirect()->route('teknisi.dashboard');
        }
        // 1. Fetch devices for EWS table (Status = Terpasang)
        // Ordered by tanggal_pasang_awal ascending (masa pakai terlama di atas)
        $devices = Device::where('status_kondisi', 'Terpasang')
            ->orderBy('tanggal_pasang_awal', 'asc')
            ->get();

        // Add duration calculations to each device
        foreach ($devices as $device) {
            if ($device->tanggal_pasang_awal) {
                $start = Carbon::parse($device->tanggal_pasang_awal);
                $now = Carbon::now();
                
                $diffYears = $start->diffInYears($now);
                $diffMonths = $start->diffInMonths($now) % 12;
                $diffDays = $start->diffInDays($now) % 30; // rough day count

                $device->durasi_pakai = "{$diffYears} Tahun {$diffMonths} Bulan {$diffDays} Hari";
                $device->months_total = $start->diffInMonths($now);

                // Find the assignment that linked this device to a customer
                $installAssignment = Assignment::where('serial_number', $device->serial_number)
                    ->where('tipe_alur', 'Pengambilan')
                    ->where('status_approval', 'Approved_by_Admin')
                    ->first();
                
                $device->customer = $installAssignment ? $installAssignment->customer : null;
                $device->teknisi = $installAssignment ? $installAssignment->teknisi : null;
            } else {
                $device->durasi_pakai = "-";
                $device->months_total = 0;
                $device->customer = null;
                $device->teknisi = null;
            }
        }

        // 2. Fetch counts for analytics cards (Global system stats)
        $pendingCount = Assignment::where('status_approval', 'Pending')->count();
        $inHandCount = Assignment::where('status_approval', 'In_Hand')->count();
        $completedCount = Assignment::where('status_approval', 'Approved_by_Admin')->count();

        return view('admin.dashboard', compact('devices', 'pendingCount', 'inHandCount', 'completedCount'));
    }

    public function teknisiDashboard()
    {
        if (Auth::user()->role !== 'Teknisi') {
            return redirect()->route('admin.dashboard');
        }
        $techId = Auth::id();

        // 1. Fetch technician tasks
        // We want to show their assignments (recent or active)
        $assignmentsRaw = Assignment::where('id_teknisi', $techId)
            ->with(['customer', 'device'])
            ->orderBy('updated_at', 'desc')
            ->get();

        $assignments = $assignmentsRaw->groupBy(function($item) {
            // Group by customer, flow type, status, and description so related devices appear as one card
            return $item->id_pelanggan . '_' . $item->tipe_alur . '_' . $item->status_approval . '_' . $item->keterangan;
        });

        // 2. Counts for technician analytics cards
        $pendingCount = Assignment::where('id_teknisi', $techId)->where('status_approval', 'Pending')->count();
        $inHandCount = Assignment::where('id_teknisi', $techId)->where('status_approval', 'In_Hand')->count();
        $completedCount = Assignment::where('id_teknisi', $techId)->where('status_approval', 'Approved_by_Admin')->count();

        // List of all active customers for the request deployment dropdown
        $customers = Customer::where('status_langganan', 'Active')->get();

        return view('teknisi.dashboard', compact('assignments', 'pendingCount', 'inHandCount', 'completedCount', 'customers'));
    }
}
