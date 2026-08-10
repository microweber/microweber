<?php

namespace MicroweberPackages\FilamentRegistry;


use MicroweberPackages\Package\MicroweberPackageServiceProvider;
use Spatie\LaravelPackageTools\Package;
class FilamentRegistryServiceProvider extends MicroweberPackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-packages/filament-registry');
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(FilamentRegistryManager::class, function () {
            return new FilamentRegistryManager();
        });

    }

    public function packageBooted(): void
    {
        //
    }
}