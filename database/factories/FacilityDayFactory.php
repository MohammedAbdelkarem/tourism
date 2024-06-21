<?php

namespace Database\Factories;

use App\Models\Day;
use App\Models\Facility;
use App\Models\Trip;
use App\Models\FacilityDay;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FacilityDay>
 */
class FacilityDayFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = FacilityDay::class;
    public function definition(): array
    {
        return [
            'date' => $this->faker->date(),
            'day_id' => Day::inRandomOrder()->first()->id,
            'trip_id' => Trip::inRandomOrder()->first()->id,
        ];
    }
}
