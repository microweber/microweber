<?php

declare(strict_types=1);

namespace MicroweberPackages\LaravelTemplates\Tests\Standalone;

use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    /**
     * LaravelTemplatesServiceProvider extends nwidart/laravel-modules' provider
     * (not a declared dependency), so it cannot boot standalone. These tests
     * exercise the package's dependency-free units instead. (The CMS-coupled
     * suite under ../ runs in the monorepo.)
     */
    protected function getPackageProviders($app): array
    {
        return [];
    }
}
