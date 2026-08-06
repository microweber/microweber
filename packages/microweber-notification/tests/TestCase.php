<?php

declare(strict_types=1);

namespace MicroweberPackages\Notification\Tests;

/**
 * Base test case for the microweber-notification package.
 *
 * Uses the full CMS application when available (Microweber monorepo),
 * otherwise Orchestra Testbench for standalone package testing.
 */
if (class_exists(\Orchestra\Testbench\TestCase::class) && ! trait_exists(\Tests\CreatesApplication::class)) {
    abstract class TestCase extends \Orchestra\Testbench\TestCase
    {
        /**
         * @param  \Illuminate\Foundation\Application  $app
         * @return list<class-string>
         */
        protected function getPackageProviders($app): array
        {
            return [
                \MicroweberPackages\Notification\Providers\NotificationServiceProvider::class,
            ];
        }

        /**
         * @param  \Illuminate\Foundation\Application  $app
         */
        protected function getEnvironmentSetUp($app): void
        {
            $app['config']->set('app.key', 'base64:' . base64_encode(random_bytes(32)));
            $app['config']->set('app.url', 'http://localhost');
            $app['config']->set('database.default', 'testing');
            $app['config']->set('database.connections.testing', [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'prefix' => '',
            ]);
            $app['config']->set('mail.default', 'array');
            $app['config']->set('microweber-notification.load_admin_routes', true);
            $app['config']->set('microweber-notification.admin_route_prefix', 'admin');
            $app['config']->set('microweber-notification.admin_middleware', ['web']);
            $app['config']->set('microweber-notification.admin_user_model', null);
            $app['config']->set('microweber-notification.admin_column', 'is_admin');
            $app['config']->set('microweber-notification.admin_value', 1);
        }

        protected function setUp(): void
        {
            parent::setUp();
            $this->loadPackageMigrations();
        }

        protected function loadPackageMigrations(): void
        {
            $migrations = dirname(__DIR__) . '/database/migrations';
            if (is_dir($migrations)) {
                $this->loadMigrationsFrom($migrations);
            }
        }
    }
} else {
    abstract class TestCase extends \Illuminate\Foundation\Testing\TestCase
    {
        use \Tests\CreatesApplication;

        protected function setUp(): void
        {
            parent::setUp();

            if (config('microweber-notification') === null) {
                config([
                    'microweber-notification' => require __DIR__ . '/../config/microweber-notification.php',
                ]);
            }

            if (! $this->app->bound(\MicroweberPackages\Notification\Services\NotificationsManager::class)) {
                $this->app->register(
                    \MicroweberPackages\Notification\Providers\NotificationServiceProvider::class
                );
            }
        }
    }
}
