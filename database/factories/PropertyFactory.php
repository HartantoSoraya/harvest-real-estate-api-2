<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PropertyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'description' => fake()->sentence(),
            'loc_city' => fake()->city(),
            'loc_latitude' => strval(fake()->latitude()),
            'loc_longitude' => strval(fake()->longitude()),
            'loc_address' => fake()->address(),
            'loc_state' => fake()->state(),
            'loc_zip' => fake()->postcode(),
            'loc_country' => fake()->country(),
            'price' => fake()->numberBetween(1, 100000000),
            'is_featured' => fake()->boolean(),
            'is_active' => fake()->boolean(),
            'is_sold' => fake()->boolean(),
            'is_rented' => fake()->boolean(),
            'offer_type' => fake()->randomElement(['sale', 'rent']),
            'slug' => fake()->slug(),
        ];
    }
}
