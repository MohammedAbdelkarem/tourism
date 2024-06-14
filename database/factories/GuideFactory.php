<?php

namespace Database\Factories;

use App\Models\Guide;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Guide>
 */
class GuideFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

     protected $model = Guide::class;
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
            'photo' => $this->faker->imageUrl,
            'can_change_unique_id' => $this->faker->randomElement(['able', 'unable']),
            'accept_by_admin' => $this->faker->randomElement(['accepted', 'rejected']),
        ];
    }
}
