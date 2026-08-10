<?php

declare(strict_types=1);

namespace MicroweberPackages\Package\Tests\Fixtures;

use MicroweberPackages\Package\MicroweberPackageServiceProvider;
use MicroweberPackages\Package\ModulePackage;
use Spatie\LaravelPackageTools\Package;

/**
 * Provider that requires a module type (CMS module-style).
 */
class ExampleModulePackageServiceProvider extends MicroweberPackageServiceProvider
{
    protected bool $requiresModuleType = true;

    public function configurePackage(Package $package): void
    {
        $package->name('microweber-example-module');
    }

    public function configureModule(ModulePackage $module): void
    {
        $module->type('example-module');
    }
}
