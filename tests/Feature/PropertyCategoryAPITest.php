<?php

namespace Tests\Feature;

use App\Models\PropertyCategory;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PropertyCategoryAPITest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
    }

    public function test_property_category_api_call_create_with_empty_slug_expect_success()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $propertyCategory = PropertyCategory::factory()
            ->setRandomName()
            ->make(['slug' => ''])
            ->toArray();

        $api = $this->json('POST', '/api/v1/property-category', $propertyCategory);

        $api->assertSuccessful();

        $propertyCategory['slug'] = $api['data']['slug'];

        $this->assertDatabaseHas('property_categories', $propertyCategory);
    }

    public function test_property_category_api_call_read_expect_collection()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $propertyCategories = PropertyCategory::factory()->count(5)->create();

        $api = $this->json('GET', '/api/v1/property-categories');

        $api->assertSuccessful();

        foreach ($propertyCategories as $propertyCategory) {
            $this->assertDatabaseHas('property_categories', $propertyCategory->toArray());
        }
    }

    public function test_property_category_api_call_update_with_empty_slug_expect_success()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $propertyCategory = PropertyCategory::factory()->setRandomName()->create();

        $updatedPropertyCategory = PropertyCategory::factory()
            ->setRandomName()
            ->make(['slug' => ''])
            ->toArray();

        $api = $this->json('POST', '/api/v1/property-category/'.$propertyCategory->id, $updatedPropertyCategory);

        $api->assertSuccessful();

        $updatedPropertyCategory['slug'] = $api['data']['slug'];

        $this->assertDatabaseHas('property_categories', $updatedPropertyCategory);
    }

    public function test_property_category_api_call_delete_expect_success()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $propertyCategory = PropertyCategory::factory()->create();

        $api = $this->json('DELETE', '/api/v1/property-category/'.$propertyCategory->id);

        $api->assertSuccessful();

        $this->assertSoftDeleted('property_categories', $propertyCategory->toArray());
    }
}
