<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PropertyTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $property_types = [
            'For Rent',
            'For Sale',
            'Leased',
            'Sold',
            'Under Offer',
        ];

        return [
            'name' => $property_types[array_rand($property_types)],
            'slug' => $this->faker->slug,
        ];
    }

    public function setRandomName(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => $this->faker->sentence(),
            ];
        });
    }
}
