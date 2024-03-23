<?php

namespace App\Repositories;

use App\Interfaces\TestimonialRepositoryInterface;
use App\Models\Testimonial;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TestimonialRepository implements TestimonialRepositoryInterface
{
    public function getAllTestimonials()
    {
        return Testimonial::all();
    }

    public function getTestimonialById(string $id)
    {
        return Testimonial::findOrFail($id);
    }

    public function createTestimonial(array $data)
    {
        DB::beginTransaction();

        try {
            $testimonial = new Testimonial;
            $testimonial->name = $data['name'];
            $testimonial->avatar = $data['avatar']->store('assets/testimonials', 'public');
            $testimonial->testimonial = $data['testimonial'];
            $testimonial->save();

            DB::commit();

            return $testimonial;
        } catch (\Exception $e) {
            DB::rollBack();

            return $e->getMessage();
        }
    }

    public function updateTestimonial(array $data, string $id)
    {
        DB::beginTransaction();

        try {
            $testimonial = Testimonial::find($id);
            $testimonial->name = $data['name'];
            if (isset($data['avatar'])) {
                $testimonial->avatar = $testimonial->avatar = $this->updateAvatar($testimonial->avatar, $data['avatar']);
            }
            $testimonial->testimonial = $data['testimonial'];
            $testimonial->save();

            DB::commit();

            return $testimonial;
        } catch (\Exception $e) {
            DB::rollBack();

            return $e->getMessage();
        }
    }

    public function deleteTestimonial(string $id)
    {
        DB::beginTransaction();

        try {
            Testimonial::findOrFail($id)->delete();

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();

            return $e->getMessage();
        }
    }

    private function updateAvatar($oldAvatar, $newAvatar)
    {
        Storage::disk('public')->delete($oldAvatar);

        return $newAvatar->store('assets/testimonials', 'public');
    }
}
