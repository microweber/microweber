<?php

namespace MicroweberPackages\CdnSync;

use MicroweberPackages\CdnSync\Services\CdnSyncService;
use MicroweberPackages\Package\MicroweberPackageServiceProvider;
use Spatie\LaravelPackageTools\Package;

class CdnSyncServiceProvider extends MicroweberPackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-packages/cdn-sync');
    }

    public function packageRegistered(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/cdn-sync.php', 'cdn-sync');

        $this->app->singleton(CdnSyncService::class, function ($app) {
            return new CdnSyncService();
        });
    }

    public function packageBooted(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'cdn-sync');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/cdn-sync.php' => config_path('cdn-sync.php'),
            ], 'cdn-sync-config');

            $this->publishes([
                __DIR__ . '/../database/migrations' => database_path('migrations'),
            ], 'cdn-sync-migrations');
        }
    }
}
