<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\Guides_backups;
use App\Models\Trip;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Trip>
 */
class TripFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Trip::class;
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'bio' => $this->faker->text(rand(10, 50)),
            'photo' => $this->faker->imageUrl(),
            'rate' => $this->faker->numberBetween(1, 5),
            'price_per_one_old' => $this->faker->randomNumber(2),
            'price_per_one_new' => $this->faker->randomNumber(2),
            'total_price' => $this->faker->randomNumber(4),
            'status' => $this->faker->randomElement(['pending' , 'active' ,'in_progress' , 'finished']),
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->date(),
            'number_of_filled_places' => $this->faker->randomNumber(),
            'number_of_available_places' => $this->faker->randomNumber(),
            'number_of_original_places' => $this->faker->numberBetween(10, 100),
            'offer_ratio' => $this->faker->randomNumber(2),
            'lat' => $this->faker->latitude,
            'long' => $this->faker->longitude,
            'guide_backup_id' => Guides_backups::inRandomOrder()->first()->id,
            'country_id' => Country::inRandomOrder()->first()->id,
        ];
    }
}
