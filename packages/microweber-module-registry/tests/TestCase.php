<?php

declare(strict_types=1);

namespace MicroweberPackages\ModuleRegistry\Tests;

use MicroweberPackages\ModuleRegistry\ModuleRegistryServiceProvider;

/**
 * Base test case for the module-registry package.
 *
 * Prefers the monorepo CMS harness when available; falls back to Orchestra
 * Testbench for true standalone installs.
 */
if (class_exists(\Tests\TestCase::class)) {
    abstract class TestCase extends \Tests\TestCase
    {
        protected function setUp(): void
        {
            parent::setUp();

            if (! $this->app->getProvider(ModuleRegistryServiceProvider::class)) {
                $this->app->register(ModuleRegistryServiceProvider::class);
            }

            // Alias facades used by tests when not aliased in CMS
            if (! class_exists('ModuleRegistry', false)) {
                class_alias(
                    \MicroweberPackages\ModuleRegistry\Facades\ModuleRegistry::class,
                    'ModuleRegistry'
                );
            }
        }
    }
} elseif (class_exists(\Orchestra\Testbench\TestCase::class)) {
    abstract class TestCase extends \Orchestra\Testbench\TestCase
    {
        /**
         * @return list<class-string>
         */
        protected function getPackageProviders($app): array
        {
            return [
                ModuleRegistryServiceProvider::class,
            ];
        }

        /**
         * @return array<string, class-string>
         */
        protected function getPackageAliases($app): array
        {
            return [
                'ModuleRegistry' => \MicroweberPackages\ModuleRegistry\Facades\ModuleRegistry::class,
                'Microweber' => \MicroweberPackages\ModuleRegistry\Facades\Microweber::class,
            ];
        }

        protected function defineEnvironment($app): void
        {
            $app['config']->set('view.paths', [
                __DIR__ . '/Fixtures/views',
                resource_path('views'),
            ]);
        }
    }
} else {
    abstract class TestCase extends \PHPUnit\Framework\TestCase
    {
        protected function setUp(): void
        {
            parent::setUp();
            $this->markTestSkipped('Laravel test harness (Tests\\TestCase or Orchestra) is required');
        }
    }
}
