<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTestimonialRequest;
use App\Http\Requests\UpdateTestimonialRequest;
use App\Http\Resources\TestimonialResource;
use App\Interfaces\TestimonialRepositoryInterface;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    protected $testimonialRepository;

    public function __construct(TestimonialRepositoryInterface $testimonialRepository)
    {
        $this->testimonialRepository = $testimonialRepository;
    }

    public function index(Request $request)
    {
        try {
            $testimonials = $this->testimonialRepository->getAllTestimonials();

            return ResponseHelper::jsonResponse(true, 'Testimonials retrieved successfully', TestimonialResource::collection($testimonials), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), [], 500);
        }
    }

    public function store(StoreTestimonialRequest $request)
    {
        try {
            $testimonial = $this->testimonialRepository->createTestimonial($request->all());

            return ResponseHelper::jsonResponse(true, 'Testimonial created successfully', new TestimonialResource($testimonial), 201);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), [], 500);
        }
    }

    public function show($id)
    {
        try {
            $testimonial = $this->testimonialRepository->getTestimonialById($id);

            return ResponseHelper::jsonResponse(true, 'Testimonial retrieved successfully', new TestimonialResource($testimonial), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), [], 500);
        }
    }

    public function update(UpdateTestimonialRequest $request, $id)
    {
        try {
            $testimonial = $this->testimonialRepository->updateTestimonial($request->all(), $id);

            return ResponseHelper::jsonResponse(true, 'Testimonial updated successfully', new TestimonialResource($testimonial), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), [], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->testimonialRepository->deleteTestimonial($id);

            return ResponseHelper::jsonResponse(true, 'Testimonial deleted successfully', [], 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), [], 500);
        }
    }
}
