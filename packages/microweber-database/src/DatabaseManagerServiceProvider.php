<?php

namespace MicroweberPackages\Database;

use Illuminate\Support\Facades\Event;
use MicroweberPackages\Database\Facades\DatabaseManager;
use MicroweberPackages\Package\MicroweberPackageServiceProvider;
use Spatie\LaravelPackageTools\Package;

class DatabaseManagerServiceProvider extends MicroweberPackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-packages/database');
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function packageBooted()
    {
        Event::listen(['eloquent.saved: *', 'eloquent.created: *', 'eloquent.deleted: *'], function ($context) {
            DatabaseManager::clearCache();
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function packageRegistered(): void
    {
        $this->app->singleton(DatabaseManagerService::class, function ($app) {
            return new DatabaseManagerService($app);
        });
    }
}
