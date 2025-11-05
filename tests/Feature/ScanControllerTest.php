<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Instrument;
use App\Models\Pouch;
use App\Models\Unit;

class ScanControllerTest extends TestCase
{
    use RefreshDatabase;

    private function generateQrCode($orderNo)
    {
        $type = 'order';
        $hash = hash('sha256', $orderNo . env('APP_KEY'));
        return base64_encode("{$type}|{$orderNo}|{$hash}");
    }

    public function test_it_successfully_returns_a_dirty_instrument()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $instrument = Instrument::factory()->create();
        $order = Order::factory()->create();
        OrderItem::factory()->create([
            'order_id' => $order->id,
            'instrument_id' => $instrument->id,
        ]);
        $pouch = Pouch::factory()->create(['instrument_id' => $instrument->id]);

        $qrContent = $this->generateQrCode($order->order_no);

        $response = $this->postJson(route('scan.return.post'), [
            'pouch_code' => $pouch->pouch_code,
            'patient_name' => 'John Doe',
            'qty' => 1,
            'qr_content' => $qrContent,
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Pengembalian instrumen berhasil dicatat.']);

        $this->assertDatabaseHas('pouches', [
            'id' => $pouch->id,
            'status' => 'dirty',
        ]);
    }

    public function test_it_fails_to_return_an_instrument_not_in_the_order()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $instrumentInOrder = Instrument::factory()->create();
        $instrumentNotInOrder = Instrument::factory()->create();

        $order = Order::factory()->create();
        OrderItem::factory()->create([
            'order_id' => $order->id,
            'instrument_id' => $instrumentInOrder->id,
        ]);

        // This pouch is for an instrument that is NOT part of the order
        $pouch = Pouch::factory()->create(['instrument_id' => $instrumentNotInOrder->id]);

        $qrContent = $this->generateQrCode($order->order_no);

        $response = $this->postJson(route('scan.return.post'), [
            'pouch_code' => $pouch->pouch_code,
            'patient_name' => 'Jane Doe',
            'qty' => 1,
            'qr_content' => $qrContent,
        ]);

        $response->assertStatus(422)
            ->assertJson(['error' => 'Instrumen ini tidak termasuk dalam order yang dipindai.']);
    }
}
