<?php

namespace App\Providers;

use App\Services\BunnyStorageService;
use App\Services\DocumentService;
use App\Services\DocusealService;
use App\Services\TestService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(BunnyStorageService::class, function ($app) {
            return new BunnyStorageService();
        });

     

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
