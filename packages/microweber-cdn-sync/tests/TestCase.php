<?php

namespace MicroweberPackages\CdnSync\Tests;

// Support both standalone (Orchestra\Testbench) and CMS-integrated testing.
if (class_exists(\Orchestra\Testbench\TestCase::class)) {
    abstract class TestCase extends \Orchestra\Testbench\TestCase
    {
        protected function getPackageProviders($app): array
        {
            return [
                \MicroweberPackages\CdnSync\CdnSyncServiceProvider::class,
            ];
        }

        protected function getEnvironmentSetUp($app): void
        {
            $app['config']->set('database.default', 'testing');
            $app['config']->set('database.connections.testing', [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'prefix' => '',
            ]);
        }

        protected function setUp(): void
        {
            parent::setUp();
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        }
    }
} else {
    abstract class TestCase extends \Tests\TestCase
    {
        protected function setUp(): void
        {
            parent::setUp();

            // Ensure the cdn_sync_log table exists
            if (!\Illuminate\Support\Facades\Schema::hasTable('cdn_sync_log')) {
                \Illuminate\Support\Facades\Artisan::call('migrate', [
                    '--path' => 'packages/microweber-cdn-sync/database/migrations',
                    '--realpath' => false,
                    '--force' => true,
                ]);
            }
        }
    }
}