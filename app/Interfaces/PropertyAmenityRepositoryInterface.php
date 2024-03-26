<?php

namespace App\Interfaces;

interface propertyAmenityRepositoryInterface
{
    public function getAllPropertyAmenities();

    public function getPropertyAmenityById(string $id);

    public function createPropertyAmenity(array $data);

    public function updatePropertyAmenity(array $data, string $id);

    public function deletePropertyAmenity(string $id);
}
