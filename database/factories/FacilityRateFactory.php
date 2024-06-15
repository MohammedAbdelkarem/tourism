<?php

namespace Database\Factories;

use App\Models\Facility;
use App\Models\UsersBackup;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FacilityRate>
 */
class FacilityRateFactory extends Factory
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
            'user_backup_id' => UsersBackup::inRandomOrder()->first()->id,
            'rate' => $this->faker->numberBetween(1 , 5),
        ];
    }
}
