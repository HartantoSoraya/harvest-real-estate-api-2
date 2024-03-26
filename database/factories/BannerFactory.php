<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;

class BannerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'image' => UploadedFile::fake()->image('banner.jpg'),
            'title' => $this->faker->sentence,
            'description' => substr($this->faker->paragraph, 0, 254),
        ];
    }
}
