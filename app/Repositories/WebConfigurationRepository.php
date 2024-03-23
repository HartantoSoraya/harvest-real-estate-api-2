<?php

namespace App\Repositories;

use App\Interfaces\WebConfigurationRepositoryInterface;
use App\Models\WebConfiguration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class WebConfigurationRepository implements WebConfigurationRepositoryInterface
{
    public function getWebConfiguration()
    {
        return WebConfiguration::first();
    }

    public function updateWebConfiguration(array $data)
    {
        DB::beginTransaction();

        try {
            $webConfiguration = WebConfiguration::first();
            $webConfiguration->title = $data['title'];
            $webConfiguration->description = $data['description'];
            $webConfiguration->email = $data['email'];
            $webConfiguration->phone = $data['phone'];
            if (isset($data['logo'])) {
                $webConfiguration->logo = $this->updateLogo($webConfiguration->logo, $data['logo']);
            }
            $webConfiguration->map = $data['map'];
            $webConfiguration->address = $data['address'];
            $webConfiguration->facebook = $data['facebook'];
            $webConfiguration->instagram = $data['instagram'];
            $webConfiguration->youtube = $data['youtube'];
            $webConfiguration->save();

            DB::commit();

            return $webConfiguration;
        } catch (\Exception $e) {
            DB::rollBack();

            return $e->getMessage();
        }
    }

    public function updateLogo($oldImage, $newImage)
    {
        Storage::disk('public')->delete($oldImage);

        return $newImage->store('assets/web-configurations', 'public');
    }
}
