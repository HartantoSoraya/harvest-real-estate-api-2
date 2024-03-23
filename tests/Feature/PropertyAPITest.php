<?php

namespace Tests\Feature;

use App\Models\Agent;
use App\Models\FloorPlan;
use App\Models\Property;
use App\Models\PropertyAmenity;
use App\Models\PropertyCategory;
use App\Models\PropertyImage;
use App\Models\PropertyType;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PropertyAPITest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
    }

    public function test_property_api_call_store_expect_successful()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        PropertyAmenity::factory()->count(5)->create();
        PropertyCategory::factory()->count(5)->create();
        PropertyType::factory()->count(5)->create();

        $property = Property::factory()
            ->for(Agent::factory()->create())
            ->make()
            ->toArray();

        $property['amenities'] = PropertyAmenity::inRandomOrder()->take(mt_rand(1, 3))->pluck('id')->toArray();
        $property['categories'] = PropertyCategory::inRandomOrder()->take(mt_rand(1, 3))->pluck('id')->toArray();
        $property['types'] = PropertyType::inRandomOrder()->take(mt_rand(1, 3))->pluck('id')->toArray();

        $propertyImages = [];
        for ($i = 0; $i < mt_rand(0, 5); $i++) {
            $propertyImages[] = PropertyImage::factory()->make()->image;
        }
        $property['images'] = $propertyImages;

        $floorPlanCount = mt_rand(1, 3);
        $floorPlans = FloorPlan::factory()->count($floorPlanCount)->make()->toArray();
        $property['floor_plans'] = $floorPlans;

        $api = $this->json('POST', '/api/v1/property', $property);

        $api->assertSuccessful();

        $this->assertDatabaseHas('properties', Arr::except($property, [
            'amenities',
            'categories',
            'types',
            'images',
            'floor_plans',
        ]));
    }

    public function test_property_api_call_index_expect_successful()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        PropertyAmenity::factory()->count(5)->create();
        PropertyCategory::factory()->count(5)->create();
        PropertyType::factory()->count(5)->create();

        $properties = Property::factory()
            ->for(Agent::factory()->create())
            ->hasAttached(PropertyAmenity::inRandomOrder()->first(), [], 'amenities')
            ->hasAttached(PropertyCategory::inRandomOrder()->first(), [], 'categories')
            ->hasAttached(PropertyType::inRandomOrder()->first(), [], 'types')
            ->has(PropertyImage::factory()->count(mt_rand(1, 3)), 'images')
            ->has(FloorPlan::factory()->count(mt_rand(1, 3)), 'floorPlans')
            ->count(5)
            ->create();

        $api = $this->json('GET', '/api/v1/properties');

        $api->assertSuccessful();

        foreach ($properties as $property) {
            $this->assertDatabaseHas('properties', $property->toArray());
        }
    }

    public function test_property_api_call_show_expect_successful()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        PropertyAmenity::factory()->count(5)->create();
        PropertyCategory::factory()->count(5)->create();
        PropertyType::factory()->count(5)->create();

        $property = Property::factory()
            ->for(Agent::factory()->create())
            ->create();

        $api = $this->json('GET', '/api/v1/property/'.$property->id);

        $api->assertSuccessful();

        $api->assertJsonFragment($property->toArray());
    }

    public function test_property_api_call_get_property_by_slug_expect_successful()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        PropertyAmenity::factory()->count(5)->create();
        PropertyCategory::factory()->count(5)->create();
        PropertyType::factory()->count(5)->create();

        $property = Property::factory()
            ->for(Agent::factory()->create())
            ->create();

        $api = $this->json('GET', '/api/v1/property/slug/'.$property->slug);

        $api->assertSuccessful();

        $api->assertJsonFragment($property->toArray());
    }

    public function test_property_api_call_get_property_cities_expect_successful()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        PropertyAmenity::factory()->count(5)->create();
        PropertyCategory::factory()->count(5)->create();
        PropertyType::factory()->count(5)->create();

        $properties = Property::factory()
            ->for(Agent::factory()->create())
            ->count(5)
            ->create();

        $api = $this->json('GET', '/api/v1/property/read/cities');

        $api->assertSuccessful();

        foreach ($properties as $property) {
            $this->assertDatabaseHas('properties', $property->toArray());
        }
    }

    public function test_property_api_call_get_properties_by_params_expect_successful()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        PropertyAmenity::factory()->count(5)->create();
        PropertyCategory::factory()->count(5)->create();
        PropertyType::factory()->count(5)->create();

        $properties = Property::factory()
            ->for(Agent::factory()->create())
            ->count(5)
            ->create();

        $api = $this->json('GET', '/api/v1/properties/search');

        $api->assertSuccessful();

        foreach ($properties as $property) {
            $this->assertDatabaseHas('properties', $property->toArray());
        }
    }

    public function test_property_api_call_update_expect_successful()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        PropertyAmenity::factory()->count(5)->create();
        PropertyCategory::factory()->count(5)->create();
        PropertyType::factory()->count(5)->create();

        $property = Property::factory()
            ->for(Agent::factory()->create())
            ->create();

        $propertyUpdate = Property::factory()
            ->for(Agent::factory()->create())
            ->make()
            ->toArray();

        $propertyUpdate['amenities'] = PropertyAmenity::inRandomOrder()->take(mt_rand(1, 3))->pluck('id')->toArray();
        $propertyUpdate['categories'] = PropertyCategory::inRandomOrder()->take(mt_rand(1, 3))->pluck('id')->toArray();
        $propertyUpdate['types'] = PropertyType::inRandomOrder()->take(mt_rand(1, 3))->pluck('id')->toArray();

        $images = [];
        for ($i = 0; $i < mt_rand(0, 5); $i++) {
            $images[] = PropertyImage::factory()->make()->image;
        }
        $propertyUpdate['images'] = $images;

        $deletedImagesIds = $property->images()
            ->inRandomOrder()
            ->take(mt_rand(0, $property->images()->count()))
            ->pluck('id')->toArray();
        $propertyUpdate['deleted_images_ids'] = $deletedImagesIds;

        $floorPlanCount = mt_rand(1, 3);
        $floorPlans = FloorPlan::factory()->count($floorPlanCount)->make()->toArray();
        $propertyUpdate['floor_plans'] = $floorPlans;

        $deletedFloorPlansIds = $property->floorPlans()
            ->inRandomOrder()
            ->take(mt_rand(0, $property->floorPlans()->count()))
            ->pluck('id')->toArray();
        $propertyUpdate['deleted_floor_plans_ids'] = $deletedFloorPlansIds;

        $api = $this->json('POST', '/api/v1/property/'.$property->id, $propertyUpdate);

        $api->assertSuccessful();

        $this->assertDatabaseHas('properties', Arr::except($propertyUpdate, [
            'amenities',
            'categories',
            'types',
            'images',
            'deleted_images_ids',
            'floor_plans',
            'deleted_floor_plans_ids',
        ]));

        foreach ($propertyUpdate['amenities'] as $propertyAmenity) {
            $this->assertDatabaseHas('property_amenity_pivot', [
                'property_id' => $property['id'],
                'property_amenity_id' => $propertyAmenity,
            ]);
        }

        foreach ($propertyUpdate['categories'] as $propertyCategory) {
            $this->assertDatabaseHas('property_category_pivot', [
                'property_id' => $property['id'],
                'property_category_id' => $propertyCategory,
            ]);
        }

        foreach ($propertyUpdate['types'] as $propertyType) {
            $this->assertDatabaseHas('property_type_pivot', [
                'property_id' => $property['id'],
                'property_type_id' => $propertyType,
            ]);
        }

        $propertyUpdate['images'] = $api['data']['images'];
        foreach ($propertyUpdate['images'] as $propertyImage) {
            $this->assertDatabaseHas('property_images', Arr::except($propertyImage, ['image_url']));

            $this->assertTrue(Storage::disk('public')->exists($propertyImage['image']));
        }

        $propertyUpdate['floor_plans'] = $api['data']['floor_plans'];
        foreach ($propertyUpdate['floor_plans'] as $floorPlan) {
            $this->assertDatabaseHas('floor_plans', Arr::except($floorPlan, ['image_url']));

            $this->assertTrue(Storage::disk('public')->exists($floorPlan['image']));
        }
    }

    public function test_property_api_call_update_featured_property_expect_successful()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        PropertyAmenity::factory()->count(5)->create();
        PropertyCategory::factory()->count(5)->create();
        PropertyType::factory()->count(5)->create();

        $property = Property::factory()
            ->for(Agent::factory()->create())
            ->create();

        $api = $this->json('POST', '/api/v1/property/featured/'.$property->id, ['is_featured' => true]);

        $api->assertSuccessful();

        $this->assertDatabaseHas('properties', array_merge($property->toArray(), ['is_featured' => true]));
    }

    public function test_property_api_call_update_unfeatured_property_expect_successful()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        PropertyAmenity::factory()->count(5)->create();
        PropertyCategory::factory()->count(5)->create();
        PropertyType::factory()->count(5)->create();

        $property = Property::factory()
            ->for(Agent::factory()->create())
            ->create();

        $api = $this->json('POST', '/api/v1/property/featured/'.$property->id, ['is_featured' => false]);

        $api->assertSuccessful();

        $this->assertDatabaseHas('properties', array_merge($property->toArray(), ['is_featured' => false]));
    }

    public function test_property_api_call_update_active_property_expect_successful()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        PropertyAmenity::factory()->count(5)->create();
        PropertyCategory::factory()->count(5)->create();
        PropertyType::factory()->count(5)->create();

        $property = Property::factory()
            ->for(Agent::factory()->create())
            ->create();

        $api = $this->json('POST', '/api/v1/property/active/'.$property->id, ['is_active' => true]);

        $api->assertSuccessful();

        $this->assertDatabaseHas('properties', array_merge($property->toArray(), ['is_active' => 1]));
    }

    public function test_property_api_call_update_inactive_property_expect_successful()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        PropertyAmenity::factory()->count(5)->create();
        PropertyCategory::factory()->count(5)->create();
        PropertyType::factory()->count(5)->create();

        $property = Property::factory()
            ->for(Agent::factory()->create())
            ->create();

        $api = $this->json('POST', '/api/v1/property/active/'.$property->id, ['is_active' => false]);

        $api->assertSuccessful();

        $this->assertDatabaseHas('properties', array_merge($property->toArray(), ['is_active' => false]));
    }

    public function test_property_api_call_update_sold_property_expect_successful()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        PropertyAmenity::factory()->count(5)->create();
        PropertyCategory::factory()->count(5)->create();
        PropertyType::factory()->count(5)->create();

        $property = Property::factory()
            ->for(Agent::factory()->create())
            ->create();

        $api = $this->json('POST', '/api/v1/property/sold/'.$property->id, ['is_sold' => true]);

        $api->assertSuccessful();

        $this->assertDatabaseHas('properties', array_merge($property->toArray(), ['is_sold' => true]));
    }

    public function test_property_api_call_update_unsold_property_expect_successful()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        PropertyAmenity::factory()->count(5)->create();
        PropertyCategory::factory()->count(5)->create();
        PropertyType::factory()->count(5)->create();

        $property = Property::factory()
            ->for(Agent::factory()->create())
            ->create();

        $api = $this->json('POST', '/api/v1/property/sold/'.$property->id, ['is_sold' => false]);

        $api->assertSuccessful();

        $this->assertDatabaseHas('properties', array_merge($property->toArray(), ['is_sold' => false]));
    }

    public function test_property_api_call_update_rented_property_expect_successful()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        PropertyAmenity::factory()->count(5)->create();
        PropertyCategory::factory()->count(5)->create();
        PropertyType::factory()->count(5)->create();

        $property = Property::factory()
            ->for(Agent::factory()->create())
            ->create();

        $api = $this->json('POST', '/api/v1/property/rented/'.$property->id, ['is_rented' => true]);

        $api->assertSuccessful();

        $this->assertDatabaseHas('properties', array_merge($property->toArray(), ['is_rented' => true]));
    }

    public function test_property_api_call_update_unrented_property_expect_successful()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        PropertyAmenity::factory()->count(5)->create();
        PropertyCategory::factory()->count(5)->create();
        PropertyType::factory()->count(5)->create();

        $property = Property::factory()
            ->for(Agent::factory()->create())
            ->create();

        $api = $this->json('POST', '/api/v1/property/rented/'.$property->id, ['is_rented' => false]);

        $api->assertSuccessful();

        $this->assertDatabaseHas('properties', array_merge($property->toArray(), ['is_rented' => false]));
    }

    public function test_property_api_call_delete_expect_successful()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        PropertyAmenity::factory()->count(5)->create();
        PropertyCategory::factory()->count(5)->create();
        PropertyType::factory()->count(5)->create();

        $property = Property::factory()
            ->for(Agent::factory()->create())
            ->create();

        $api = $this->json('DELETE', '/api/v1/property/'.$property->id);

        $api->assertSuccessful();

        $this->assertSoftDeleted('properties', $property->toArray());
    }
}
