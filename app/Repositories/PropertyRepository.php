<?php

namespace App\Repositories;

use App\Interfaces\PropertyRepositoryInterface;
use App\Models\FloorPlan;
use App\Models\Property;
use App\Models\PropertyImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PropertyRepository implements PropertyRepositoryInterface
{
    public function getAllProperties(?string $sortBy = null, ?string $sort = null)
    {
        $properties = Property::with(['amenities', 'categories', 'types', 'images', 'floorPlans']);

        if ($sortBy && $sort) {
            return $properties->orderBy($sortBy, $sort)->get();
        }

        return $properties->orderBy('is_featured', 'desc')->get();
    }

    public function getPropertiesByParams(?string $search = null, ?string $city = null, ?string $category = null, ?string $amenities = null, ?string $type = null, ?int $minPrice = null, ?int $maxPrice = null, ?bool $sold = null, ?bool $rented = null)
    {
        $properties = Property::query();

        $properties->where(function ($query) use ($search, $city, $category, $amenities, $type, $minPrice, $maxPrice, $sold, $rented) {

            if ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('title', 'like', '%'.$search.'%')
                        ->orWhere('loc_city', 'like', '%'.$search.'%')
                        ->orWhere('loc_state', 'like', '%'.$search.'%')
                        ->orWhere('loc_address', 'like', '%'.$search.'%')
                        ->orWhere('offer_type', 'like', '%'.$search.'%');
                });
            }

            if ($city) {
                $query->where('loc_city', 'like', '%'.$city.'%');
            }

            if ($category) {
                $query->whereHas('categories', function ($subQuery) use ($category) {
                    $subQuery->where('name', $category);
                });
            }

            if ($amenities) {
                $query->whereHas('amenities', function ($subQuery) use ($amenities) {
                    $subQuery->where('name', $amenities);
                });
            }

            if ($minPrice >= 0 && $maxPrice > 0) {
                $query->whereBetween('price', [$minPrice, $maxPrice]);
            }

            if ($sold) {
                $query->where('is_sold', $sold);
            }

            if ($rented) {
                $query->where('is_rented', $rented);
            }

            if ($type) {
                $query->where('offer_type', $type);
            }
        });

        return $properties->get();
    }

    public function getPropertyById($id)
    {
        return Property::find($id);
    }

    public function getPropertyBySlug($slug)
    {
        return Property::where('slug', $slug)->first();
    }

    public function getPropertyCities()
    {
        return Property::groupBy('loc_city')->select('loc_city', DB::raw('count(*) as total_properties'))->get();
    }

    public function createProperty(array $data)
    {
        DB::beginTransaction();

        try {
            $property = new Property();
            $property->title = $data['title'];
            $property->description = $data['description'];
            $property->loc_city = $data['loc_city'];
            $property->loc_latitude = $data['loc_latitude'];
            $property->loc_longitude = $data['loc_longitude'];
            $property->loc_address = $data['loc_address'];
            $property->loc_state = $data['loc_state'];
            $property->loc_zip = $data['loc_zip'];
            $property->loc_country = $data['loc_country'];
            $property->price = $data['price'];
            $property->agent_id = $data['agent_id'];
            $property->is_featured = $data['is_featured'];
            $property->is_active = $data['is_active'];
            $property->is_sold = $data['is_sold'];
            $property->is_rented = $data['is_rented'];
            $property->offer_type = $data['offer_type'];
            $property->slug = $data['slug'];
            $property->save();

            $property->amenities()->attach($data['amenities']);

            $property->categories()->attach($data['categories']);

            $property->types()->attach($data['types']);

            if (isset($data['images'])) {
                foreach ($data['images'] as $image) {
                    $newImage = new PropertyImage();
                    $newImage->property_id = $property->id;
                    $newImage->image = $image->store('assets/properties/images', 'public');
                    $newImage->save();
                }
            }

            if (isset($data['floor_plans'])) {
                foreach ($data['floor_plans'] as $floorPlan) {
                    $newFloorPlan = new FloorPlan();
                    $newFloorPlan->property_id = $property->id;
                    $newFloorPlan->sort = $floorPlan['sort'];
                    $newFloorPlan->title = $floorPlan['title'];
                    $newFloorPlan->image = $floorPlan['image']->store('assets/properties/floor_plans', 'public');
                    $newFloorPlan->save();
                }
            }

            DB::commit();

            return $property;
        } catch (\Exception $e) {
            DB::rollBack();

            return $e->getMessage();
        }
    }

    public function updateProperty(array $data, string $id)
    {
        DB::beginTransaction();

        try {
            $property = Property::find($id);

            $property->title = $data['title'];
            $property->description = $data['description'];
            $property->loc_city = $data['loc_city'];
            $property->loc_latitude = $data['loc_latitude'];
            $property->loc_longitude = $data['loc_longitude'];
            $property->loc_address = $data['loc_address'];
            $property->loc_state = $data['loc_state'];
            $property->loc_zip = $data['loc_zip'];
            $property->loc_country = $data['loc_country'];
            $property->price = $data['price'];
            $property->agent_id = $data['agent_id'];
            $property->is_featured = $data['is_featured'];
            $property->is_active = $data['is_active'];
            $property->is_sold = $data['is_sold'];
            $property->is_rented = $data['is_rented'];
            $property->offer_type = $data['offer_type'];
            $property->slug = $data['slug'];
            $property->save();

            $property->amenities()->sync($data['amenities']);

            $property->categories()->sync($data['categories']);

            $property->types()->sync($data['types']);

            foreach ($data['deleted_images_ids'] as $deletedImageId) {
                $propertyImage = PropertyImage::find($deletedImageId);
                Storage::disk('public')->delete($propertyImage->image);
                $propertyImage->delete();
            }
            foreach ($data['images'] as $image) {
                $propertyImage = new PropertyImage();
                $propertyImage->property_id = $property->id;
                $propertyImage->image = $image->store('assets/properties/images', 'public');
                $propertyImage->save();
            }

            foreach ($data['deleted_floor_plans_ids'] as $deletedFloorPlanId) {
                $floorPlan = FloorPlan::find($deletedFloorPlanId);
                Storage::disk('public')->delete($floorPlan->image);
                $floorPlan->delete();
            }
            foreach ($data['floor_plans'] as $floorPlan) {
                if ($floorPlan['id']) {
                    $propertyFloorPlan = FloorPlan::find($floorPlan['id']);
                    $propertyFloorPlan->sort = $floorPlan['sort'];
                    $propertyFloorPlan->title = $floorPlan['title'];
                    if (isset($floorPlan['image'])) {
                        $propertyFloorPlan->image = $this->updateFloorPlanImage($propertyFloorPlan->image, $floorPlan['image']);
                    }
                    $propertyFloorPlan->save();
                } else {
                    $propertyFloorPlan = new FloorPlan();
                    $propertyFloorPlan->property_id = $property->id;
                    $propertyFloorPlan->sort = $floorPlan['sort'];
                    $propertyFloorPlan->title = $floorPlan['title'];
                    $propertyFloorPlan->image = $floorPlan['image']->store('assets/properties/floor_plans', 'public');
                    $propertyFloorPlan->save();
                }
            }

            DB::commit();

            return $property;
        } catch (\Exception $e) {
            DB::rollBack();

            return $e->getMessage();
        }
    }

    public function updateFeaturedProperty($id, $featured)
    {
        $property = Property::find($id);

        $property->update([
            'is_featured' => $featured,
        ]);

        return $property;
    }

    public function updateActiveProperty($id, $active)
    {
        $property = Property::find($id);

        $property->update([
            'is_active' => $active,
        ]);

        return $property;
    }

    public function updateSoldProperty($id, $sold)
    {
        $property = Property::find($id);

        $property->update([
            'is_sold' => $sold,
        ]);

        return $property;
    }

    public function updateRentedProperty($id, $rented)
    {

        $property = Property::find($id);

        $property->update([
            'is_rented' => $rented,
        ]);

        return $property;
    }

    public function deleteProperty(string $id)
    {
        DB::beginTransaction();

        try {
            Property::findOrFail($id)->delete();

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();

            return $e->getMessage();
        }
    }

    private function updateFloorPlanImage($oldImage, $newImage)
    {
        Storage::disk('public')->delete($oldImage);

        return $newImage->store('assets/properties/floor_plans', 'public');
    }
}
