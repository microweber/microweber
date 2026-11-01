<?php

namespace MicroweberPackages\CdnSync\Tests;

// Support both standalone (Orchestra\Testbench) and CMS-integrated testing.
// Prefer the CMS-integrated base when we're inside the monorepo: \Tests\TestCase
// only resolves there (the package ships no such class), whereas Testbench is a
// dev dependency that is ALSO installed in the monorepo — so keying off Testbench
// would wrongly pick the :memory: standalone branch during the full suite (giving
// "no such table: users" and unbound CMS services like filament/option_manager).
if (class_exists(\Tests\TestCase::class)) {
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
} else {
    abstract class TestCase extends \Orchestra\Testbench\TestCase
    {
        protected function getPackageProviders($app): array
        {
            return [
                \Livewire\LivewireServiceProvider::class,
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
}