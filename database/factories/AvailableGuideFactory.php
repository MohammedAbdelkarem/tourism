<?php

namespace Database\Factories;

use App\Models\Guide;
use App\Models\Trip;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AvailableGuide>
 */
class AvailableGuideFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'trip_id' => Trip::inRandomOrder()->first()->id,
            'guide_id' => Guide::inRandomOrder()->first()->id,
            'accept_trip' => $this->faker->randomElement(['accepted', 'rejected']),
        ];
    }
}
