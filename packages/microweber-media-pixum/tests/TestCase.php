<?php

namespace MicroweberPackages\MediaPixum\Tests;

/**
 * Base test case for the media-pixum package.
 *
 * Dual-mode: Orchestra Testbench for standalone package testing, or the CMS
 * harness (Tests\TestCase) when running inside the Microweber monorepo.
 */
if (class_exists(\Orchestra\Testbench\TestCase::class) && ! trait_exists(\Tests\CreatesApplication::class)) {
    abstract class TestCase extends \Orchestra\Testbench\TestCase
    {
        protected function getPackageProviders($app): array
        {
            return [
                \MicroweberPackages\MediaPixum\MediaPixumServiceProvider::class,
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

        protected function setUp(): void
        {
            parent::setUp();
            config(['media-pixum.cache_path' => storage_path('app/public/pixum-test')]);
        }

        protected function tearDown(): void
        {
            $pixumPath = config('media-pixum.cache_path');
            if ($pixumPath && is_dir($pixumPath)) {
                foreach ((glob($pixumPath . '/*.png') ?: []) as $f) {
                    @unlink($f);
                }
                @rmdir($pixumPath);
            }
            parent::tearDown();
        }
    }
} else {
    abstract class TestCase extends \Tests\TestCase
    {
        protected function setUp(): void
        {
            parent::setUp();
            config(['media-pixum.cache_path' => storage_path('app/public/pixum-test')]);
        }

        protected function tearDown(): void
        {
            $pixumPath = config('media-pixum.cache_path');
            if ($pixumPath && is_dir($pixumPath)) {
                foreach ((glob($pixumPath . '/*.png') ?: []) as $f) {
                    @unlink($f);
                }
                @rmdir($pixumPath);
            }
            parent::tearDown();
        }
    }
}
