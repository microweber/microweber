<?php

namespace MicroweberPackages\CssFrameworkBootstrap\Providers;


use MicroweberPackages\Package\MicroweberPackageServiceProvider;
use Spatie\LaravelPackageTools\Package;
class CssFrameworkServiceProvider extends MicroweberPackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber/css-framework-bootstrap');
    }

    public function packageBooted(): void
    {
        $this->publishes([
            __DIR__ . '/../resources/assets' => public_path('packages/microweber-css-framework-bootstrap'),
        ], ['mw-css-framework', 'public']);
    }

    public function packageRegistered(): void
    {
        //
    }
}