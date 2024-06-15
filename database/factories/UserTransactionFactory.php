<?php

namespace Database\Factories;

use App\Models\Admin;
use App\Models\Reservatoin;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserTransaction>
 */
class UserTransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'wallet' => $this->faker->numberBetween(100, 5000),
            'amount' => $this->faker->numberBetween(100, 5000),
            'date' => $this->faker->date(),
            'type' => $this->faker->randomElement(['add' , 'dis']),
            'admin_id' => Admin::inRandomOrder()->first()->id,
            'reservation_id' => Reservatoin::inRandomOrder()->first()->id,
            'user_id' => User::inRandomOrder()->first()->id,
        ];
    }
}
