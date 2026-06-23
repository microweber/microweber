<?php

namespace MicroweberPackages\Url\Providers;

use Illuminate\Support\ServiceProvider;
use MicroweberPackages\Url\UrlManager;

class UrlServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     */
    public function register(): void
    {
        $this->app->singleton('url_manager', function ($app) {
            return new UrlManager();
        });
    }

    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        //
    }
}