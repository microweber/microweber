<?php

namespace MicroweberPackages\Database;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use MicroweberPackages\Database\Facades\DatabaseManager;

class DatabaseManagerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
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
    public function register(): void
    {
        $this->app->singleton(DatabaseManagerService::class, function ($app) {
            return new DatabaseManagerService($app);
        });
    }
}
