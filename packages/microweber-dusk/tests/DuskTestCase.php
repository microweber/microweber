<?php

namespace MicroweberPackages\Dusk\Tests;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Laravel\Dusk\TestCase as BaseTestCase;
use Tests\CreatesApplication;

abstract class DuskTestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Base URL for the site under test.
     *
     * NOT hardcoded — resolved in setUp() from the app's configured URL
     * (config('app.url'), which reads APP_URL and defaults to http://localhost),
     * so the suite follows whatever host/port the app is actually served on.
     */
    public string $siteUrl = '';

    /**
     * Whether the admin login cookie is active for the shared browser session.
     */
    public static bool $adminLoggedIn = false;

    protected function setUp(): void
    {
        if (!defined('MW_UNIT_TEST')) {
            define('MW_UNIT_TEST', true);
        }

        parent::setUp();

        // Follow the app's configured URL (config('app.url') reads APP_URL and is
        // never empty — Laravel defaults it to http://localhost). No hardcoded host.
        $this->siteUrl = rtrim(config('app.url') ?: 'http://localhost', '/') . '/';

        // MW_SITE_URL is read at runtime by the site_url() helper (laravel-helper
        // -functions/url.php), so defining it here (post-boot) is fine.
        if (!defined('MW_SITE_URL')) {
            define('MW_SITE_URL', $this->siteUrl);
        }

        if (!static::runningInSail()) {
            static::startChromeDriver(['--port=9515']);
        }
    }

    /**
     * Base URL used by the browser (visit('/') etc.).
     *
     * Overrides Laravel Dusk's default to follow $this->siteUrl, which is derived
     * from the app's configured URL in setUp() — keeping visit() and the explicit
     * $this->siteUrl usages consistent on whatever host the app is served on.
     */
    protected function baseUrl(): string
    {
        // Fall back to config('app.url') so this is never empty even if it is
        // consulted before setUp() sets $siteUrl (config defaults to http://localhost).
        $url = $this->siteUrl ?: (config('app.url') ?: 'http://localhost');

        return rtrim($url, '/');
    }

    /**
     * Create the RemoteWebDriver instance.
     */
    protected function driver(): RemoteWebDriver
    {
        $arguments = [
            '--disable-gpu',
            '--no-sandbox',
            '--headless=new',
            '--disable-dev-shm-usage',
            '--window-size=1920,1080',
            '--disable-web-security',
            '--ignore-certificate-errors',
            '--disable-popup-blocking',
            '--disable-extensions',
            '--disable-infobars',
            '--disable-notifications',
            '--disable-default-apps',
            '--disable-translate',
            '--disable-save-password-bubble',
            '--metrics-recording-only',
        ];

        if ($this->hasHeadlessDisabled()) {
            $arguments = array_filter($arguments, fn($a) => !str_starts_with($a, '--headless'));
        }

        $options = (new ChromeOptions)->addArguments($arguments);

        $options->setExperimentalOption('prefs', [
            'download.default_directory'                 => storage_path('temp'),
            'credentials_enable_service'                 => 0,
            'profile.password_manager_enabled'           => 0,
            'profile.default_content_settings.popups'    => 0,
        ]);

        $options->setExperimentalOption('excludeSwitches', [
            'enable-logging',
        ]);

        return RemoteWebDriver::create(
            $_ENV['DUSK_DRIVER_URL'] ?? env('DUSK_DRIVER_URL', 'http://localhost:9515'),
            DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY, $options
            ),
            60000,
            60000
        );
    }

    protected function hasHeadlessDisabled(): bool
    {
        return isset($_SERVER['DUSK_HEADLESS_DISABLED'])
            || isset($_ENV['DUSK_HEADLESS_DISABLED']);
    }

    /**
     * Override to catch dead-session screenshot failures.
     */
    protected function captureFailuresFor($browsers): void
    {
        $browsers->each(function ($browser, $key) {
            try {
                $name = $this->getCallerName();
                $browser->screenshot('failure-' . $name . '-' . $key);
            } catch (\Exception) {
                // Session dead
            }
        });
    }

    protected function storeConsoleLogsFor($browsers): void
    {
        $browsers->each(function ($browser, $key) {
            try {
                $name = $this->getCallerName();
                $browser->storeConsoleLog($name . '-' . $key);
            } catch (\Exception) {
                // Session dead
            }
        });
    }
}
