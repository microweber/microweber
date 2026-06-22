<?php

namespace MicroweberPackages\TaggableFileCache\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use MicroweberPackages\TaggableFileCache\TaggableFileCacheServiceProvider;

class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            TaggableFileCacheServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app)
    {
        $app['config']->set('cache.default', 'file');
        $app['config']->set('cache.stores.file', [
            'driver' => 'file',
            'path' => storage_path('framework/cache/data'),
        ]);
    }
}