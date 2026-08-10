<?php

declare(strict_types=1);

namespace MicroweberPackages\Package\Tests\Fixtures;

use MicroweberPackages\Package\MicroweberPackageServiceProvider;
use Spatie\LaravelPackageTools\Package;

/**
 * Concrete package provider used by unit/feature/standalone tests.
 */
class ExamplePackageServiceProvider extends MicroweberPackageServiceProvider
{
    public bool $registeredHookCalled = false;

    public bool $bootedHookCalled = false;

    public function configurePackage(Package $package): void
    {
        $package->name('microweber-example-package');
    }

    public function packageRegistered(): void
    {
        $this->registeredHookCalled = true;
        $this->app->instance('microweber.example-package.flag', true);
    }

    public function packageBooted(): void
    {
        $this->bootedHookCalled = true;
    }
}
