<?php

declare(strict_types=1);

namespace MicroweberPackages\LaravelModules\Tests\Standalone;

use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    /**
     * LaravelModulesServiceProvider extends nwidart/laravel-modules' provider
     * (not a declared dependency) and wires Filament resources, so it cannot
     * boot standalone. These tests exercise the package's dependency-free
     * units instead. (The CMS-coupled suite under ../ runs in the monorepo.)
     */
    protected function getPackageProviders($app): array
    {
        return [];
    }
}
