<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Http\Livewire\MasterUnit;
use App\Http\Livewire\MasterInstrument;
use App\Models\User;
use App\Models\Unit;
use App\Models\Instrument;
use Livewire\Livewire;

class MasterDataManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function cssd_user_can_create_and_delete_a_unit()
    {
        $user = User::factory()->create(['role' => 'cssd']);
        $this->actingAs($user);

        // Test Create
        Livewire::test(MasterUnit::class)
            ->set('name', 'UGD')
            ->set('location', 'Lantai 1')
            ->call('save');

        $this->assertDatabaseHas('units', ['name' => 'UGD']);

        // Test Delete
        $unit = Unit::where('name', 'UGD')->first();
        Livewire::test(MasterUnit::class)
            ->call('delete', $unit->id);

        $this->assertDatabaseMissing('units', ['name' => 'UGD']);
    }

    /** @test */
    public function cssd_user_can_create_and_delete_an_instrument()
    {
        $user = User::factory()->create(['role' => 'cssd']);
        $this->actingAs($user);

        $unit = Unit::factory()->create();

        // Test Create
        Livewire::test(MasterInstrument::class)
            ->set('code', 'SC-01')
            ->set('name', 'Scalpel')
            ->set('unit_id', $unit->id)
            ->call('save');

        $this->assertDatabaseHas('instruments', ['code' => 'SC-01']);

        // Test Delete
        $instrument = Instrument::where('code', 'SC-01')->first();
        Livewire::test(MasterInstrument::class)
            ->call('delete', $instrument->id);

        $this->assertDatabaseMissing('instruments', ['code' => 'SC-01']);
    }

    /** @test */
    public function unauthorized_user_cannot_access_master_data_pages()
    {
        $user = User::factory()->create(['role' => 'unit']); // Unauthorized role
        $this->actingAs($user);

        $this->get(route('master.units'))->assertStatus(403);
        $this->get(route('master.instruments'))->assertStatus(403);
    }
}
