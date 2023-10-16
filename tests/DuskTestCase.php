<?php

namespace Tests;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Auth;
use Laravel\Dusk\Browser;
use Laravel\Dusk\TestCase as BaseTestCase;
use MicroweberPackages\App\Managers\PermalinkManager;
use MicroweberPackages\Multilanguage\MultilanguageHelpers;
use MicroweberPackages\Multilanguage\MultilanguagePermalinkManager;
use MicroweberPackages\User\Models\User;
use Tests\Browser\Components\AdminMakeInstall;
use Tests\Browser\Components\ChekForJavascriptErrors;

abstract class DuskTestCase extends BaseTestCase
{
    public $siteUrl = 'http://127.0.0.1:8000/';

    use CreatesApplication;


    protected function setUp(): void
    {

        if (!defined('MW_UNIT_TEST')) {
            define('MW_UNIT_TEST', true);
        }

        $_ENV['APP_ENV'] = 'testing';
        putenv('APP_ENV=testing');
        parent::setUp();
    }

    /**
     * Prepare for Dusk test execution.
     *
     * @beforeClass
     * @return void
     */
    public static function prepare()
    {
        if (!static::runningInSail()) {
            static::startChromeDriver();
        }

        \Illuminate\Support\Env::getRepository()->set('APP_ENV', 'testing');


    }

    /**
     * Create the RemoteWebDriver instance.
     *
     * @return \Facebook\WebDriver\Remote\RemoteWebDriver
     */
    protected function driver()
    {
        $tempDir = storage_path('app/chrome-profiles/' . time() . rand(1, 1000));

        $arguments = [];
        $arguments[] = '--disable-web-security';
        $arguments[] = '--disable-xss-auditor';
        //$arguments[] = '--enable-devtools-experiments';
        $arguments[] = '--disable-gpu';
        $arguments[] = '--no-sandbox';
        $arguments[] = '--ignore-certificate-errors';
        $arguments[] = '--window-size=1280,1080';
        $arguments[] = '--disable-popup-blocking';
        $arguments[] = '--disable-dev-shm-usage';
      //  $arguments[] = '--user-data-dir=' . $tempDir;
     //   $arguments[] = '--crash-dumps-dir=' . $tempDir;

      //  $arguments[] = '--headless';
        //addArguments(`user-data-dir=${CURRENT_CHROMIUM_TMP_DIR}`);

        // chrome_options.add_experimental_option(
        //"prefs", {"credentials_enable_service": False, "profile.password_manager_enabled": False})

        $arguments[] = '--disable-extensions';
        $arguments[] = '--disable-infobars';
        $arguments[] = '--disable-notifications';
        $arguments[] = '--disable-default-apps';
        $arguments[] = '--disable-translate';
        $arguments[] = '--disable-save-password-bubble';
        $arguments[] = '--metrics-recording-only';
        $arguments[] = '--ash-no-nudges';


        $options = (new ChromeOptions)->addArguments(collect($arguments)
            ->unless($this->hasHeadlessDisabled(), function ($items) use ($arguments) {
                if (getenv('GITHUB_RUN_NUMBER')) {
                    $arguments[] = '--headless';
                }
                return $items->merge($arguments);

            })->all());

        $options->setExperimentalOption('prefs', [
            'download.default_directory' => storage_path('temp'),
            'credentials_enable_service' => 0,
            'profile.password_manager_enabled' => 0,
            'profile.default_content_settings.popups' => 0,
        ]);
        $options->setExperimentalOption('excludeSwitches', [
            'enable-logging',
        ]);



        return RemoteWebDriver::create(
           $_ENV['DUSK_DRIVER_URL'] ?? 'http://localhost:9515',
        //    $_ENV['DUSK_DRIVER_URL'] ?? 'http://localhost:4444/wd/hub',
            DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY, $options
            ), 90000, 90000
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

    protected function assertPreConditions(): void
    {





        $this->assertEquals('testing', \Illuminate\Support\Env::get('APP_ENV'));
        $this->assertEquals('testing', app()->environment());

        if (mw_is_installed()) {

            save_option('dusk_test', 1, 'dusk');


            //

            \MicroweberPackages\Multilanguage\MultilanguageHelpers::setMultilanguageEnabled(false);

            \DB::table('options')
                ->where('option_group', 'multilanguage_settings')
                ->delete();

            \DB::table('multilanguage_translations')->truncate();
            \DB::table('multilanguage_supported_locales')->truncate();

            app()->multilanguage_repository->clearCache();
            app()->option_repository->clearCache();
            clearcache();

            $this->app->bind('permalink_manager', function () {
                return new PermalinkManager();
            });
        }

    }


}
