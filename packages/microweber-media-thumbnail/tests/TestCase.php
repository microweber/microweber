<?php

namespace MicroweberPackages\MediaThumbnail\Tests;

/**
 * Base test case for the media-thumbnail package.
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
                // media-thumbnail delegates to media-pixum (e.g. the media-pixum.serve
                // route) and the thumbnailer package (ThumbnailGenerator binding), so
                // their providers must be registered too.
                \MicroweberPackages\Thumbnailer\ThumbnailerServiceProvider::class,
                \MicroweberPackages\MediaPixum\MediaPixumServiceProvider::class,
                \MicroweberPackages\MediaThumbnail\MediaThumbnailServiceProvider::class,
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
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
            $this->applyPaths();
        }

        private function applyPaths(): void
        {
            $thumbnailsPath = storage_path('app/public/thumbnails-test');
            config(['thumbnailer.thumbnails_path' => $thumbnailsPath]);
            config(['thumbnailer.thumbnails_url' => '/storage/thumbnails']);
            config(['media-thumbnail.thumbnails_path' => $thumbnailsPath]);
            config(['media-pixum.cache_path' => $thumbnailsPath . '/pixum']);
        }
    }
} else {
    abstract class TestCase extends \Tests\TestCase
    {
        protected function setUp(): void
        {
            parent::setUp();

            $thumbnailsPath = storage_path('app/public/thumbnails-test');
            config(['thumbnailer.thumbnails_path' => $thumbnailsPath]);
            config(['thumbnailer.thumbnails_url' => '/storage/thumbnails']);
            config(['media-thumbnail.thumbnails_path' => $thumbnailsPath]);
            config(['media-pixum.cache_path' => $thumbnailsPath . '/pixum']);
        }
    }
}
