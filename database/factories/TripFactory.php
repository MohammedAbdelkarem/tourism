<?php

namespace Database\Factories;

use App\Models\Guides_backups;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Generator as Faker;
use App\Models\Country;
use App\Models\Trip;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Trip>
 */
class TripFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Trip::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'bio' => $this->faker->paragraph,
            'photo' => $this->faker->imageUrl,
            'rate' => $this->faker->numberBetween(0, 5),
            'price_per_one_old' => $this->faker->randomNumber(2),
            'price_per_one_new' => $this->faker->randomNumber(2),
            'total_price' => $this->faker->randomNumber(4),
            'status' => $this->faker->randomElement(['pending', 'active', 'finished']),
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->date(),
            'number_of_filled_places' => $this->faker->randomNumber(2),
            'number_of_available_places' => $this->faker->randomNumber(2),
            'number_of_original_places' => $this->faker->randomNumber(2),
            'offer_ratio' => $this->faker->randomNumber(2),
            'lat' => $this->faker->latitude,
            'long' => $this->faker->longitude,
            'guide_id' => Guides_backups::inRandomOrder()->first()->id,
            'country_id' => Country::inRandomOrder()->first()->id,
        ];
    }
}
