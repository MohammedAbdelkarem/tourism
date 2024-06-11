<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\Facility;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Facility>
 */
class FacilityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

     protected $model = Facility::class;
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'photo' => $this->faker->imageUrl,
            'type' => $this->faker->randomElement(['restaurant', 'hotel', 'place']),
            'lat' => $this->faker->latitude,
            'long' => $this->faker->longitude,
            'bio' => $this->faker->optional()->paragraph,
            'number_of_places_available' => $this->faker->numberBetween(1, 100),
            'price_per_person' => $this->faker->numberBetween(10, 100),
            'profits' => $this->faker->numberBetween(0, 1000),
            'rate' => $this->faker->randomFloat(2, 0, 5),
            'country_id' => Country::inRandomOrder()->first()->id,
        ];
    }
}
