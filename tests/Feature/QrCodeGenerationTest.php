<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeGenerationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function qr_code_content_is_generated_with_correct_secure_format()
    {
        $user = User::factory()->create(['role' => 'admin']);
        $this->actingAs($user);

        $order = Order::factory()->create();

        // Data yang diharapkan ada di dalam QR code
        $type = 'order';
        $hash = hash('sha256', $order->order_no . env('APP_KEY'));
        $expectedQrContent = base64_encode("{$type}|{$order->order_no}|{$hash}");

        // Menggunakan Mocking untuk memata-matai input ke QrCode::generate()
        QrCode::shouldReceive('format->size->generate')
            ->once()
            ->with($expectedQrContent)
            ->andReturn('<svg></svg>'); // Mengembalikan SVG kosong

        // Panggil endpoint yang men-trigger pembuatan QR code
        $this->get(route('transaksi.cssd.qr', ['orderNo' => $order->order_no]));
    }
}
