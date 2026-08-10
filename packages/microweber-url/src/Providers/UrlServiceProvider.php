<?php

namespace MicroweberPackages\Url\Providers;

use Illuminate\Support\Facades\Route;
use MicroweberPackages\Url\Facades\UrlManager;
use MicroweberPackages\Url\UrlManagerService;
use MicroweberPackages\Package\MicroweberPackageServiceProvider;
use Spatie\LaravelPackageTools\Package;

class UrlServiceProvider extends MicroweberPackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-packages/url');
    }

    /**
     * Register the application services.
     */
    public function packageRegistered(): void
    {
        $this->app->singleton(UrlManagerService::class, function () {
            return new UrlManagerService();
        });
    }

    /**
     * Bootstrap the application services.
     */
    public function packageBooted(): void
    {
        // Test-only route previously registered by the removed HelpersServiceProvider.
        if ($this->app->runningInConsole() && $this->app->runningUnitTests()) {
            Route::get('uri_test_details', function () {
                return UrlManager::current();
            })->name('uri_test_details');
        }
    }
}
