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
        // 0. Ensure storage directory exists and download dummy images
        $storagePath = storage_path('app/public/assignments');
        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0755, true);
        }

        // Create some simple dummy image files using PHP GD
        $images = [
            'proof_pasang.jpg' => 'Bukti Pemasangan',
            'proof_rusak.jpg' => 'Bukti Perangkat Rusak',
            'proof_dismantle.jpg' => 'Bukti Dismantle'
        ];

        foreach ($images as $filename => $text) {
            $image = imagecreatetruecolor(400, 300);
            $bg = imagecolorallocate($image, 240, 240, 240);
            $color = imagecolorallocate($image, 50, 50, 50);
            imagefill($image, 0, 0, $bg);
            imagestring($image, 5, 50, 140, $text, $color);
            imagejpeg($image, $storagePath . '/' . $filename);
            imagedestroy($image);
        }

        // 1. Create Default Users
        $admin = User::create([
            'id_user' => 'usr-admin',
            'nama_jelas' => 'Admin Gudang',
            'username' => 'admin',
            'password' => Hash::make('password'),
            'role' => 'Admin',
        ]);

        $teknisi1 = User::create([
            'id_user' => 'usr-tek-01',
            'nama_jelas' => 'Budi Santoso (Teknisi 1)',
            'username' => 'teknisi1',
            'password' => Hash::make('password'),
            'role' => 'Teknisi',
        ]);

        $teknisi2 = User::create([
            'id_user' => 'usr-tek-02',
            'nama_jelas' => 'Andi Wijaya (Teknisi 2)',
            'username' => 'teknisi2',
            'password' => Hash::make('password'),
            'role' => 'Teknisi',
        ]);

        // 2. Create Customers
        $customer1 = Customer::create([
            'id_pelanggan' => 'PLG-2026-001',
            'nama_pelanggan' => 'John Doe (Baru Pasang)',
            'no_telepon' => '081234567891',
            'alamat_pemasangan' => 'Jl. Sudirman No. 12, Jakarta',
            'status_langganan' => 'Active',
        ]);

        $customer2 = Customer::create([
            'id_pelanggan' => 'PLG-2026-OLD',
            'nama_pelanggan' => 'Bambang (Pasang > 3 Tahun)',
            'no_telepon' => '081234567892',
            'alamat_pemasangan' => 'Jl. Menteng Raya No. 4, Jakarta',
            'status_langganan' => 'Active',
        ]);

        $customer3 = Customer::create([
            'id_pelanggan' => 'PLG-2026-RUSAK',
            'nama_pelanggan' => 'Siti (Perangkat Rusak)',
            'no_telepon' => '081234567893',
            'alamat_pemasangan' => 'Jl. Thamrin No. 8, Jakarta',
            'status_langganan' => 'Active',
        ]);

        $customer4 = Customer::create([
            'id_pelanggan' => 'PLG-2026-DISMANTLE',
            'nama_pelanggan' => 'Rudi (Dismantle)',
            'no_telepon' => '081234567894',
            'alamat_pemasangan' => 'Jl. Gatot Subroto No. 9, Jakarta',
            'status_langganan' => 'Terminated',
        ]);

        // 3. Create Devices
        // Device 1: Ready in warehouse
        Device::create([
            'serial_number' => 'SN-MODEM-READY',
            'jenis_merek' => 'Modem ZTE',
            'tipe_perangkat' => 'F609',
            'status_kondisi' => null,
            'alasan_rusak' => null,
            'tanggal_pasang_awal' => null,
        ]);

        // Device 2: Terpasang (Baru)
        Device::create([
            'serial_number' => 'SN-STB-BARU',
            'jenis_merek' => 'STB ZTE',
            'tipe_perangkat' => 'B860H',
            'status_kondisi' => 'Terpasang',
            'alasan_rusak' => null,
            'tanggal_pasang_awal' => Carbon::now()->subMonths(2),
        ]);

        // Device 3: Terpasang (> 3 Tahun)
        Device::create([
            'serial_number' => 'SN-STB-OLD-999',
            'jenis_merek' => 'STB Huawei',
            'tipe_perangkat' => 'huawei790',
            'status_kondisi' => 'Terpasang',
            'alasan_rusak' => null,
            'tanggal_pasang_awal' => Carbon::now()->subYears(3)->subMonths(1), // > 3 years
        ]);

        // Device 4: Rusak
        Device::create([
            'serial_number' => 'SN-MODEM-RUSAK',
            'jenis_merek' => 'Modem Huawei',
            'tipe_perangkat' => 'HG8245H',
            'status_kondisi' => 'Rusak',
            'alasan_rusak' => 'Mati total tersambar petir',
            'tanggal_pasang_awal' => Carbon::now()->subMonths(10),
        ]);

        // Device 5: Dismantling
        Device::create([
            'serial_number' => 'SN-STB-DISMANTLE',
            'jenis_merek' => 'STB ZTE',
            'tipe_perangkat' => 'B860H',
            'status_kondisi' => 'Dismantling',
            'alasan_rusak' => null,
            'tanggal_pasang_awal' => Carbon::now()->subMonths(14),
        ]);

        // 4. Create Assignments (Transactions)
        // Pasang Baru -> Customer 1
        Assignment::create([
            'id_pelanggan' => $customer1->id_pelanggan,
            'id_teknisi' => $teknisi1->id_user,
            'serial_number' => 'SN-STB-BARU',
            'tipe_alur' => 'Pengambilan',
            'status_approval' => 'Approved_by_Admin',
            'foto_bukti' => 'assignments/proof_pasang.jpg',
        ]);

        // Pasang Lama -> Customer 2
        Assignment::create([
            'id_pelanggan' => $customer2->id_pelanggan,
            'id_teknisi' => $teknisi2->id_user,
            'serial_number' => 'SN-STB-OLD-999',
            'tipe_alur' => 'Pengambilan',
            'status_approval' => 'Approved_by_Admin',
            'foto_bukti' => 'assignments/proof_pasang.jpg',
        ]);

        // Pengembalian (Rusak) -> Customer 3
        Assignment::create([
            'id_pelanggan' => $customer3->id_pelanggan,
            'id_teknisi' => $teknisi1->id_user,
            'serial_number' => 'SN-MODEM-RUSAK',
            'tipe_alur' => 'Pengembalian',
            'status_approval' => 'Approved_by_Admin',
            'alasan_rusak' => 'Mati total tersambar petir',
            'foto_bukti' => 'assignments/proof_rusak.jpg',
        ]);

        // Dismantling -> Customer 4
        Assignment::create([
            'id_pelanggan' => $customer4->id_pelanggan,
            'id_teknisi' => $teknisi2->id_user,
            'serial_number' => 'SN-STB-DISMANTLE',
            'tipe_alur' => 'Dismantling',
            'status_approval' => 'Approved_by_Admin',
            'foto_bukti' => 'assignments/proof_dismantle.jpg',
        ]);
    }
}
