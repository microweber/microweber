<?php

declare(strict_types=1);

namespace MicroweberPackages\Passport\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Base test case for the microweber-passport package.
 *
 * Auto-detects the runtime: uses Orchestra Testbench when available
 * (standalone package testing), or Microweber's Tests\TestCase when
 * running inside the CMS.
 */
if (class_exists(\Orchestra\Testbench\TestCase::class)) {
    abstract class TestCase extends \Orchestra\Testbench\TestCase
    {
        use RefreshDatabase;

        protected function getPackageProviders($app): array
        {
            return [
                \MicroweberPackages\Passport\Providers\MicroweberPassportServiceProvider::class,
            ];
        }

        protected function defineEnvironment($app): void
        {
            $app['config']->set('database.default', 'testing');
            $app['config']->set('database.connections.testing', [
                'driver'   => 'sqlite',
                'database' => ':memory:',
                'prefix'   => '',
            ]);

            $app['config']->set('auth.guards.api', [
                'driver' => 'passport',
                'provider' => 'users',
            ]);

            $app['config']->set('auth.providers.users', [
                'driver' => 'eloquent',
                'model' => \MicroweberPackages\Passport\Tests\Fixtures\User::class,
            ]);
        }

        protected function defineDatabaseMigrations(): void
        {
            $this->loadLaravelMigrations();
        }
    }
} else {
    abstract class TestCase extends \Tests\TestCase
    {
        protected function setUp(): void
        {
            parent::setUp();
        }
    }
}