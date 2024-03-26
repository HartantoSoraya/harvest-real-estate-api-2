<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->bind(\App\Interfaces\WebConfigurationRepositoryInterface::class, \App\Repositories\WebConfigurationRepository::class);
        $this->app->bind(\App\Interfaces\BannerRepositoryInterface::class, \App\Repositories\BannerRepository::class);
        $this->app->bind(\App\Interfaces\PropertyAmenityRepositoryInterface::class, \App\Repositories\PropertyAmenityRepository::class);
        $this->app->bind(\App\Interfaces\PropertyCategoryRepositoryInterface::class, \App\Repositories\PropertyCategoryRepository::class);
        $this->app->bind(\App\Interfaces\PropertyTypeRepositoryInterface::class, \App\Repositories\PropertyTypeRepository::class);
        $this->app->bind(\App\Interfaces\FloorPlanRepositoryInterface::class, \App\Repositories\FloorPlanRepository::class);
        $this->app->bind(\App\Interfaces\propertyImageRepositoryInterface::class, \App\Repositories\propertyImageRepository::class);
        $this->app->bind(\App\Interfaces\PropertyRepositoryInterface::class, \App\Repositories\PropertyRepository::class);
        $this->app->bind(\App\Interfaces\AgentRepositoryInterface::class, \App\Repositories\AgentRepository::class);
        $this->app->bind(\App\Interfaces\TestimonialRepositoryInterface::class, \App\Repositories\TestimonialRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
