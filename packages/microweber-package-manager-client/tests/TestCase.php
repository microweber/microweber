<?php

declare(strict_types=1);

namespace MicroweberPackages\PackageManagerClient\Tests;

use MicroweberPackages\PackageManagerClient\PackageManagerClientServiceProvider;

// Support both standalone (Orchestra\Testbench) and CMS-integrated testing.
if (class_exists(\Orchestra\Testbench\TestCase::class)) {
    abstract class TestCase extends \Orchestra\Testbench\TestCase
    {
        /**
         * @param \Illuminate\Foundation\Application $app
         * @return list<class-string>
         */
        protected function getPackageProviders($app): array
        {
            return [
                PackageManagerClientServiceProvider::class,
            ];
        }

        /**
         * @param \Illuminate\Foundation\Application $app
         */
        protected function getEnvironmentSetUp($app): void
        {
            $app['config']->set('app.key', 'base64:' . base64_encode(random_bytes(32)));
            $app['config']->set('database.default', 'testing');
            $app['config']->set('database.connections.testing', [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'prefix' => '',
            ]);
            $app['config']->set('package-manager-client.modules_path', 'Modules');
            $app['config']->set('package-manager-client.templates_path', 'Templates');
            $app['config']->set('package-manager-client.http.verify_ssl', false);
        }
    }
} else {
    abstract class TestCase extends \Tests\TestCase
    {
        protected function setUp(): void
        {
            parent::setUp();

            if (config('package-manager-client') === null) {
                config(['package-manager-client' => require __DIR__ . '/../config/package-manager-client.php']);
            }

            if (!$this->app->bound(\MicroweberPackages\PackageManagerClient\PackageManagerClientService::class)) {
                $this->app->register(PackageManagerClientServiceProvider::class);
            }
        }
    }
}
