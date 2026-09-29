<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Label;
use Illuminate\Support\Facades\View;


class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('layouts.simple.header', function ($view) {
            $view->with('labels', Label::all());
        });
        
        // View::composer('layouts.simple.header', function ($view) {
        //     $labels = cache()->remember('header_labels', 3600, function () {
        //         return Label::all();
        //     });

        //     $view->with('labels', $labels);
        // });
    }
}
