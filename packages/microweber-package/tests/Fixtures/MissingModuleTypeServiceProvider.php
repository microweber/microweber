<?php

declare(strict_types=1);

namespace MicroweberPackages\Package\Tests\Fixtures;

use MicroweberPackages\Package\MicroweberPackageServiceProvider;
use Spatie\LaravelPackageTools\Package;

/**
 * Provider that requires a module type but never sets one — for exception tests.
 */
class MissingModuleTypeServiceProvider extends MicroweberPackageServiceProvider
{
    protected bool $requiresModuleType = true;

    public function configurePackage(Package $package): void
    {
        $package->name('microweber-missing-module-type');
    }
}
