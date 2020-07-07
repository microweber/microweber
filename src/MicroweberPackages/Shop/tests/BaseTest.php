<?php
namespace MicroweberPackages\Shop\tests;

use Orchestra\Testbench\TestCase;

abstract class BaseTest extends TestCase
{
    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate', ['--database' => 'testing']);
    }

    public function tearDown(): void
    {
        \Mockery::close();
    }


    protected function getPackageProviders($app)
    {
        return [
           \MicroweberPackages\ContentManager\ContentManagerServiceProvider::class,
            \MicroweberPackages\DatabaseManager\DatabaseManagerServiceProvider::class,
            \MicroweberPackages\Cache\TaggableFileCacheServiceProvider::class,
            \MicroweberPackages\Helpers\HelpersServiceProvider::class,
            \MicroweberPackages\EventManager\EventManagerServiceProvider::class,
            \MicroweberPackages\ContentManager\ContentManagerServiceProvider::class,
            \MicroweberPackages\CategoryManager\CategoryManagerServiceProvider::class,
            \MicroweberPackages\MediaManager\MediaManagerServiceProvider::class,
            \MicroweberPackages\ShopManager\ShopManagerServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'database_manager' => \MicroweberPackages\DatabaseManager\DatabaseManagerFacade::class,
            'option_manager' => \MicroweberPackages\ContentManager\ContentManagerFacade::class,
            'category_manager' => \MicroweberPackages\CategoryManager\CategoryManagerFacade::class
        ];
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
        $app['config']->set('database.default', 'testing');

        $app['config']->set('cache.default', 'tfile');
        $app['config']->set('cache.stores.tfile',
            [
                'driver' => 'tfile',
                'path' => storage_path('framework/cache'),
                'separator' => '~#~'
            ]
        );

    }

}
