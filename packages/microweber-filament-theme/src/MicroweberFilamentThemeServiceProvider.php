<?php

namespace MicroweberPackages\MicroweberFilamentTheme;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class MicroweberFilamentThemeServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('microweber-filament-theme');
    }

    public function packageBooted(): void
    {
        MicroweberFilamentTheme::configure();
    }
}
