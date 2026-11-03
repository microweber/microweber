<?php

declare(strict_types=1);

namespace MicroweberPackages\LaravelModulesFilament\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    /**
     * LaravelModulesFilamentServiceProvider extends the Coolsam Filament
     * modules provider (Filament + nwidart stack), which is not resolvable
     * standalone. These tests assert the package's shipped configuration
     * without registering the provider.
     */
    protected function getPackageProviders($app): array
    {
        return [];
    }
}
