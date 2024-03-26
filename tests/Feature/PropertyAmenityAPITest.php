<?php

namespace Tests\Feature;

use App\Models\PropertyAmenity;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PropertyAmenityAPITest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
    }

    public function test_property_amenity_api_call_read_expect_collection()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $propertyAmenities = PropertyAmenity::factory()->count(5)->create();

        $api = $this->json('GET', '/api/v1/property-amenities');

        $api->assertSuccessful();

        foreach ($propertyAmenities as $propertyAmenity) {
            $this->assertDatabaseHas('property_amenities', $propertyAmenity->toArray());
        }
    }

    public function test_property_amenity_api_call_create_expect_success()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $propertyAmenity = PropertyAmenity::factory()->make()->toArray();

        $api = $this->json('POST', '/api/v1/property-amenity', $propertyAmenity);

        $api->assertSuccessful();

        $this->assertDatabaseHas('property_amenities', $propertyAmenity);
    }

    public function test_property_amenity_api_call_read_property_amenity_by_id_expect_success()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $propertyAmenity = PropertyAmenity::factory()->create();

        $api = $this->json('GET', '/api/v1/property-amenity/'.$propertyAmenity->id);

        $api->assertSuccessful();

        $this->assertDatabaseHas('property_amenities', $propertyAmenity->toArray());
    }

    public function test_property_amenity_api_call_update_with_id_expect_success()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $propertyAmenity = PropertyAmenity::factory()->create();

        $updatedAmenity = PropertyAmenity::factory()->make()->toArray();

        $api = $this->json('POST', '/api/v1/property-amenity/'.$propertyAmenity->id, $updatedAmenity);

        $api->assertSuccessful();

        $this->assertDatabaseHas('property_amenities', $updatedAmenity);
    }

    public function test_property_amenity_api_call_delete_with_id_expect_success()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $propertyAmenity = PropertyAmenity::factory()->create();

        $api = $this->json('DELETE', '/api/v1/property-amenity/'.$propertyAmenity->id);

        $api->assertSuccessful();

        $this->assertSoftDeleted('property_amenities', $propertyAmenity->toArray());
    }
}
