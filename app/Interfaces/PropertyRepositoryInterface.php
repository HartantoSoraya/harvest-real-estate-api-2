<?php

namespace App\Interfaces;

interface propertyRepositoryInterface
{
    public function getAllProperties(
        ?string $sortBy = null,
        ?string $sort = null
    );

    public function getPropertiesByParams(
        ?string $search = null,
        ?string $city = null,
        ?string $category = null,
        ?string $amenities = null,
        ?string $type = null,
        ?int $minPrice = null,
        ?int $maxPrice = null,
        ?bool $sold = null,
        ?bool $rented = null
    );

    public function getPropertyById(string $id);

    public function getPropertyBySlug(string $slug);

    public function getPropertyCities();

    public function createProperty(array $data);

    public function updateProperty(array $data, string $id);

    public function updateFeaturedProperty(string $id, bool $featured);

    public function updateActiveProperty(string $id, bool $active);

    public function updateSoldProperty(string $id, bool $sold);

    public function updateRentedProperty(string $id, bool $rented);

    public function deleteProperty(string $id);
}
