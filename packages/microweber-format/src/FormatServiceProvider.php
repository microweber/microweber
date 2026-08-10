<?php

namespace MicroweberPackages\Format;


use MicroweberPackages\Package\MicroweberPackageServiceProvider;
use Spatie\LaravelPackageTools\Package;
class FormatServiceProvider extends MicroweberPackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-packages/format');
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(FormatService::class, function () {
            return new FormatService();
        });
    }

    public function packageBooted(): void
    {
        //
    }
}
