<?php

namespace App\Interfaces;

interface webConfigurationRepositoryInterface
{
    public function getWebConfiguration();

    public function updateWebConfiguration(array $data);
}
