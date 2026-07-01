<?php

namespace MicroweberPackages\FilamentRegistry;

use Illuminate\Support\ServiceProvider;
use MicroweberPackages\FilamentRegistry\Facades\FilamentRegistry;

class FilamentRegistryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(FilamentRegistryManager::class, function () {
            return new FilamentRegistryManager();
        });

        // Also bind the facade accessor
        $this->app->alias(FilamentRegistryManager::class, FilamentRegistry::class);
    }

    public function boot(): void
    {
        //
    }
}