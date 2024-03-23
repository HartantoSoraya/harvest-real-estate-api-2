<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\WebConfiguration;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class WebConfigurationAPITest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
    }

    public function test_web_configuration_api_call_read_expect_collection()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        WebConfiguration::truncate();
        $webConfiguration = WebConfiguration::factory()->create();

        $api = $this->json('GET', 'api/v1/web-configuration');

        $api->assertSuccessful();

        $this->assertDatabaseHas('web_configurations', $webConfiguration->toArray());
    }

    public function test_web_configuration_api_call_update_expect_successfull()
    {
        $password = '1234567890';
        $user = User::factory()->create(['password' => $password]);

        $this->actingAs($user);

        WebConfiguration::truncate();
        WebConfiguration::factory()->create();

        $updatedWebConfigurations = WebConfiguration::factory()->make()->toArray();

        $api = $this->json('POST', 'api/v1/web-configuration', $updatedWebConfigurations);

        $api->assertSuccessful();

        $updatedWebConfigurations['logo'] = $api['data']['logo'];

        $this->assertDatabaseHas(
            'web_configurations',
            $updatedWebConfigurations
        );

        $this->assertTrue(Storage::disk('public')->exists($updatedWebConfigurations['logo']));
    }
}
