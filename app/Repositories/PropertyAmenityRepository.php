<?php

namespace App\Repositories;

use App\Interfaces\PropertyAmenityRepositoryInterface;
use App\Models\PropertyAmenity;
use Illuminate\Support\Facades\DB;

class PropertyAmenityRepository implements PropertyAmenityRepositoryInterface
{
    public function getAllPropertyAmenities()
    {
        return PropertyAmenity::all();
    }

    public function getPropertyAmenityById(string $id)
    {
        return PropertyAmenity::findOrFail($id);
    }

    public function createPropertyAmenity(array $data)
    {
        DB::beginTransaction();

        try {
            $propertyAmenity = new PropertyAmenity();
            $propertyAmenity->name = $data['name'];
            $propertyAmenity->save();

            DB::commit();

            return $propertyAmenity;
        } catch (\Exception $e) {
            DB::rollBack();

            return $e->getMessage();
        }
    }

    public function updatePropertyAmenity(array $data, string $id)
    {
        DB::beginTransaction();

        try {
            $propertyAmenity = PropertyAmenity::findOrFail($id);
            $propertyAmenity->name = $data['name'];
            $propertyAmenity->save();

            DB::commit();

            return $propertyAmenity;
        } catch (\Exception $e) {
            DB::rollBack();

            return $e->getMessage();
        }
    }

    public function deletePropertyAmenity(string $id)
    {
        DB::beginTransaction();

        try {
            PropertyAmenity::findOrFail($id)->delete();

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();

            return $e->getMessage();
        }
    }
}
