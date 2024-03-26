<?php

namespace App\Interfaces;

interface propertyCategoryRepositoryInterface
{
    public function getAllPropertyCategories();

    public function getPropertyCategoryById(string $id);

    public function createPropertyCategory(array $data);

    public function updatePropertyCategory(array $data, string $id);

    public function deletePropertyCategory(string $id);

    public function generateSlug(string $name, int $tryCount): string;

    public function isUniqueSlug(string $slug, ?string $exceptId = null): bool;
}
