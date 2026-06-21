<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Device;
use App\Models\Customer;
use App\Models\Assignment;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Default Users (Super Admin, Admin, Teknisi)
        User::create([
            'id_user' => 'usr-superadmin',
            'nama_jelas' => 'Super Admin Utama',
            'username' => 'superadmin',
            'password' => Hash::make('password'),
            'role' => 'Super Admin',
        ]);

        User::create([
            'id_user' => 'usr-admin',
            'nama_jelas' => 'Admin Gudang',
            'username' => 'admin',
            'password' => Hash::make('password'),
            'role' => 'Admin',
        ]);

        User::create([
            'id_user' => 'usr-teknisi',
            'nama_jelas' => 'Budi Santoso',
            'username' => 'teknisi',
            'password' => Hash::make('password'),
            'role' => 'Teknisi',
        ]);

        // 2. Create Default Pool Devices
        Device::create([
            'serial_number' => 'SN-MODEM-ZTE-001',
            'jenis_merek' => 'Modem ZTE',
            'tipe_perangkat' => 'F609',
            'status_kondisi' => null, // in warehouse
            'alasan_rusak' => null,
            'tanggal_pasang_awal' => null,
        ]);

        Device::create([
            'serial_number' => 'SN-MODEM-HUA-002',
            'jenis_merek' => 'Modem Huawei',
            'tipe_perangkat' => 'HG8245H',
            'status_kondisi' => null,
            'alasan_rusak' => null,
            'tanggal_pasang_awal' => null,
        ]);

        Device::create([
            'serial_number' => 'SN-STB-HUA-003',
            'jenis_merek' => 'STB Huawei',
            'tipe_perangkat' => 'huawei790',
            'status_kondisi' => null,
            'alasan_rusak' => null,
            'tanggal_pasang_awal' => null,
        ]);

        Device::create([
            'serial_number' => 'SN-STB-ZTE-004',
            'jenis_merek' => 'STB ZTE',
            'tipe_perangkat' => 'B860H',
            'status_kondisi' => null,
            'alasan_rusak' => null,
            'tanggal_pasang_awal' => null,
        ]);

        // Create an old device installed > 3 years ago (for EWS check)
        Device::create([
            'serial_number' => 'SN-STB-OLD-999',
            'jenis_merek' => 'STB Huawei',
            'tipe_perangkat' => 'huawei790',
            'status_kondisi' => 'Terpasang',
            'alasan_rusak' => null,
            'tanggal_pasang_awal' => Carbon::now()->subYears(3)->subMonths(2), // 3 years and 2 months ago
        ]);

        // 3. Create Default Customers
        Customer::create([
            'id_pelanggan' => 'PLG-2026-001',
            'nama_pelanggan' => 'John Doe',
            'no_telepon' => '081234567890',
            'alamat_pemasangan' => 'Jl. Sudirman No. 12, Jakarta',
            'latitude' => -6.2088,
            'longitude' => 106.8456,
            'status_langganan' => 'Active',
        ]);

        Customer::create([
            'id_pelanggan' => 'PLG-2026-002',
            'nama_pelanggan' => 'Jane Smith',
            'no_telepon' => '081298765432',
            'alamat_pemasangan' => 'Gedung Artha Graha Lt. 15, Jakarta',
            'latitude' => null,
            'longitude' => null, // empty for tech coordinates input test
            'status_langganan' => 'Active',
        ]);

        Customer::create([
            'id_pelanggan' => 'PLG-2026-OLD',
            'nama_pelanggan' => 'Bambang Wijaya',
            'no_telepon' => '081300001111',
            'alamat_pemasangan' => 'Jl. Menteng Raya No. 4, Jakarta',
            'latitude' => -6.1852,
            'longitude' => 106.8313,
            'status_langganan' => 'Active',
        ]);

        // 4. Create Transaction Assignment for old device
        Assignment::create([
            'id_pelanggan' => 'PLG-2026-OLD',
            'id_teknisi' => 'usr-teknisi',
            'serial_number' => 'SN-STB-OLD-999',
            'tipe_alur' => 'Pengambilan',
            'status_approval' => 'Approved_by_Admin',
            'foto_bukti' => 'seeded_old_device_proof.jpg',
        ]);

        // 5. Create Pending Transactions for Admin Approval Testing

        // Pending Deployment Request (no serial number assigned yet)
        Assignment::create([
            'id_pelanggan' => 'PLG-2026-002',
            'id_teknisi' => 'usr-teknisi',
            'serial_number' => null,
            'tipe_alur' => 'Pengambilan',
            'status_approval' => 'Pending',
        ]);

        // Create an installed device for return testing
        Device::create([
            'serial_number' => 'SN-MODEM-ZTE-RETURN',
            'jenis_merek' => 'Modem ZTE',
            'tipe_perangkat' => 'F609',
            'status_kondisi' => 'Terpasang',
            'alasan_rusak' => null,
            'tanggal_pasang_awal' => Carbon::now()->subMonths(6),
        ]);

        // Pending Return Request
        Assignment::create([
            'id_pelanggan' => 'PLG-2026-001',
            'id_teknisi' => 'usr-teknisi',
            'serial_number' => 'SN-MODEM-ZTE-RETURN',
            'tipe_alur' => 'Pengembalian',
            'status_approval' => 'Pending',
            'foto_bukti' => 'storage/assignments/seeded_return_proof.jpg',
            'alasan_rusak' => 'Modem sering restart sendiri',
        ]);

        // Create an installed device for dismantle testing
        Device::create([
            'serial_number' => 'SN-STB-ZTE-DISMANTLE',
            'jenis_merek' => 'STB ZTE',
            'tipe_perangkat' => 'B860H',
            'status_kondisi' => 'Terpasang',
            'alasan_rusak' => null,
            'tanggal_pasang_awal' => Carbon::now()->subMonths(12),
        ]);

        // Pending Dismantle Request
        Assignment::create([
            'id_pelanggan' => 'PLG-2026-001',
            'id_teknisi' => 'usr-teknisi',
            'serial_number' => 'SN-STB-ZTE-DISMANTLE',
            'tipe_alur' => 'Dismantling',
            'status_approval' => 'Pending',
            'foto_bukti' => 'storage/assignments/seeded_dismantle_proof.jpg',
        ]);
    }
}
