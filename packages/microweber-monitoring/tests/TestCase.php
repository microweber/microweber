<?php

declare(strict_types=1);

namespace MicroweberPackages\Monitoring\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    /**
     * MonitoringServiceProvider::boot() registers Filament resources/widgets
     * through the FilamentRegistry facade, which needs a booted Filament panel
     * (full admin runtime). Standalone coverage therefore exercises the
     * self-contained ErrorTrackingService against an in-memory database and
     * loads only the package's own migration.
     */
    protected function getPackageProviders($app): array
    {
        return [];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(dirname(__DIR__) . '/src/database/migrations');
    }
}
