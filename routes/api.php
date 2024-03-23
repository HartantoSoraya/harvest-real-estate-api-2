<?php

use App\Http\Controllers\Api\AgentController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\PropertyAmenityController;
use App\Http\Controllers\Api\PropertyCategoryController;
use App\Http\Controllers\Api\PropertyController;
use App\Http\Controllers\Api\PropertyTypeController;
use App\Http\Controllers\Api\TestimonialController;
use App\Http\Controllers\Api\WebConfigurationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('web-configuration', [WebConfigurationController::class, 'index']);

Route::get('banners', [BannerController::class, 'index']);
Route::get('banner/{id}', [BannerController::class, 'show']);

Route::get('agents', [AgentController::class, 'index']);
Route::get('agent/{id}', [AgentController::class, 'show']);
Route::get('agent/slug/{slug}', [AgentController::class, 'getAgentBySlug']);

Route::get('property-amenities', [PropertyAmenityController::class, 'index']);
Route::get('property-amenity/{id}', [PropertyAmenityController::class, 'show']);

Route::get('property-categories', [PropertyCategoryController::class, 'index']);
Route::get('property-category/{id}', [PropertyCategoryController::class, 'show']);

Route::get('property-types', [PropertyTypeController::class, 'index']);
Route::get('property-type/{id}', [PropertyTypeController::class, 'show']);

Route::get('properties', [PropertyController::class, 'index']);
Route::get('property/{id}', [PropertyController::class, 'show']);

Route::get('properties/search', [PropertyController::class, 'getPropertiesByParams']);
Route::get('property/slug/{slug}', [PropertyController::class, 'getPropertyBySlug']);
Route::get('property/read/cities', [PropertyController::class, 'getPropertyCities']);

Route::get('testimonials', [TestimonialController::class, 'index']);
Route::get('testimonial/{id}', [TestimonialController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('web-configuration', [WebConfigurationController::class, 'update']);

    Route::post('banner', [BannerController::class, 'store']);
    Route::post('banner/{id}', [BannerController::class, 'update']);
    Route::delete('banner/{id}', [BannerController::class, 'destroy']);

    Route::post('agent', [AgentController::class, 'store']);
    Route::post('agent/{id}', [AgentController::class, 'update']);
    Route::delete('agent/{id}', [AgentController::class, 'destroy']);

    Route::post('property-amenity', [PropertyAmenityController::class, 'store']);
    Route::post('property-amenity/{id}', [PropertyAmenityController::class, 'update']);
    Route::delete('property-amenity/{id}', [PropertyAmenityController::class, 'destroy']);

    Route::post('property-category', [PropertyCategoryController::class, 'store']);
    Route::post('property-category/{id}', [PropertyCategoryController::class, 'update']);
    Route::delete('property-category/{id}', [PropertyCategoryController::class, 'destroy']);

    Route::post('property-type', [PropertyTypeController::class, 'store']);
    Route::post('property-type/{id}', [PropertyTypeController::class, 'update']);
    Route::delete('property-type/{id}', [PropertyTypeController::class, 'destroy']);

    Route::post('property', [PropertyController::class, 'store']);
    Route::post('property/{id}', [PropertyController::class, 'update']);
    Route::post('property/featured/{property}', [PropertyController::class, 'updateFeaturedProperty']);
    Route::post('property/active/{property}', [PropertyController::class, 'updateActiveProperty']);
    Route::post('property/sold/{property}', [PropertyController::class, 'updateSoldProperty']);
    Route::post('property/rented/{property}', [PropertyController::class, 'updateRentedProperty']);
    Route::delete('property/{id}', [PropertyController::class, 'destroy']);

    Route::post('testimonial', [TestimonialController::class, 'store']);
    Route::post('testimonial/{id}', [TestimonialController::class, 'update']);
    Route::delete('testimonial/{id}', [TestimonialController::class, 'destroy']);
});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
