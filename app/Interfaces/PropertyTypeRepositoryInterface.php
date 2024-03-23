<?php

namespace App\Interfaces;

interface propertyTypeRepositoryInterface
{
    public function getAllPropertyTypes();

    public function getPropertyTypeById(string $id);

    public function createPropertyType(array $data);

    public function updatePropertyType(array $data, string $id);

    public function deletePropertyType(string $id);

    public function generateSlug(string $name, int $tryCount): string;

    public function isUniqueSlug(string $slug, ?string $exceptId = null): bool;
}
