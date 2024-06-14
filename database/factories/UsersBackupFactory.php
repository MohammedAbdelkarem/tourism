<?php

namespace Database\Factories;

use App\Models\UsersBackup;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

class UsersBackupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = UsersBackup::class;
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->unique()->phoneNumber,
            'photo' => $this->faker->imageUrl(),
            'password' => Hash::make('password'),
            'wallet' => $this->faker->randomNumber(),
            'active' => $this->faker->randomElement(['active', 'inactive']),
        ];
    }
}
