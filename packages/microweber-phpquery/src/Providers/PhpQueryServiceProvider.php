<?php

namespace MicroweberPackages\PhpQuery\Providers;

use Illuminate\Support\ServiceProvider;
use MicroweberPackages\PhpQuery\PhpQueryManager;

class PhpQueryServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app->singleton(PhpQueryManager::class, function ($app) {
            return new PhpQueryManager();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        //
    }
}