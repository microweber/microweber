<?php

namespace MicroweberPackages\Config\Tests;

use MicroweberPackages\Config\ConfigServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    protected function getPackageProviders($app): array
    {
        return [
            ConfigServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app): array
    {
        return [
            'ConfigExtended' => \MicroweberPackages\Config\Facades\ConfigExtended::class,
        ];
    }
}