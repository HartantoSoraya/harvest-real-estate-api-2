<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PropertyCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $propertyCategories = [
            'Apartment',
            'House',
            'Land',
            'Commercial',
            'Garage',
        ];

        return [
            'name' => $propertyCategories[array_rand($propertyCategories)],
            'icon' => $this->faker->word,
            'slug' => $this->faker->slug,
        ];
    }

    public function setRandomName(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => $this->faker->sentence,
            ];
        });
    }
}
