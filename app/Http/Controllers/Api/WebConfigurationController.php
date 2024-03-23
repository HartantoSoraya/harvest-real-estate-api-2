<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateWebConfigurationRequest;
use App\Http\Resources\WebConfigurationResource;
use App\Interfaces\WebConfigurationRepositoryInterface;
use Illuminate\Http\Request;

class WebConfigurationController extends Controller
{
    protected $webConfigurationRepository;

    public function __construct(WebConfigurationRepositoryInterface $webConfigurationRepository)
    {
        $this->webConfigurationRepository = $webConfigurationRepository;
    }

    public function index(Request $request)
    {
        try {
            $webConfiguration = $this->webConfigurationRepository->getWebConfiguration();

            return ResponseHelper::jsonResponse(true, 'Web Configurations retrieved successfully', new WebConfigurationResource($webConfiguration), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), [], 500);
        }
    }

    public function update(UpdateWebConfigurationRequest $request)
    {
        $request = $request->validated();

        try {
            $webConfiguration = $this->webConfigurationRepository->updateWebConfiguration($request);

            return ResponseHelper::jsonResponse(true, 'Web Configurations updated successfully', new WebConfigurationResource($webConfiguration), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), [], 500);
        }
    }
}
