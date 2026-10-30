<?php

namespace MicroweberPackages\ContentField\Tests;

use MicroweberPackages\ContentField\ContentFieldServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            ContentFieldServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        // Isolated in-memory DB so these standalone package tests never touch
        // (and previously, via RefreshDatabase, wiped) the shared file DB used by
        // the full-app suite. Testbench's defineDatabaseMigrations() below runs
        // the package migrations into it per test — no RefreshDatabase needed.
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
}
