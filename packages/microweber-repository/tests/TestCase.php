<?php

namespace MicroweberPackages\Repository\Tests;

use MicroweberPackages\Repository\Providers\RepositoryServiceProvider;
use MicroweberPackages\Repository\Providers\RepositoryEventServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            RepositoryServiceProvider::class,
            RepositoryEventServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app)
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
        $app['config']->set('repositories.cache_enabled', false);
    }
}