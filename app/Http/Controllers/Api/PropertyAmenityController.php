<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePropertyAmenityRequest;
use App\Http\Requests\UpdatePropertyAmenityRequest;
use App\Http\Resources\PropertyAmenityResource;
use App\Interfaces\PropertyAmenityRepositoryInterface;
use Illuminate\Http\Request;

class PropertyAmenityController extends Controller
{
    protected $propertyAmenityRepository;

    public function __construct(PropertyAmenityRepositoryInterface $propertyAmenityRepository)
    {
        $this->propertyAmenityRepository = $propertyAmenityRepository;
    }

    public function index(Request $request)
    {
        try {
            $propertyAmenities = $this->propertyAmenityRepository->getAllPropertyAmenities();

            return ResponseHelper::jsonResponse(true, 'Property Amenities retrieved successfully', PropertyAmenityResource::collection($propertyAmenities), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), [], 500);
        }
    }

    public function store(StorePropertyAmenityRequest $request)
    {
        $request = $request->validated();

        try {
            $propertyAmenity = $this->propertyAmenityRepository->createPropertyAmenity($request);

            return ResponseHelper::jsonResponse(true, 'Property Amenity created successfully', new PropertyAmenityResource($propertyAmenity), 201);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), [], 500);
        }
    }

    public function show($id)
    {
        try {
            $propertyAmenity = $this->propertyAmenityRepository->getPropertyAmenityById($id);

            if (! $propertyAmenity) {
                return ResponseHelper::jsonResponse(false, 'Property Amenity not found', [], 404);
            }

            return ResponseHelper::jsonResponse(true, 'Property Amenity retrieved successfully', new PropertyAmenityResource($propertyAmenity), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), [], 500);

        }
    }

    public function update(UpdatePropertyAmenityRequest $request, $id)
    {
        $request = $request->validated();

        try {
            $propertyAmenity = $this->propertyAmenityRepository->updatePropertyAmenity($request, $id);

            return ResponseHelper::jsonResponse(true, 'Property Amenity updated successfully', new PropertyAmenityResource($propertyAmenity), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), [], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->propertyAmenityRepository->deletePropertyAmenity($id);

            return ResponseHelper::jsonResponse(true, 'Property Amenity deleted successfully', [], 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), [], 500);
        }
    }
}
