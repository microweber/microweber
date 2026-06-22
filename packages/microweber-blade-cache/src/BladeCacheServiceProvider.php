<?php

declare(strict_types=1);

namespace MicroweberPackages\BladeCache;

use Illuminate\Support\ServiceProvider;

class BladeCacheServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/blade-cache.php', 'blade-cache');

        $this->app->singleton(BladeCacheService::class, function () {
            return new BladeCacheService();
        });
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/blade-cache.php' => config_path('blade-cache.php'),
        ], 'blade-cache-config');

        CacheDirective::register();
    }
}