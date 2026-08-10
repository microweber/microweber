<?php

namespace MicroweberPackages\PhpQuery\Providers;

use MicroweberPackages\PhpQuery\PhpQueryManager;
use MicroweberPackages\Package\MicroweberPackageServiceProvider;
use Spatie\LaravelPackageTools\Package;

class PhpQueryServiceProvider extends MicroweberPackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-packages/phpquery');
    }

    /**
     * Register the service provider.
     */
    public function packageRegistered()
    {
        $this->app->singleton(PhpQueryManager::class, function ($app) {
            return new PhpQueryManager();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function packageBooted()
    {
        //
    }
}