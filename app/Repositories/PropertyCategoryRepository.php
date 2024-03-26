<?php

namespace App\Repositories;

use App\Interfaces\PropertyCategoryRepositoryInterface;
use App\Models\PropertyCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PropertyCategoryRepository implements PropertyCategoryRepositoryInterface
{
    public function getAllPropertyCategories()
    {
        return PropertyCategory::all();
    }

    public function getPropertyCategoryById(string $id)
    {
        return PropertyCategory::findOrFail($id);
    }

    public function createPropertyCategory(array $data)
    {
        DB::beginTransaction();

        try {
            $propertyCategory = new PropertyCategory();
            $propertyCategory->name = $data['name'];
            $propertyCategory->icon = $data['icon'];
            $propertyCategory->slug = $data['slug'];
            $propertyCategory->save();

            DB::commit();

            return $propertyCategory;
        } catch (\Exception $e) {
            DB::rollBack();

            return $e->getMessage();
        }
    }

    public function updatePropertyCategory(array $data, string $id)
    {
        DB::beginTransaction();

        try {
            $propertyCategory = PropertyCategory::findOrFail($id);
            $propertyCategory->name = $data['name'];
            $propertyCategory->icon = $data['icon'];
            $propertyCategory->slug = $data['slug'];
            $propertyCategory->save();

            DB::commit();

            return $propertyCategory;
        } catch (\Exception $e) {
            DB::rollBack();

            return $e->getMessage();
        }
    }

    public function deletePropertyCategory(string $id)
    {
        DB::beginTransaction();

        try {
            PropertyCategory::findOrFail($id)->delete();

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
        if (PropertyCategory::count() === 0) {
            return true;
        }

        $query = PropertyCategory::where('slug', $slug);

        if ($exceptId) {
            $query->where('id', '!=', $exceptId);
        }

        return $query->count() === 0;
    }
}
