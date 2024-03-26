<?php

namespace Tests\Feature;

use App\Models\Agent;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AgentAPITest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
    }

    public function test_agent_api_call_create_with_auto_code_and_empty_slug_expect_success()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $agent = Agent::factory()->make([
            'code' => 'AUTO',
            'slug' => '',
        ])->toArray();

        $api = $this->json('POST', '/api/v1/agent', $agent);

        $api->assertSuccessful();

        $agent['code'] = $api['data']['code'];
        $agent['slug'] = $api['data']['slug'];
        $agent['avatar'] = $api['data']['avatar'];

        $this->assertDatabaseHas('agents', $agent);

        $this->assertTrue(Storage::disk('public')->exists($api['data']['avatar']));
    }

    public function test_agent_api_call_read_expect_collection()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $agents = Agent::factory()->count(5)->create();

        $api = $this->json('GET', '/api/v1/agents');

        $api->assertSuccessful();

        foreach ($agents as $agent) {
            $this->assertDatabaseHas('agents', $agent->toArray());
        }
    }

    public function test_agent_api_call_get_agent_with_slug_expect_success()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $agent = Agent::factory()->create();

        $api = $this->json('GET', '/api/v1/agent/slug/'.$agent->slug);

        $api->assertSuccessful();

        $this->assertDatabaseHas('agents', $agent->toArray());
    }

    public function test_agent_api_call_update_with_auto_code_and_empty_slug_expect_success()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $agent = Agent::factory()->create();

        $agentUpdate = Agent::factory()->make([
            'code' => 'AUTO',
            'slug' => '',
        ])->toArray();

        $api = $this->json('POST', '/api/v1/agent/'.$agent->id, $agentUpdate);

        $api->assertSuccessful();

        $productUpdate['code'] = $api['data']['code'];
        $productUpdate['slug'] = $api['data']['slug'];
        $productUpdate['avatar'] = $api['data']['avatar'];

        $this->assertDatabaseHas('agents', $productUpdate);

        $this->assertTrue(Storage::disk('public')->exists($api['data']['avatar']));
    }

    public function test_agent_api_call_delete_expect_success()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $agent = Agent::factory()->create();

        $api = $this->json('DELETE', '/api/v1/agent/'.$agent->id);

        $api->assertSuccessful();

        $this->assertSoftDeleted('agents', $agent->toArray());
    }
}
