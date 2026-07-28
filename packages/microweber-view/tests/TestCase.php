<?php

declare(strict_types=1);

namespace MicroweberPackages\View\Tests;

/**
 * Base test case for the microweber-view package.
 *
 * Uses the full CMS application when available (Microweber monorepo),
 * otherwise Orchestra Testbench for standalone package testing.
 */
if (class_exists(\Orchestra\Testbench\TestCase::class) && !trait_exists(\Tests\CreatesApplication::class)) {
    abstract class TestCase extends \Orchestra\Testbench\TestCase
    {
        /**
         * @return list<class-string>
         */
        protected function getPackageProviders($app): array
        {
            return [
                \MicroweberPackages\View\ViewServiceProvider::class,
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
            $app['config']->set('microweber-view.routes_enabled', true);
            $app['config']->set('microweber-view.allow_render_endpoints', true);
            $app['config']->set('microweber-view.module_directive_enabled', true);
        }
    }
} else {
    abstract class TestCase extends \Illuminate\Foundation\Testing\TestCase
    {
        use \Tests\CreatesApplication;

        protected function setUp(): void
        {
            parent::setUp();

            if (config('microweber-view') === null) {
                config(['microweber-view' => require __DIR__ . '/../config/microweber-view.php']);
            }
        }
    }
}
