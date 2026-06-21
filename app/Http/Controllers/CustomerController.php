<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    // Admin: List all customers
    public function index()
    {
        if (Auth::user()->role === 'Teknisi') {
            abort(403, 'Unauthorized.');
        }
        $customers = Customer::withCount(['assignments'])
            ->with(['assignments' => function($q) {
                $q->where('tipe_alur', 'Pengambilan')
                  ->where('status_approval', 'Approved_by_Admin')
                  ->with('device');
            }])
            ->orderBy('nama_pelanggan')
            ->get();
        return view('admin.customers', compact('customers'));
    }

    // Admin: Update customer
    public function update(Request $request, $id_pelanggan)
    {
        if (Auth::user()->role === 'Teknisi') {
            abort(403, 'Unauthorized.');
        }
        $customer = Customer::findOrFail($id_pelanggan);
        $request->validate([
            'nama_pelanggan' => 'required|string',
            'no_telepon' => 'required|string',
            'alamat_pemasangan' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'status_langganan' => 'required|in:Active,Suspended,Terminated',
        ]);
        $customer->update($request->only(['nama_pelanggan', 'no_telepon', 'alamat_pemasangan', 'latitude', 'longitude', 'status_langganan']));
        return redirect()->route('admin.customers.index')->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    // Admin: Delete customer
    public function destroy($id_pelanggan)
    {
        if (Auth::user()->role === 'Teknisi') {
            abort(403, 'Unauthorized.');
        }
        $customer = Customer::findOrFail($id_pelanggan);
        // Check if has active installed devices
        $activeCount = $customer->assignments()
            ->where('tipe_alur', 'Pengambilan')
            ->where('status_approval', 'Approved_by_Admin')
            ->count();
        if ($activeCount > 0) {
            return redirect()->route('admin.customers.index')->with('error', 'Pelanggan tidak bisa dihapus karena masih memiliki perangkat aktif terpasang.');
        }
        $customer->delete();
        return redirect()->route('admin.customers.index')->with('success', 'Data pelanggan berhasil dihapus.');
    }

    // Search customer on the fly (for AJAX)
    public function search(Request $request)
    {
        $id = $request->query('id_pelanggan');
        $customer = Customer::find($id);

        if ($customer) {
            return response()->json([
                'success' => true,
                'customer' => $customer
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Data tidak ditemukan'
        ]);
    }

    // Bypass / On-The-Fly Input
    public function bypassStore(Request $request)
    {
        $validated = $request->validate([
            'id_pelanggan' => 'required|string|unique:customers,id_pelanggan',
            'nama_pelanggan' => 'required|string',
            'no_telepon' => 'required|string',
            'alamat_pemasangan' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $customer = Customer::create(array_merge($validated, [
            'status_langganan' => 'Active'
        ]));

        return response()->json([
            'success' => true,
            'customer' => $customer,
            'message' => 'Pelanggan berhasil ditambahkan melalui bypass.'
        ]);
    }

    // Show import page
    public function showImport()
    {
        if (Auth::user()->role === 'Teknisi') {
            abort(403, 'Unauthorized.');
        }
        return view('admin.import');
    }

    // Parse uploaded CSV/Excel
    public function parseImport(Request $request)
    {
        if (Auth::user()->role === 'Teknisi') {
            abort(403, 'Unauthorized.');
        }
        $request->validate([
            'import_file' => 'required|file|mimes:csv,txt'
        ]);

        $file = $request->file('import_file');
        $path = $file->getRealPath();
        
        $rows = [];
        if (($handle = fopen($path, 'r')) !== FALSE) {
            // Read header
            $header = fgetcsv($handle, 1000, ',');
            // Fallback for semicolon separated CSVs
            if (count($header) === 1 && strpos($header[0], ';') !== false) {
                rewind($handle);
                $header = fgetcsv($handle, 1000, ';');
                $separator = ';';
            } else {
                $separator = ',';
            }

            // Normalise header to clean lowercase
            $header = array_map(function($h) {
                return strtolower(trim(preg_replace('/[\x00-\x1F\x7F-\x9F\xEF\xBB\xBF]/', '', $h)));
            }, $header);

            while (($data = fgetcsv($handle, 1000, $separator)) !== FALSE) {
                if (count($header) == count($data)) {
                    $row = array_combine($header, $data);
                    $rows[] = [
                        'id_pelanggan' => trim($row['id_pelanggan'] ?? $row['id'] ?? ''),
                        'nama_pelanggan' => trim($row['nama_pelanggan'] ?? $row['nama'] ?? ''),
                        'no_telepon' => trim($row['no_telepon'] ?? $row['no_telp'] ?? $row['telepon'] ?? ''),
                        'alamat_pemasangan' => trim($row['alamat_pemasangan'] ?? $row['alamat'] ?? ''),
                    ];
                }
            }
            fclose($handle);
        }

        // Detect conflicts and successes
        $successRows = [];
        $conflictRows = [];

        foreach ($rows as $row) {
            if (empty($row['id_pelanggan'])) continue;

            $existing = Customer::find($row['id_pelanggan']);
            if ($existing) {
                $conflictRows[] = [
                    'new' => $row,
                    'old' => $existing->toArray()
                ];
            } else {
                $successRows[] = $row;
            }
        }

        // Save in session
        Session::put('import_success_rows', $successRows);
        Session::put('import_conflict_rows', $conflictRows);

        return view('admin.import-preview', [
            'total_rows' => count($rows),
            'success_count' => count($successRows),
            'conflict_count' => count($conflictRows),
            'conflict_rows' => $conflictRows
        ]);
    }

    // Resolve conflicts globally (massal)
    public function resolveConflict(Request $request)
    {
        if (Auth::user()->role === 'Teknisi') {
            abort(403, 'Unauthorized.');
        }
        $strategy = $request->input('strategy'); // 'skip', 'overwrite', 'keep_both'
        if (!in_array($strategy, ['skip', 'overwrite', 'keep_both'])) {
            return redirect()->route('admin.import.show')->with('error', 'Strategi resolusi tidak valid.');
        }

        $successRows = Session::get('import_success_rows', []);
        $conflictRows = Session::get('import_conflict_rows', []);

        // 1. Process non-conflicting rows first
        foreach ($successRows as $row) {
            Customer::create([
                'id_pelanggan' => $row['id_pelanggan'],
                'nama_pelanggan' => $row['nama_pelanggan'],
                'no_telepon' => $row['no_telepon'],
                'alamat_pemasangan' => $row['alamat_pemasangan'],
                'status_langganan' => 'Active'
            ]);
        }

        // 2. Process conflicting rows based on strategy
        $processedConflictCount = 0;
        if ($strategy === 'overwrite') {
            foreach ($conflictRows as $conf) {
                $new = $conf['new'];
                $existing = Customer::find($new['id_pelanggan']);
                if ($existing) {
                    $existing->update([
                        'nama_pelanggan' => $new['nama_pelanggan'],
                        'no_telepon' => $new['no_telepon'],
                        'alamat_pemasangan' => $new['alamat_pemasangan'],
                    ]);
                    $processedConflictCount++;
                }
            }
        } elseif ($strategy === 'keep_both') {
            foreach ($conflictRows as $conf) {
                $new = $conf['new'];
                $originalId = $new['id_pelanggan'];
                $suffix = 1;
                
                // Find a unique modified ID
                do {
                    $newId = $originalId . '_DUP' . $suffix;
                    $exists = Customer::find($newId);
                    $suffix++;
                } while ($exists);

                Customer::create([
                    'id_pelanggan' => $newId,
                    'nama_pelanggan' => $new['nama_pelanggan'],
                    'no_telepon' => $new['no_telepon'],
                    'alamat_pemasangan' => $new['alamat_pemasangan'],
                    'status_langganan' => 'Active'
                ]);
                $processedConflictCount++;
            }
        }
        // If strategy is 'skip', we just do nothing with conflict rows (they are ignored)

        // Clear session
        Session::forget('import_success_rows');
        Session::forget('import_conflict_rows');

        $message = "Import selesai. " . count($successRows) . " data baru diimpor.";
        if ($strategy === 'overwrite') {
            $message .= " {$processedConflictCount} data lama ditimpa.";
        } elseif ($strategy === 'keep_both') {
            $message .= " {$processedConflictCount} data duplikat disimpan dengan ID baru.";
        } else {
            $message .= " " . count($conflictRows) . " data konflik diabaikan.";
        }

        return redirect()->route('admin.dashboard')->with('success', $message);
    }
}
