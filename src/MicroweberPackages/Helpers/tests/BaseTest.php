<?php
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;

abstract class BaseTest extends Orchestra\Testbench\TestCase
{

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function tearDown(): void
    {
        \Mockery::close();
    }


    protected function getPackageProviders($app)
    {
        return [
            \MicroweberPackages\Helpers\HelpersServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'helpers' => \MicroweberPackages\Helpers\HelpersFacade::class
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
    }

}