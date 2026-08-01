<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Device;
use Illuminate\Support\Facades\Auth;

class DeviceController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->role === 'Teknisi') {
            abort(403, 'Unauthorized.');
        }

        $query = Device::with(['assignments.customer', 'assignments.teknisi']);

        // Filter by Search (SN or Jenis/Tipe)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('serial_number', 'like', "%{$search}%")
                  ->orWhere('jenis_merek', 'like', "%{$search}%")
                  ->orWhere('tipe_perangkat', 'like', "%{$search}%");
            });
        }

        // Filter by Status
        if ($request->filled('status')) {
            if ($request->status === 'Ready') {
                $query->whereNull('status_kondisi');
            } else {
                $query->where('status_kondisi', $request->status);
            }
        }

        // Sorting
        $sortBy = $request->input('sort_by', 'default');
        if ($sortBy === 'newest') {
            $query->orderBy('created_at', 'desc');
        } elseif ($sortBy === 'oldest') {
            $query->orderBy('created_at', 'asc');
        } elseif ($sortBy === 'last_edited') {
            $query->orderBy('updated_at', 'desc');
        } else {
            // Default sort (EWS style)
            $query->orderByRaw('CASE WHEN tanggal_pasang_awal IS NOT NULL THEN 0 ELSE 1 END')
                  ->orderBy('tanggal_pasang_awal', 'asc')
                  ->orderBy('created_at', 'desc');
        }

        $devices = $query->get();

        return view('admin.devices', compact('devices'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role === 'Teknisi') {
            abort(403, 'Unauthorized.');
        }
        $validated = $request->validate([
            'serial_number' => 'required|string|unique:devices,serial_number',
            'jenis_merek' => 'required|string|in:STB Huawei,STB ZTE,Modem ZTE,Modem Huawei',
            'tipe_perangkat' => 'required|string',
        ]);

        Device::create(array_merge($validated, [
            'status_kondisi' => null, // null means Ready in warehouse
            'alasan_rusak' => null,
            'tanggal_pasang_awal' => null,
        ]));

        return redirect()->route('admin.devices.index')->with('success', 'Perangkat baru berhasil didaftarkan ke pool gudang.');
    }

    public function update(Request $request, $serial_number)
    {
        if (Auth::user()->role === 'Teknisi') {
            abort(403, 'Unauthorized.');
        }

        $device = Device::findOrFail($serial_number);

        if (in_array($device->status_kondisi, ['Terpasang', 'Dismantling', 'Rusak'])) {
            return redirect()->route('admin.devices.index')->with('error', 'Perangkat tidak bisa diedit karena statusnya ' . $device->status_kondisi . '.');
        }

        $request->validate([
            'jenis_merek' => 'required|string|in:STB Huawei,STB ZTE,Modem ZTE,Modem Huawei',
            'tipe_perangkat' => 'required|string',
            'alasan_rusak' => 'nullable|string',
        ]);

        $device->update([
            'jenis_merek' => $request->jenis_merek,
            'tipe_perangkat' => $request->tipe_perangkat,
            'alasan_rusak' => $request->alasan_rusak,
        ]);

        return redirect()->route('admin.devices.index')->with('success', 'Data perangkat berhasil diperbarui.');
    }

    public function destroy($serial_number)
    {
        if (Auth::user()->role === 'Teknisi') {
            abort(403, 'Unauthorized.');
        }

        $device = Device::findOrFail($serial_number);

        if ($device->status_kondisi === 'Terpasang') {
            return redirect()->route('admin.devices.index')->with('error', 'Perangkat tidak bisa dihapus karena sedang terpasang di lokasi pelanggan.');
        }

        $device->delete();

        return redirect()->route('admin.devices.index')->with('success', 'Perangkat berhasil dihapus dari sistem.');
    }
}
