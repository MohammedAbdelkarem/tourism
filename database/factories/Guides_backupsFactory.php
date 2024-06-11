<?php

namespace Database\Factories;

use App\Models\Guides_backups;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;


class Guides_backupsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

     protected $model = Guides_backups::class;
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->unique()->phoneNumber,
            'password' => Hash::make('password'), // You can modify the default password if needed
            'status' => $this->faker->randomElement(['available', 'unavailable']),
            'price_per_person_one_day' => $this->faker->numberBetween(10, 100),
            'father_name' => $this->faker->firstNameMale,
            'mother_name' => $this->faker->firstNameFemale,
            'unique_id' => $this->faker->unique()->randomNumber(6),
            'birth_place' => $this->faker->city,
            'birth_date' => $this->faker->date(),
            'wallet' => $this->faker->numberBetween(1000, 10000),
            'photo' => $this->faker->optional()->imageUrl,
            'can_change_id' => $this->faker->optional()->randomElement(['able', 'unable']),
        ];
    }
}
