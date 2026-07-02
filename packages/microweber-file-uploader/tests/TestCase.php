<?php

namespace MicroweberPackages\FileUploader\Tests;

use MicroweberPackages\FileUploader\FileUploaderServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            FileUploaderServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app): array
    {
        return [
            'FileUploader' => \MicroweberPackages\FileUploader\Facades\FileUploader::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        // Set up a test storage disk
        $app['config']->set('filesystems.disks.public', [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => '/storage',
            'visibility' => 'public',
        ]);
    }
}