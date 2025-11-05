<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\ScanQr;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Instrument;
use Livewire\Livewire;
use Tests\TestCase;

class ScanQrTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_scan_qr_code()
    {
        $user = User::factory()->create();
        $instrument = Instrument::factory()->create();
        $this->actingAs($user);

        Livewire::test(ScanQr::class)
            ->set('qrCode', $instrument->qr_code)
            ->assertSet('result.name', $instrument->name);
    }
}
