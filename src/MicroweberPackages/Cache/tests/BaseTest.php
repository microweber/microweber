<?php

class BaseTest extends Orchestra\Testbench\TestCase
{

    public function tearDown(): void
    {
        \Mockery::close();
    }


    protected function getPackageProviders($app)
    {
        return [\MicroweberPackages\Cache\TaggableFileCacheServiceProvider::class];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.key', 'tQbgKF5NH5zMyGh4vCNypFAzx9trCkE6x');

        // Setup default database to use sqlite :memory:
        $app['config']->set('cache.default', 'file');
        $app['config']->set('cache.stores.file',
            [
                'driver' => 'file',
                'path' => storage_path('framework/cache'),
                'separator' => '~#~'
            ]
        );
    }

}
