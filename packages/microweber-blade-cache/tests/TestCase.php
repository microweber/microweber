<?php

declare(strict_types=1);

namespace MicroweberPackages\BladeCache\Tests;

use MicroweberPackages\BladeCache\BladeCacheServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            BladeCacheServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('cache.default', 'array');
        $app['config']->set('blade-cache.enabled', true);
        $app['config']->set('blade-cache.ttl', 3600);
        $app['config']->set('blade-cache.store', null);
    }
}