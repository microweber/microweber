<?php

namespace MicroweberPackages\EnvWriter;


use MicroweberPackages\Package\MicroweberPackageServiceProvider;
use Spatie\LaravelPackageTools\Package;
class EnvWriterServiceProvider extends MicroweberPackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-packages/env-writer');
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(EnvWriterService::class, function () {
            return new EnvWriterService();
        });
    }
}
