<?php

namespace Database\Factories;

use App\Models\FacilityDay;
use App\Models\FacilityDayNotes;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FacilityDayNotes>
 */
class FacilityDayNotesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

     protected $model = FacilityDayNotes::class;
    public function definition(): array
    {
        return [
            'note' => $this->faker->text(rand(10, 50)),
            'facility_day_id' => FacilityDay::inRandomOrder()->first()->id,
        ];
    }
}
