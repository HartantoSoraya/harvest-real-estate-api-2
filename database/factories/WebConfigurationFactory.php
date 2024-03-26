<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;

class WebConfigurationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $web_conf_name = [
            'Default',
            'Custom 1',
            'Custom 2',
            'Custom 3',
            'Custom 4',
        ];

        $facebook_usernames = [
            'user1_facebook',
            'user2_facebook',
            'user3_facebook',
            'user4_facebook',
            'user5_facebook',
            'user6_facebook',
            'user7_facebook',
            'user8_facebook',
            'user9_facebook',
            'user10_facebook',
        ];

        $instagram_usernames = [
            'user1_instagram',
            'user2_instagram',
            'user3_instagram',
            'user4_instagram',
            'user5_instagram',
            'user6_instagram',
            'user7_instagram',
            'user8_instagram',
            'user9_instagram',
            'user10_instagram',
        ];

        $youtube_usernames = [
            'user1_youtube',
            'user2_youtube',
            'user3_youtube',
            'user4_youtube',
            'user5_youtube',
            'user6_youtube',
            'user7_youtube',
            'user8_youtube',
            'user9_youtube',
            'user10_youtube',
        ];

        return [
            'title' => $web_conf_name[array_rand($web_conf_name)],
            'description' => fake()->sentence(),
            'email' => fake()->email(),
            'phone' => fake()->phoneNumber(),
            'logo' => UploadedFile::fake()->image('logo.jpg'),
            'map' => fake()->url(),
            'address' => fake()->address(),
            'facebook' => $facebook_usernames[array_rand($facebook_usernames)],
            'instagram' => $instagram_usernames[array_rand($instagram_usernames)],
            'youtube' => $youtube_usernames[array_rand($youtube_usernames)],
        ];
    }
}
