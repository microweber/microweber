<?php

namespace MicroweberPackages\FilamentRegistry;

use Illuminate\Support\ServiceProvider;

class FilamentRegistryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(FilamentRegistryManager::class, function () {
            return new FilamentRegistryManager();
        });

    }

    public function boot(): void
    {
        //
    }
}