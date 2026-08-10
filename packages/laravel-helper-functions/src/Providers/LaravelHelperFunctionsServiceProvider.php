<?php

declare(strict_types=1);

namespace MicroweberPackages\LaravelHelperFunctions\Providers;

use MicroweberPackages\Package\MicroweberPackageServiceProvider;
use Spatie\LaravelPackageTools\Package;

class LaravelHelperFunctionsServiceProvider extends MicroweberPackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-packages/laravel-helper-functions');
    }
}
