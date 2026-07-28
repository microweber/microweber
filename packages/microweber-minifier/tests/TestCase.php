<?php

declare(strict_types=1);

namespace MicroweberPackages\Minifier\Tests;

/**
 * Base test case for the minifier package.
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
                \MicroweberPackages\Minifier\MinifierServiceProvider::class,
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
        }
    }
} else {
    abstract class TestCase extends \Illuminate\Foundation\Testing\TestCase
    {
        use \Tests\CreatesApplication;

        protected function setUp(): void
        {
            parent::setUp();

            if (config('minifier') === null) {
                config(['minifier' => require __DIR__ . '/../config/minifier.php']);
            }

            if (!$this->app->bound(\MicroweberPackages\Minifier\Services\MinifierService::class)) {
                $this->app->register(\MicroweberPackages\Minifier\MinifierServiceProvider::class);
            }
        }
    }
}
