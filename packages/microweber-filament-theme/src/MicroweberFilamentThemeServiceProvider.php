<?php

namespace MicroweberPackages\MicroweberFilamentTheme;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class MicroweberFilamentThemeServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('microweber-filament-theme')
            ->hasAssets();
    }

    public function register(): void
    {

        $this->publishes([
            dirname(__DIR__) . '/resources/dist' => public_path('vendor/microweber-packages/microweber-filament-theme'),
        ], 'public');

        parent::register();
    }

    public function packageBooted(): void
    {

        MicroweberFilamentTheme::configure();
    }
}
