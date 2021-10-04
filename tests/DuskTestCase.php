<?php

namespace Tests;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Laravel\Dusk\TestCase as BaseTestCase;
use MicroweberPackages\App\LaravelApplication;
use MicroweberPackages\App\Providers\AppServiceProvider;
use MicroweberPackages\Config\ConfigSave;
use MicroweberPackages\Core\tests\TestCase;
use Orchestra\Testbench\Concerns\CreatesApplication;

abstract class DuskTestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function getBasePath()
    {
        return \realpath(__DIR__.'/../');
    }

    /**
     * Prepare for Dusk test execution.
     *
     * @beforeClass
     * @return void
     */
    public static function prepare()
    {
        $_SERVER['DUSK_HEADLESS_DISABLED'] = 1;

        if (! static::runningInSail()) {
            static::startChromeDriver();
        }
    }

    protected function getPackageProviders($app)
    {
        return [\MicroweberPackages\Config\ConfigSaveServiceProvider::class];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Config' => \MicroweberPackages\Config\ConfigSaveFacade::class
        ];
    }
    public function createApplication()
    {

        $app = $this->resolveApplication();
        //  $app->registerBaseServiceProviders();

        $this->resolveApplicationBindings($app);
        $this->resolveApplicationExceptionHandler($app);
        $this->resolveApplicationCore($app);
        $this->resolveApplicationConfiguration($app);
        $this->resolveApplicationHttpKernel($app);
        $this->resolveApplicationConsoleKernel($app);
        $this->resolveApplicationBootstrappers($app);
        $this->resolveApplicationBootstrappers($app);

        $is_installed = mw_is_installed();

        if (!$is_installed) {
            //   dump($is_installed);
            //   $app->commands('MicroweberPackages\Install\Console\Commands\InstallCommand');


            $tc = new TestCase();
            $tc->createApplication();
//            $input = array(
//                'db_host' => $this->argument('db_host'),
//                'db_name' => $this->argument('db_name'),
//                'db_user' => $this->argument('db_user'),
//                'db_pass' => $this->argument('db_pass'),
//                'db_driver' => $this->argument('db_driver'),
//                'table_prefix' => $this->option('prefix'),
//                'admin_email' => $this->argument('email'),
//                'admin_username' => $this->argument('username'),
//                'admin_password' => $this->argument('password'),
//                'with_default_content' => $this->option('default-content'),
//                'default_template' => $this->option('template'),
//                'config_only' => $this->option('config_only'),
//                'site_lang' => $this->option('language'),
//                '--env' => 'testing',
//            );
//
//
//         $install = \Artisan::call('microweber:install', $input);
        }



        return $app;
    }
    /**
     * Create the RemoteWebDriver instance.
     *
     * @return \Facebook\WebDriver\Remote\RemoteWebDriver
     */
    protected function driver()
    {
        $options = (new ChromeOptions)->addArguments(collect([
            '--window-size=1920,1080',
        ])->unless($this->hasHeadlessDisabled(), function ($items) {
            return $items->merge([
                '--disable-gpu',
                '--headless',
            ]);
        })->all());

        return RemoteWebDriver::create(
            $_ENV['DUSK_DRIVER_URL'] ?? 'http://localhost:9515',
            DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY, $options
            )
        );
    }

    /**
     * Determine whether the Dusk command has disabled headless mode.
     *
     * @return bool
     */
    protected function hasHeadlessDisabled()
    {
        return isset($_SERVER['DUSK_HEADLESS_DISABLED']) ||
            isset($_ENV['DUSK_HEADLESS_DISABLED']);
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.key', 'tQbgKF5NH5zMyGh4vCNypFAzx9trCkE6x');
    }
}
