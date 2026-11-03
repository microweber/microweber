<?php

declare(strict_types=1);

namespace Coolsam\Modules\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    /**
     * ModulesServiceProvider extends the host CMS package provider and wires
     * Filament + nwidart/laravel-modules, which are not available standalone.
     * These tests assert the package's dependency-free units instead, so no
     * package provider is registered here.
     */
    protected function getPackageProviders($app): array
    {
        return [];
    }
}
