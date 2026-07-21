<?php

namespace MicroweberPackages\CdnSync;

use Illuminate\Support\ServiceProvider;
use MicroweberPackages\CdnSync\Services\CdnSyncService;

class CdnSyncServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/cdn-sync.php', 'cdn-sync');

        $this->app->singleton('cdn_sync', function ($app) {
            return new CdnSyncService();
        });

        $this->app->alias('cdn_sync', CdnSyncService::class);
    }

    public function boot(): void
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