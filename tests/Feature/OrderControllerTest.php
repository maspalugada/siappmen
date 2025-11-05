<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Instrument;
use App\Models\Unit;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_a_new_order_and_redirects()
    {
        $unit = Unit::factory()->create();
        $user = User::factory()->create(['unit_id' => $unit->id, 'role' => 'unit']);
        $instrument = Instrument::factory()->create();

        $this->actingAs($user);

        $response = $this->post(route('orders.store'), [
            'date_return_planned' => now()->addDays(7)->toDateString(),
            'items' => [
                [
                    'instrument_id' => $instrument->id,
                    'qty' => 1,
                    'notes' => 'Test notes',
                ],
            ],
        ]);

        $response->assertRedirect(route('orders.index'))
            ->assertSessionHas('activity', 'Order baru berhasil dibuat!');
    }
}
