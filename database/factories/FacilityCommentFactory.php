<?php

namespace Database\Factories;

use App\Models\Facility;
use App\Models\UsersBackup;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FacilityComment>
 */
class FacilityCommentFactory extends Factory
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
            'comment' => $this->faker->text(rand(10, 50)),
        ];
    }
}
