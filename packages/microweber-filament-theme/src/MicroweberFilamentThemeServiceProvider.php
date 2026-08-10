<?php

namespace MicroweberPackages\MicroweberFilamentTheme;

use Spatie\LaravelPackageTools\Package;
use MicroweberPackages\Package\MicroweberPackageServiceProvider;
class MicroweberFilamentThemeServiceProvider extends MicroweberPackageServiceProvider
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
