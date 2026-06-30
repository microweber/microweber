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
        $this->app->singleton('phpquery', function ($app) {
            return new PhpQueryManager();
        });

        $this->app->alias('phpquery', PhpQueryManager::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        //
    }
}