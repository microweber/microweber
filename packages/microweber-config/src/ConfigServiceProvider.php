<?php

namespace MicroweberPackages\Config;

use Illuminate\Support\Facades\Facade;
use MicroweberPackages\Package\MicroweberPackageServiceProvider;
use Spatie\LaravelPackageTools\Package;
class ConfigServiceProvider extends MicroweberPackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-packages/config');
    }

    public function packageRegistered(): void
    {
        $config = new ConfigRepository($this->app);
        $this->app->instance('config', $config);

        // Clear the facade's cached instance so it picks up our new
        // ConfigRepository instead of the original Repository instance
        // that was resolved during bootstrap.
        Facade::clearResolvedInstance('config');
    }
}