<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePropertyCategoryRequest;
use App\Http\Requests\UpdatePropertyCategoryRequest;
use App\Http\Resources\PropertyCategoryResource;
use App\Interfaces\PropertyCategoryRepositoryInterface;
use Illuminate\Http\Request;

class PropertyCategoryController extends Controller
{
    protected $propertyCategoryRepository;

    public function __construct(PropertyCategoryRepositoryInterface $propertyCategoryRepository)
    {
        $this->propertyCategoryRepository = $propertyCategoryRepository;
    }

    public function index(Request $request)
    {
        try {
            $propertyCategories = $this->propertyCategoryRepository->getAllPropertyCategories();

            return ResponseHelper::jsonResponse(true, 'Property Categories retrieved successfully', PropertyCategoryResource::collection($propertyCategories), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function store(StorePropertyCategoryRequest $request)
    {
        $request = $request->validated();

        $slug = $request['slug'];
        if (! $slug) {
            $tryCount = 1;
            do {
                $slug = $this->propertyCategoryRepository->generateSlug($request['name'], $tryCount);
                $tryCount++;
            } while (! $this->propertyCategoryRepository->isUniqueSlug($slug));
            $request['slug'] = $slug;
        }

        try {
            $propertyCategory = $this->propertyCategoryRepository->createPropertyCategory($request);

            return ResponseHelper::jsonResponse(true, 'Property Category created successfully', new PropertyCategoryResource($propertyCategory), 201);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function show($id)
    {
        try {
            $propertyCategory = $this->propertyCategoryRepository->getPropertyCategoryById($id);

            return ResponseHelper::jsonResponse(true, 'Property Category retrieved successfully', new PropertyCategoryResource($propertyCategory), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function update(UpdatePropertyCategoryRequest $request, $id)
    {
        $request = $request->validated();

        $slug = $request['slug'];
        if (! $slug) {
            $tryCount = 1;
            do {
                $slug = $this->propertyCategoryRepository->generateSlug($request['name'], $tryCount);
                $tryCount++;
            } while (! $this->propertyCategoryRepository->isUniqueSlug($slug, $id));
            $request['slug'] = $slug;
        }

        try {
            $propertyCategory = $this->propertyCategoryRepository->updatePropertyCategory($request, $id);

            return ResponseHelper::jsonResponse(true, 'Property Category updated successfully', new PropertyCategoryResource($propertyCategory), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->propertyCategoryRepository->deletePropertyCategory($id);

            return ResponseHelper::jsonResponse(true, 'Property Category deleted successfully', null, 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }
}
