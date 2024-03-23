<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PropertyAmenityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $amenities = [
            'Swimming Pool',
            'Fitness Center',
            'Spa',
            'Tennis Court',
            'Golf Course',
            'Playground',
            'Gated Community',
            'Clubhouse',
            'Walking Trails',
            'Security System',
            'Garage',
            'Balcony',
            'Fireplace',
            'Central Heating',
            'Air Conditioning',
            'Wi-Fi',
            'Cable TV',
            'Laundry Facilities',
            'Dishwasher',
            'Refrigerator',
            'Microwave',
            'Oven',
            'Washer/Dryer',
            'Pet-Friendly',
            'Wheelchair Accessible',
            'Elevator',
            'Storage Space',
            'On-site Maintenance',
            'BBQ Area',
            'Outdoor Lounge',
            'Roof Deck',
            'Smart Home Technology',
            'Alarm System',
            'Hardwood Floors',
            'Ceiling Fans',
            'Walk-in Closets',
            'Patio',
            'Furnished',
            'Carport',
            'Shuttle Service',
            'Yard',
            'Waterfront Views',
            'Mountain Views',
            'City Views',
            'Fitness Classes',
            'Business Center',
            'Game Room',
            'Conference Room',
            'Library',
            'Package Receiving',
            'Guest Parking',
            'Concierge Service',
        ];

        return [
            'name' => $amenities[array_rand($amenities)],
        ];
    }
}
