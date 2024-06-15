<?php

namespace Database\Factories;

use App\Models\Guide;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GuideTransaction>
 */
class GuideTransactionFactory extends Factory
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
            'guide_id' => Guide::inRandomOrder()->first()->id,
        ];
    }
}
