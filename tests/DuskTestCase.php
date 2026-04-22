<?php

namespace Tests;

use Facebook\WebDriver\Chrome\ChromeDevToolsDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use Laravel\Dusk\TestCase as BaseTestCase;
use MicroweberPackages\App\Managers\PermalinkManager;
use MicroweberPackages\Install\Http\Controllers\InstallController;
use MicroweberPackages\Multilanguage\MultilanguageHelpers;
use MicroweberPackages\Multilanguage\MultilanguagePermalinkManager;
use MicroweberPackages\User\Models\User;
use PHPUnit\Framework\Attributes\BeforeClass;
use Tests\Browser\Components\AdminMakeInstall;
use Tests\Browser\Components\BaseComponent;
use Tests\Browser\Components\ChekForJavascriptErrors;

abstract class DuskTestCase extends BaseTestCase
{
    public $siteUrl = 'http://127.0.0.1:8000/';

    public $template_name = 'Big2';

    /**
     * Tracks whether the shared chrome profile dir has been initialized
     * (and the process shutdown hook registered) for this PHP process.
     */
    protected static bool $sharedProfileInitialized = false;

    /**
     * Monotonically-increasing suffix for the shared chrome profile dir.
     * Incremented whenever we detect a dead chrome session so the next
     * browser starts with a clean, unlocked profile.
     */
    protected static int $sharedProfileSequence = 0;

    /**
     * Shared admin-login cache, accessed from {@see Browser\Traits\AdminLoginTrait}.
     * A single static on the base DuskTestCase makes the flag visible across
     * every child test class so one login covers the whole run.
     */
    protected static bool $adminLoggedIn = false;

    /**
     * Running count of Dusk tests that have entered createBrowsersFor.
     * Used to trigger a proactive browser recycle before chrome gets flaky
     * after long runs.
     */
    protected static int $testCount = 0;

    /**
     * Recycle the shared chrome process after this many tests. Empirically,
     * chrome becomes unstable after ~40 min of continuous use on this host,
     * so we pre-emptively bounce it to avoid mid-run SessionNotCreatedException.
     */
    protected static int $recycleEvery = 10;

    use CreatesApplication;


    protected function setUp(): void
    {

        if (!defined('MW_UNIT_TEST')) {
            define('MW_UNIT_TEST', true);
        }

        //  $_ENV['APP_ENV'] = 'testing';
        //  putenv('APP_ENV=testing');
        if (!defined('MW_SITE_URL')) {
            define('MW_SITE_URL', $this->siteUrl);
        }
        parent::setUp();

        if (!static::runningInSail()) {
            static::startChromeDriver([
                '--port=9515'
            ]);
        }

        //       \Illuminate\Support\Env::getRepository()->set('APP_ENV', 'testing');

    }


    /**
     * Create the RemoteWebDriver instance.
     *
     * One chrome instance is shared across all tests in a single `php artisan
     * dusk` run. A stable per-PID profile path means the same chrome process
     * (and its login cookies) are reused across test classes. The profile dir
     * is wiped at the start of each run and cleaned up at process exit.
     *
     * @return \Facebook\WebDriver\Remote\RemoteWebDriver
     */
    protected function driver()
    {
        $tempDir = $this->sharedChromeProfileDir();

        if (!self::$sharedProfileInitialized) {
            if (is_dir($tempDir)) {
                self::removeDirectory($tempDir);
            }
            @mkdir($tempDir, 0755, true);
            self::$sharedProfileInitialized = true;

            $pid = getmypid();
            static $shutdownRegistered = false;
            if (!$shutdownRegistered) {
                $shutdownRegistered = true;

                // Close chrome + wipe any shared-{pid}-* profile dirs on exit.
                register_shutdown_function(static function () use ($pid) {
                    foreach (static::$browsers as $browser) {
                        try {
                            $browser->quit();
                        } catch (\Throwable) {
                            // ignore
                        }
                    }
                    static::$browsers = collect();

                    $glob = storage_path('app/chrome-profiles/shared-' . $pid . '-*');
                    foreach (glob($glob) ?: [] as $dir) {
                        if (is_dir($dir)) {
                            self::removeDirectory($dir);
                        }
                    }
                });
            }
        }

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
        $arguments[] = '--user-data-dir=' . $tempDir;
        $arguments[] = '--crash-dumps-dir=' . $tempDir;

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
            //  $_ENV['DUSK_DRIVER_URL'] ?? 'http://localhost:4444/wd/hub',
            DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY, $options
            ), 30000, 30000
        );
    }

    /**
     * Determine whether the Dusk command has disabled headless mode.
     *
     * @return bool
     */
    protected function hasHeadlessDisabled(): bool
    {
        return isset($_SERVER['DUSK_HEADLESS_DISABLED']) ||
            isset($_ENV['DUSK_HEADLESS_DISABLED']);
    }

    protected function assertPreConditions(): void
    {


        $this->assertEquals('testing', \Illuminate\Support\Env::get('APP_ENV'));
        $this->assertEquals('testing', app()->environment());

        if (!mw_is_installed()) {
            // install
            $installController = new InstallController();

            // Basic installation parameters
            $installParams = [
                'make_install' => 1,
                'db_driver' => 'sqlite',
                'db_name' => storage_path('database.sqlite'),
                'db_username' => '',
                'db_password' => '',
                'db_prefix' => 'mw_test_',
                'admin_username' => 'admin',
                'admin_email' => 'admin@localhost',
                'admin_password' => 'admin123',
                'default_template' => $this->template_name,
                'with_default_content' => 1,
                'site_lang' => 'en_US'
            ];

            $installResult = $installController->index($installParams);

            if ($installResult !== 'done' && !is_array($installResult)) {
                throw new \Exception('Installation failed: ' . $installResult);
            }
        }


        if (mw_is_installed()) {

            if ($this->template_name) {

                save_option('current_template', $this->template_name, 'template');
            }

            save_option('dusk_test', 1, 'dusk');

            \MicroweberPackages\Multilanguage\MultilanguageHelpers::setMultilanguageEnabled(false);

            DB::table('options')
                ->where('option_group', 'multilanguage_settings')
                ->delete();



            if (MultilanguageHelpers::multilanguageIsEnabled()) {


                DB::table('multilanguage_translations')->truncate();
                DB::table('multilanguage_supported_locales')->truncate();


//                $option = array();
//                $option['option_value'] = '0';
//                $option['option_key'] = 'is_active';
//                $option['option_group'] = 'multilanguage_settings';
//                save_option($option);

                change_language_by_locale('en_US');
                save_option('language', 'en_US', 'website');

                if (app()->bound('multilanguage_repository')) {
                    app()->multilanguage_repository->clearCache();
                }

                if (app()->bound('option_repository')) {
                    app()->option_repository->clearCache();
                }

                //clearcache();

                $this->app->bind('permalink_manager', function () {
                    return new PermalinkManager();
                });
            }
        }

    }


    protected function grantPermission(Browser $browser, $permissions)
    {
        try {
            $driver = $browser->driver;
            $devtools = new ChromeDevToolsDriver($driver);

            $result = $devtools->execute('Browser.grantPermissions', [
                "permissions" => $permissions,
            ]);

            return $result;
        } catch (\Exception) {
            return null;
        }
    }

    /**
     * Override to detect dead browser sessions and replace them with fresh ones.
     * On dead-session detection we also rotate the chrome profile dir and drop
     * the shared login cache so the next browser starts clean.
     */
    protected function createBrowsersFor(\Closure $callback)
    {
        static::$testCount++;

        $needsReset = false;

        if (count(static::$browsers) > 0) {
            try {
                $browser = static::$browsers->first();
                $browser->driver->getWindowHandles();
            } catch (\Throwable) {
                $needsReset = true;
            }
        }

        // Proactive recycle: bounce chrome every N tests before it gets flaky.
        if (!$needsReset
            && static::$testCount > 1
            && (static::$testCount - 1) % static::$recycleEvery === 0
            && count(static::$browsers) > 0) {
            $needsReset = true;
        }

        if ($needsReset) {
            $this->resetSharedBrowserState();
        }

        return parent::createBrowsersFor($callback);
    }

    /**
     * Drop the shared browser/profile/login state so a fresh chrome can be
     * started. Used when the previous session died unexpectedly.
     */
    protected function resetSharedBrowserState(): void
    {
        foreach (static::$browsers as $browser) {
            try {
                $browser->quit();
            } catch (\Throwable) {
                // ignore — session is already dead
            }
        }
        static::$browsers = collect();

        // Kill any chrome processes still tied to the old profile dir.
        // Dead-session quits sometimes leave zombie chrome children that hold
        // file locks and accumulate memory over long runs.
        $oldDir = $this->sharedChromeProfileDir();
        @exec('pkill -9 -f ' . escapeshellarg('user-data-dir=' . $oldDir) . ' 2>/dev/null');
        usleep(200_000);

        // Wipe the old profile (may contain lock files) and rotate to a new one.
        if (is_dir($oldDir)) {
            self::removeDirectory($oldDir);
        }
        self::$sharedProfileSequence++;
        self::$sharedProfileInitialized = false;
        self::$adminLoggedIn = false;

        // If chromedriver itself has died (not just chrome), start a fresh one
        // that can bind to port 9515 before the next driver() call POSTs there.
        if (!$this->isChromeDriverResponding()) {
            if (static::$chromeProcess) {
                try {
                    static::$chromeProcess->stop(0);
                } catch (\Throwable) {
                    // ignore — already stopped
                }
                static::$chromeProcess = null;
            }
            static::startChromeDriver(['--port=9515']);

            // Wait for the new chromedriver to accept connections.
            for ($i = 0; $i < 20; $i++) {
                if ($this->isChromeDriverResponding()) {
                    break;
                }
                usleep(250_000);
            }
        }
    }

    /**
     * Check whether chromedriver is accepting TCP connections on port 9515.
     */
    protected function isChromeDriverResponding(): bool
    {
        $fp = @fsockopen('127.0.0.1', 9515, $errno, $errstr, 1);
        if ($fp) {
            fclose($fp);
            return true;
        }
        return false;
    }

    /**
     * Path to the shared chrome user-data-dir for this PHP process.
     */
    protected function sharedChromeProfileDir(): string
    {
        return storage_path(
            'app/chrome-profiles/shared-' . getmypid() . '-' . self::$sharedProfileSequence
        );
    }

    /**
     * Override to prevent dead sessions from crashing console log collection.
     */
    protected function storeConsoleLogsFor($browsers)
    {
        $browsers->each(function ($browser, $key) {
            try {
                $name = $this->getCallerName();
                $browser->storeConsoleLog($name . '-' . $key);
            } catch (\Exception) {
                // Session dead — skip console log storage
            }
        });
    }

    protected function captureFailuresFor($browsers)
    {
        $browsers->each(function ($browser, $key) {
            try {
                if (property_exists($browser, 'fitOnFailure') && $browser->fitOnFailure) {
                    $browser->fitContent();
                }
                $name = $this->getCallerName();
                $browser->screenshot('failure-' . $name . '-' . $key);
            } catch (\Exception) {
                // Session dead — skip failure screenshot
            }
        });
    }

    protected function storeSourceLogsFor($browsers)
    {
        $browsers->each(function ($browser, $key) {
            try {
                if (property_exists($browser, 'madeSourceAssertion') && $browser->madeSourceAssertion) {
                    $browser->storeSource($this->getCallerName() . '-' . $key);
                }
            } catch (\Exception) {
                // Session dead — skip source log storage
            }
        });
    }

    protected function assertPostConditions(): void
    {
        self::collectCoverage();
        parent::assertPostConditions();
    }

    public static function tearDownAfterClass(): void
    {
        self::collectCoverage();
        parent::tearDownAfterClass();
    }

    /**
     * Override Dusk's per-class teardown so the shared browser survives
     * between test classes. Browsers are closed at process exit by the
     * shutdown function registered in {@see driver()}.
     */
    #[\PHPUnit\Framework\Attributes\AfterClass]
    public static function tearDownDuskClass(): void
    {
        // Intentionally left blank — chrome is reused across the whole
        // `php artisan dusk` run.
    }

    private static function removeDirectory(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }

        $items = @scandir($dir) ?: [];
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            $path = $dir . DIRECTORY_SEPARATOR . $item;
            if (is_dir($path) && !is_link($path)) {
                self::removeDirectory($path);
            } else {
                @unlink($path);
            }
        }
        @rmdir($dir);
    }

    protected function tearDown(): void
    {
        self::collectCoverage();
        parent::tearDown();
    }

    public static function collectCoverage(): void
    {
        foreach (static::$browsers as $browser) {
            try {
                $window = collect($browser->driver->getWindowHandles())->last();
                $browser->driver->switchTo()->window($window);
                $coverage = $browser->driver->executeScript('return window.__coverage__');
                if ($coverage) {
                    self::saveCoverage($coverage);
                }
            } catch (\Exception) {
                // Session may have died — skip coverage collection
            }
        }
    }

    public static function saveCoverage($coverage)
    {
        BaseComponent::saveCoverage($coverage);
    }
}
