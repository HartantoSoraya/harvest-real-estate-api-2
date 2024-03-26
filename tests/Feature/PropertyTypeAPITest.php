<?php

namespace Tests\Feature;

use App\Models\PropertyType;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PropertyTypeAPITest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
    }

    public function test_property_type_api_call_create_with_empty_slug_expect_success()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $propertyType = PropertyType::factory()
            ->setRandomName()
            ->make(['slug' => ''])
            ->toArray();

        $api = $this->json('POST', '/api/v1/property-type', $propertyType);

        $api->assertSuccessful();

        $propertyType['slug'] = $api['data']['slug'];

        $this->assertDatabaseHas('property_types', $propertyType);
    }

    public function test_property_type_api_call_read_expect_collection()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $propertyTypes = PropertyType::factory()->count(5)->create();

        $api = $this->json('GET', '/api/v1/property-types');

        $api->assertSuccessful();

        foreach ($propertyTypes as $propertyType) {
            $this->assertDatabaseHas('property_types', $propertyType->toArray());
        }
    }

    public function test_property_type_api_call_update_with_empty_slug_expect_success()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $propertyType = PropertyType::factory()->setRandomName()->create();

        $updatedPropertyType = PropertyType::factory()
            ->setRandomName()
            ->make(['slug' => ''])
            ->toArray();

        $api = $this->json('POST', '/api/v1/property-type/'.$propertyType->id, $updatedPropertyType);

        $api->assertSuccessful();

        $updatedPropertyType['slug'] = $api['data']['slug'];

        $this->assertDatabaseHas('property_types', $updatedPropertyType);
    }

    public function test_property_type_api_call_delete_expect_success()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $propertyType = PropertyType::factory()->setRandomName()->create();

        $api = $this->json('DELETE', '/api/v1/property-type/'.$propertyType->id);

        $api->assertSuccessful();

        $this->assertSoftDeleted('property_types', $propertyType->toArray());
    }
}
