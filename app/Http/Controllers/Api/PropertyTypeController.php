<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePropertyTypeRequest;
use App\Http\Requests\UpdatePropertyTypeRequest;
use App\Http\Resources\PropertyTypeResource;
use App\Interfaces\PropertyTypeRepositoryInterface;
use Illuminate\Http\Request;

class PropertyTypeController extends Controller
{
    protected $propertyTypeRepository;

    public function __construct(PropertyTypeRepositoryInterface $propertyTypeRepository)
    {
        $this->propertyTypeRepository = $propertyTypeRepository;
    }

    public function index(Request $request)
    {
        try {
            $propertyTypes = $this->propertyTypeRepository->getAllPropertyTypes();

            return ResponseHelper::jsonResponse(true, 'Property Types retrieved successfully', PropertyTypeResource::collection($propertyTypes), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, 'Failed to retrieve Property Types', $e->getMessage(), 500);
        }
    }

    public function store(StorePropertyTypeRequest $request)
    {
        $request = $request->validated();

        $slug = $request['slug'];
        if (! $slug) {
            $tryCount = 1;
            do {
                $slug = $this->propertyTypeRepository->generateSlug($request['name'], $tryCount);
                $tryCount++;
            } while (! $this->propertyTypeRepository->isUniqueSlug($slug));
            $request['slug'] = $slug;
        }

        try {
            $propertyType = $this->propertyTypeRepository->createPropertyType($request);

            return ResponseHelper::jsonResponse(true, 'Property Type created successfully', new PropertyTypeResource($propertyType), 201);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, 'Failed to create Property Type', $e->getMessage(), 500);
        }
    }

    public function show($id)
    {
        try {
            $propertyType = $this->propertyTypeRepository->getPropertyTypeById($id);

            return ResponseHelper::jsonResponse(true, 'Property Type retrieved successfully', new PropertyTypeResource($propertyType), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, 'Failed to retrieve Property Type', $e->getMessage(), 500);

        }
    }

    public function update(UpdatePropertyTypeRequest $request, $id)
    {
        $request = $request->validated();

        $slug = $request['slug'];
        if (! $slug) {
            $tryCount = 1;
            do {
                $slug = $this->propertyTypeRepository->generateSlug($request['name'], $tryCount);
                $tryCount++;
            } while (! $this->propertyTypeRepository->isUniqueSlug($slug, $id));
            $request['slug'] = $slug;
        }

        try {
            $propertyType = $this->propertyTypeRepository->updatePropertyType($request, $id);

            return ResponseHelper::jsonResponse(true, 'Property Type updated successfully', new PropertyTypeResource($propertyType), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, 'Failed to update Property Type', $e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->propertyTypeRepository->deletePropertyType($id);

            return ResponseHelper::jsonResponse(true, 'Property Type deleted successfully', null, 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, 'Failed to delete Property Type', $e->getMessage(), 500);
        }
    }
}
