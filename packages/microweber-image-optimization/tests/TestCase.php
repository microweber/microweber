<?php

declare(strict_types=1);

namespace MicroweberPackages\ImageOptimization\Tests;

/**
 * Base test case for the image-optimization package.
 *
 * Uses the full CMS application when available (Microweber monorepo),
 * otherwise Orchestra Testbench for standalone package testing.
 */
if (class_exists(\Orchestra\Testbench\TestCase::class) && !trait_exists(\Tests\CreatesApplication::class)) {
    abstract class TestCase extends \Orchestra\Testbench\TestCase
    {
        protected function getPackageProviders($app): array
        {
            return [
                \MicroweberPackages\ImageOptimization\ImageOptimizationServiceProvider::class,
            ];
        }

        protected function getEnvironmentSetUp($app): void
        {
            $app['config']->set('app.key', 'base64:' . base64_encode(random_bytes(32)));
            $app['config']->set('database.default', 'testing');
            $app['config']->set('database.connections.testing', [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'prefix' => '',
            ]);
            $app['config']->set('filesystems.disks.public', [
                'driver' => 'local',
                'root' => storage_path('app/public'),
                'url' => '/storage',
                'visibility' => 'public',
            ]);
        }
    }
} else {
    abstract class TestCase extends \Illuminate\Foundation\Testing\TestCase
    {
        use \Tests\CreatesApplication;

        protected function setUp(): void
        {
            parent::setUp();

            // Ensure package config is available
            if (config('image-optimization') === null) {
                config(['image-optimization' => require __DIR__ . '/../config/image-optimization.php']);
            }
        }
    }
}
