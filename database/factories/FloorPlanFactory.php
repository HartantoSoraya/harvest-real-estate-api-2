<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;

class FloorPlanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sort' => $this->faker->randomNumber(),
            'title' => $this->faker->sentence(),
            'image' => UploadedFile::fake()->image('floor-plan.jpg'),
        ];
    }
}
