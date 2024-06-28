<?php

namespace Database\Factories;

use App\Models\Facility;
use App\Models\FacilityDay;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FacilityInDay>
 */
class FacilityInDayFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'facility_id' => Facility::inRandomOrder()->first()->id,
            'facility_day_id' => FacilityDay::inRandomOrder()->first()->id,
            'start_time' => $this->faker->time(),
            'end_time' => $this->faker->time(),
            'note' => $this->faker->text(rand(20, 50)),
        ];
    }
}
