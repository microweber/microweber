<?php

namespace MicroweberPackages\MediaThumbnail\Tests;

use MicroweberPackages\MediaThumbnail\MediaThumbnailServiceProvider;
use MicroweberPackages\Thumbnailer\ThumbnailerServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            ThumbnailerServiceProvider::class,
            MediaThumbnailServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $thumbnailsPath = $app->storagePath('app/public/thumbnails');

        $app['config']->set('app.key', 'base64:' . base64_encode(random_bytes(32)));
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
        $app['config']->set('thumbnailer.thumbnails_path', $thumbnailsPath);
        $app['config']->set('thumbnailer.thumbnails_url', '/storage/thumbnails');
        $app['config']->set('media-thumbnail.thumbnails_path', $thumbnailsPath);
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
}