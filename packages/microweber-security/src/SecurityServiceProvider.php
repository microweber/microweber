<?php

namespace MicroweberPackages\Security;


use MicroweberPackages\Package\MicroweberPackageServiceProvider;
use Spatie\LaravelPackageTools\Package;
class SecurityServiceProvider extends MicroweberPackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-packages/security');
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(HtmlClean::class, function () {
            return new HtmlClean();
        });

        $this->app->singleton(XSSClean::class, function () {
            return new XSSClean();
        });

        $this->app->singleton(XSSSecurity::class, function () {
            return new XSSSecurity();
        });
    }

    public function packageBooted(): void
    {
        //
    }
}
