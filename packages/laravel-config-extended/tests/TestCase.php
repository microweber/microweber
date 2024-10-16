<?php

namespace MicroweberPackages\LaravelConfigExtended\Tests;

use MicroweberPackages\LaravelConfigExtended\ConfigExtendedServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

     //   $this->setUpDatabase();
    }
    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }
    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {

        return [
            ConfigExtendedServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'config' => \MicroweberPackages\Config\Facades\ConfigExtended::class,
        ];
    }
}