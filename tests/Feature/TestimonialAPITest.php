<?php

namespace Tests\Feature;

use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TestimonialAPITest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
    }

    public function test_testimonial_api_call_create_expect_success()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $testimonial = Testimonial::factory()->make()->toArray();

        $api = $this->json('POST', '/api/v1/testimonial', $testimonial);

        $api->assertSuccessful();

        $testimonial['avatar'] = $api['data']['avatar'];

        $this->assertDatabaseHas('testimonials', $testimonial);

        $this->assertTrue(Storage::disk('public')->exists($api['data']['avatar']));
    }

    public function test_testimonial_api_call_read_expect_collection()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $testimonials = Testimonial::factory()->count(5)->create();

        $api = $this->json('GET', '/api/v1/testimonials');

        $api->assertSuccessful();

        foreach ($testimonials as $testimonial) {
            $this->assertDatabaseHas('testimonials', $testimonial->toArray());
        }
    }

    public function test_testimonial_api_call_get_testimonial_by_id_expect_success()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $testimonial = Testimonial::factory()->create();

        $api = $this->json('GET', '/api/v1/testimonial/'.$testimonial->id);

        $api->assertSuccessful();

        $this->assertDatabaseHas('testimonials', $testimonial->toArray());
    }

    public function test_testimonial_api_call_update_expect_success()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $testimonial = Testimonial::factory()->create();

        $updatedTestimonial = Testimonial::factory()->make()->toArray();

        $api = $this->json('POST', '/api/v1/testimonial/'.$testimonial->id, $updatedTestimonial);

        $api->assertSuccessful();

        $updatedTestimonial['avatar'] = $api['data']['avatar'];

        $this->assertDatabaseHas('testimonials', $updatedTestimonial);
    }

    public function test_testimonial_api_call_delete_expect_success()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $testimonial = Testimonial::factory()->create();

        $api = $this->json('DELETE', '/api/v1/testimonial/'.$testimonial->id);

        $api->assertSuccessful();

        $this->assertSoftDeleted('testimonials', $testimonial->toArray());
    }
}
