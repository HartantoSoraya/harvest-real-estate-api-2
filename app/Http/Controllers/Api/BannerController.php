<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBannerRequest;
use App\Http\Requests\UpdateBannerRequest;
use App\Http\Resources\BannerResource;
use App\Interfaces\BannerRepositoryInterface;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    protected $bannerRepository;

    public function __construct(BannerRepositoryInterface $bannerRepository)
    {
        $this->bannerRepository = $bannerRepository;
    }

    public function index(Request $request)
    {
        try {
            $banners = $this->bannerRepository->getAllBanners();

            return ResponseHelper::jsonResponse(true, 'Banners retrieved successfully', BannerResource::collection($banners), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), [], 500);
        }
    }

    public function store(StoreBannerRequest $request)
    {
        $request = $request->validated();

        try {
            $banner = $this->bannerRepository->createBanner($request);

            return ResponseHelper::jsonResponse(true, 'Banner created successfully', new BannerResource($banner), 201);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), [], 500);
        }
    }

    public function show($id)
    {
        try {
            $banner = $this->bannerRepository->getBannerById($id);

            return ResponseHelper::jsonResponse(true, 'Banner retrieved successfully', new BannerResource($banner), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), [], 500);
        }
    }

    public function update(UpdateBannerRequest $request, $id)
    {
        $request = $request->validated();

        try {
            $banner = $this->bannerRepository->updateBanner($request, $id);

            return ResponseHelper::jsonResponse(true, 'Banner updated successfully', new BannerResource($banner), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), [], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->bannerRepository->deleteBanner($id);

            return ResponseHelper::jsonResponse(true, 'Banner deleted successfully', [], 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), [], 500);
        }
    }
}
