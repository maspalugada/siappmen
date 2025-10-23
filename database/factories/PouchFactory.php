<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pouch>
 */
class PouchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pouch_code' => 'POUCH-' . $this->faker->unique()->uuid(),
            'instrument_id' => \App\Models\Instrument::factory(),
            'status' => $this->faker->randomElement(['clean', 'dirty', 'in_use', 'lost']),
        ];
    }
}
