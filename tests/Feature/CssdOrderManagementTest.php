<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\Unit;

class CssdOrderManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_cssd_user_can_approve_an_order()
    {
        // Buat pengguna dengan peran 'cssd'
        $cssdUser = User::factory()->create(['role' => 'cssd']);
        $this->actingAs($cssdUser);

        // Buat pesanan yang akan diuji
        $order = Order::factory()->create(['status' => 'pending']);

        // Kirim permintaan untuk mengubah status menjadi 'approved'
        $response = $this->post(route('orders.updateStatus', $order), [
            'status' => 'approved',
        ]);

        // Periksa apakah respons berhasil dan status pesanan diperbarui di database
        $response->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'approved',
        ]);
    }

    public function test_cssd_user_can_reject_an_order()
    {
        $cssdUser = User::factory()->create(['role' => 'cssd']);
        $this->actingAs($cssdUser);

        $order = Order::factory()->create(['status' => 'pending']);

        $response = $this->post(route('orders.updateStatus', $order), [
            'status' => 'rejected',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'rejected',
        ]);
    }

    public function test_non_cssd_user_cannot_update_order_status()
    {
        // Buat pengguna dengan peran 'unit' (bukan cssd/admin)
        $unitUser = User::factory()->create(['role' => 'unit']);
        $this->actingAs($unitUser);

        $order = Order::factory()->create(['status' => 'pending']);

        $response = $this->post(route('orders.updateStatus', $order), [
            'status' => 'approved',
        ]);

        // Periksa apakah permintaan ditolak (Forbidden)
        $response->assertStatus(403);
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'pending', // Status seharusnya tidak berubah
        ]);
    }
}
