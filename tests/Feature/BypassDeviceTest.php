<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Device;
use App\Models\Customer;
use App\Models\Assignment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BypassDeviceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create Admin User
        $this->admin = User::create([
            'id_user' => 'admin-test',
            'nama_jelas' => 'Admin Test',
            'username' => 'admintest',
            'password' => bcrypt('password'),
            'role' => 'Admin',
        ]);

        // Create Customer
        $this->customer = Customer::create([
            'id_pelanggan' => 'PLG-TEST',
            'nama_pelanggan' => 'Customer Test',
            'no_telepon' => '0812345678',
            'alamat_pemasangan' => 'Alamat Test',
            'status_langganan' => 'Active',
        ]);

        // Create Technician User
        $this->teknisi = User::create([
            'id_user' => 'tech-test',
            'nama_jelas' => 'Technician Test',
            'username' => 'techtest',
            'password' => bcrypt('password'),
            'role' => 'Teknisi',
        ]);

        // Create Pending Deployment Assignment
        $this->assignment = Assignment::create([
            'id_pelanggan' => 'PLG-TEST',
            'id_teknisi' => 'tech-test',
            'serial_number' => null,
            'tipe_alur' => 'Pengambilan',
            'status_approval' => 'Pending',
        ]);
    }

    /** @test */
    public function admin_can_approve_deployment_with_device_bypass()
    {
        $this->actingAs($this->admin);

        $response = $this->post(route('admin.approvals.approve-deployment', $this->assignment->id_transaksi), [
            'bypass_device' => '1',
            'serial_number' => 'BYPASS-MODEM-999',
            'jenis_merek' => 'Modem ZTE',
            'tipe_perangkat' => 'F609',
        ]);

        $response->assertRedirect(route('admin.approvals.index'));
        $response->assertSessionHas('success');

        // Check if device was created
        $this->assertDatabaseHas('devices', [
            'serial_number' => 'BYPASS-MODEM-999',
            'jenis_merek' => 'Modem ZTE',
            'tipe_perangkat' => 'F609',
            'status_kondisi' => null,
        ]);

        // Check if assignment was updated
        $this->assignment->refresh();
        $this->assertEquals('BYPASS-MODEM-999', $this->assignment->serial_number);
    }

    /** @test */
    public function admin_fails_bypass_if_serial_number_already_exists()
    {
        // Pre-create the device
        Device::create([
            'serial_number' => 'DUPLICATE-SN',
            'jenis_merek' => 'Modem Huawei',
            'tipe_perangkat' => 'HG8245H',
            'status_kondisi' => null,
        ]);

        $this->actingAs($this->admin);

        $response = $this->post(route('admin.approvals.approve-deployment', $this->assignment->id_transaksi), [
            'bypass_device' => '1',
            'serial_number' => 'DUPLICATE-SN',
            'jenis_merek' => 'Modem ZTE',
            'tipe_perangkat' => 'F609',
        ]);

        $response->assertSessionHasErrors(['serial_number']);
        
        // Ensure no new device was created and assignment wasn't updated
        $this->assignment->refresh();
        $this->assertNull($this->assignment->serial_number);
    }

    /** @test */
    public function admin_can_create_direct_assignment_with_device_bypass()
    {
        $this->actingAs($this->admin);

        $response = $this->post(route('admin.approvals.direct'), [
            'id_pelanggan' => 'PLG-TEST',
            'id_teknisi' => 'tech-test',
            'bypass_device' => '1',
            'serial_number' => 'DIRECT-BYPASS-SN-123',
            'jenis_merek' => 'Modem Huawei',
            'tipe_perangkat' => 'HG8245H',
        ]);

        $response->assertRedirect(route('admin.approvals.index'));
        $response->assertSessionHas('success');

        // Check if device was created
        $this->assertDatabaseHas('devices', [
            'serial_number' => 'DIRECT-BYPASS-SN-123',
            'jenis_merek' => 'Modem Huawei',
            'tipe_perangkat' => 'HG8245H',
            'status_kondisi' => null,
        ]);

        // Check if assignment was created directly with In_Hand status and the new device SN
        $this->assertDatabaseHas('assignments', [
            'id_pelanggan' => 'PLG-TEST',
            'id_teknisi' => 'tech-test',
            'serial_number' => 'DIRECT-BYPASS-SN-123',
            'tipe_alur' => 'Pengambilan',
            'status_approval' => 'In_Hand',
        ]);
    }
}
