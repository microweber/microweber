<?php

namespace MicroweberPackages\Url\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use MicroweberPackages\Url\Facades\UrlManager;
use MicroweberPackages\Url\UrlManagerService;

class UrlServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     */
    public function register(): void
    {
        $this->app->singleton(UrlManagerService::class, function () {
            return new UrlManagerService();
        });
    }

    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        // Test-only route previously registered by the removed HelpersServiceProvider.
        if ($this->app->runningInConsole() && $this->app->runningUnitTests()) {
            Route::get('uri_test_details', function () {
                return UrlManager::current();
            })->name('uri_test_details');
        }
    }
}
