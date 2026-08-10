<?php

declare(strict_types=1);

namespace MicroweberPackages\BladeCache;


use MicroweberPackages\Package\MicroweberPackageServiceProvider;
use Spatie\LaravelPackageTools\Package;
class BladeCacheServiceProvider extends MicroweberPackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-packages/blade-cache');
    }

    public function packageRegistered(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/blade-cache.php', 'blade-cache');

        $this->app->singleton(BladeCacheService::class, function () {
            return new BladeCacheService();
        });
    }

    public function packageBooted(): void
    {
        $this->publishes([
            __DIR__ . '/../config/blade-cache.php' => config_path('blade-cache.php'),
        ], 'blade-cache-config');

        CacheDirective::register();
    }
}