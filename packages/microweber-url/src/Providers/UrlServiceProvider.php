<?php

namespace MicroweberPackages\Url\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use MicroweberPackages\Url\UrlManager;

class UrlServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     */
    public function register(): void
    {
        $this->app->singleton(UrlManager::class, function () {
            return new UrlManager();
        });

        $this->app->alias(UrlManager::class, 'url_manager');
    }

    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        // Test-only route previously registered by the removed HelpersServiceProvider.
        if ($this->app->runningInConsole() && $this->app->runningUnitTests()) {
            Route::get('uri_test_details', function () {
                /** @var UrlManager $urlManager */
                $urlManager = app('url_manager');

                return $urlManager->current();
            })->name('uri_test_details');
        }
    }
}