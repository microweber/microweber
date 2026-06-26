<?php

namespace MicroweberPackages\Searchable\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use MicroweberPackages\Searchable\SearchableServiceProvider;

class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            SearchableServiceProvider::class,
        ];
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/migrations');
    }
}