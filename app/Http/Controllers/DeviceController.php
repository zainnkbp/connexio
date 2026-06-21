<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Device;
use Illuminate\Support\Facades\Auth;

class DeviceController extends Controller
{
    public function index()
    {
        if (Auth::user()->role === 'Teknisi') {
            abort(403, 'Unauthorized.');
        }
        $devices = Device::with(['assignments.customer', 'assignments.teknisi'])
            ->orderByRaw('CASE WHEN tanggal_pasang_awal IS NOT NULL THEN 0 ELSE 1 END')
            ->orderBy('tanggal_pasang_awal', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();
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
