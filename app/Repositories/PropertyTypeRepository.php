<?php

namespace App\Repositories;

use App\Interfaces\PropertyTypeRepositoryInterface;
use App\Models\PropertyType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PropertyTypeRepository implements PropertyTypeRepositoryInterface
{
    public function getAllPropertyTypes()
    {
        return PropertyType::all();
    }

    public function getPropertyTypeById(string $id)
    {
        return PropertyType::findOrFail($id);
    }

    public function createPropertyType(array $data)
    {
        DB::beginTransaction();

        try {
            $propertyType = new PropertyType;
            $propertyType->name = $data['name'];
            $propertyType->slug = $data['slug'];
            $propertyType->save();

            DB::commit();

            return $propertyType;
        } catch (\Exception $e) {
            DB::rollBack();

            return $e->getMessage();
        }
    }

    public function updatePropertyType(array $data, string $id)
    {
        DB::beginTransaction();

        try {
            $propertyType = PropertyType::findOrFail($id);
            $propertyType->name = $data['name'];
            $propertyType->slug = $data['slug'];
            $propertyType->save();

            DB::commit();

            return $propertyType;
        } catch (\Exception $e) {
            DB::rollBack();

            return $e->getMessage();
        }
    }

    public function deletePropertyType(string $id)
    {
        DB::beginTransaction();

        try {
            PropertyType::findOrFail($id)->delete();

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();

            return $e->getMessage();
        }
    }

    public function generateSlug(string $name, int $tryCount): string
    {
        $slug = Str::slug($name);

        if ($tryCount > 0) {
            $slug = $slug.'_'.$tryCount;
        }

        return $slug;
    }

    public function isUniqueSlug(string $slug, ?string $exceptId = null): bool
    {
        if (PropertyType::count() === 0) {
            return true;
        }

        $query = PropertyType::where('slug', $slug);

        if ($exceptId) {
            $query->where('id', '!=', $exceptId);
        }

        return $query->count() === 0;
    }
}
