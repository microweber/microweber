<?php

namespace Tests\Browser\CrossBrowser;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Firefox\FirefoxDriver;
use Facebook\WebDriver\Firefox\FirefoxProfile;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

/**
 * Cross-Browser Test Base Case
 *
 * Provides multi-browser support for cross-browser compatibility testing.
 * Supports Chrome, Firefox, Edge, and Safari (macOS only).
 */
abstract class CrossBrowserTestCase extends DuskTestCase
{
    /**
     * Available browsers for testing
     */
    protected array $supportedBrowsers = ['chrome', 'firefox', 'edge'];

    /**
     * Current browser being tested
     */
    protected string $currentBrowser = 'chrome';

    /**
     * Browser-specific capabilities configuration
     */
    protected array $browserConfig = [
        'chrome' => [
            'window_size' => [1920, 1080],
            'headless' => true,
            'arguments' => [
                '--disable-web-security',
                '--disable-gpu',
                '--no-sandbox',
                '--ignore-certificate-errors',
                '--window-size=1920,1080',
                '--disable-popup-blocking',
                '--disable-dev-shm-usage',
                '--disable-extensions',
                '--disable-infobars',
                '--disable-notifications',
                '--disable-default-apps',
                '--disable-translate',
                '--disable-save-password-bubble',
                '--metrics-recording-only',
            ],
        ],
        'firefox' => [
            'window_size' => [1920, 1080],
            'headless' => true,
            'preferences' => [
                'browser.download.folderList' => 2,
                'browser.download.manager.showWhenStarting' => false,
                'browser.download.dir' => '/tmp',
                'browser.helperApps.neverAsk.saveToDisk' => 'application/pdf,application/octet-stream',
                'security.ssl.enable_ocsp_stapling' => false,
                'security.cert_pinning.enforcement_level' => 0,
                'network.stricttransportsecurity.preloadlist' => false,
            ],
        ],
        'edge' => [
            'window_size' => [1920, 1080],
            'headless' => true,
            'arguments' => [
                '--disable-web-security',
                '--disable-gpu',
                '--no-sandbox',
                '--ignore-certificate-errors',
                '--window-size=1920,1080',
                '--disable-popup-blocking',
                '--disable-dev-shm-usage',
            ],
        ],
    ];

    /**
     * Set up the test environment for the specified browser
     */
    protected function setUpBrowser(string $browser): void
    {
        $this->currentBrowser = strtolower($browser);

        if (!in_array($this->currentBrowser, $this->supportedBrowsers)) {
            $this->markTestSkipped("Browser '{$browser}' is not supported or configured.");
        }

        // Check if browser driver is available
        if (!$this->isDriverAvailable($this->currentBrowser)) {
            $this->markTestSkipped("Driver for '{$browser}' is not available.");
        }
    }

    /**
     * Check if browser driver is available
     */
    protected function isDriverAvailable(string $browser): bool
    {
        return match ($browser) {
            'chrome' => $this->isChromeDriverAvailable(),
            'firefox' => $this->isFirefoxDriverAvailable(),
            'edge' => $this->isEdgeDriverAvailable(),
            default => false,
        };
    }

    /**
     * Check if ChromeDriver is available
     */
    protected function isChromeDriverAvailable(): bool
    {
        return !empty(shell_exec('which chromedriver 2>/dev/null')) ||
               !empty(shell_exec('which chromedriver.exe 2>/dev/null'));
    }

    /**
     * Check if GeckoDriver (Firefox) is available
     */
    protected function isFirefoxDriverAvailable(): bool
    {
        return !empty(shell_exec('which geckodriver 2>/dev/null')) ||
               !empty(shell_exec('which geckodriver.exe 2>/dev/null'));
    }

    /**
     * Check if EdgeDriver is available
     */
    protected function isEdgeDriverAvailable(): bool
    {
        return !empty(shell_exec('which msedgedriver 2>/dev/null')) ||
               !empty(shell_exec('which MicrosoftWebDriver.exe 2>/dev/null')) ||
               !empty(shell_exec('which edgedriver 2>/dev/null'));
    }

    /**
     * Create the RemoteWebDriver instance for the specified browser
     */
    protected function driver(): RemoteWebDriver
    {
        return match ($this->currentBrowser) {
            'firefox' => $this->createFirefoxDriver(),
            'edge' => $this->createEdgeDriver(),
            default => $this->createChromeDriver(),
        };
    }

    /**
     * Create Chrome driver instance
     */
    protected function createChromeDriver(): RemoteWebDriver
    {
        $config = $this->browserConfig['chrome'];
        $arguments = $config['arguments'];

        if ($config['headless'] && !$this->hasHeadlessDisabled()) {
            $arguments[] = '--headless=new';
        }

        $options = (new ChromeOptions)->addArguments($arguments);

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
            DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY, $options
            ),
            30000,
            30000
        );
    }

    /**
     * Create Firefox driver instance
     */
    protected function createFirefoxDriver(): RemoteWebDriver
    {
        $config = $this->browserConfig['firefox'];

        $profile = new FirefoxProfile();
        foreach ($config['preferences'] as $key => $value) {
            $profile->setPreference($key, $value);
        }

        $capabilities = DesiredCapabilities::firefox();
        $capabilities->setCapability(FirefoxDriver::PROFILE, $profile);

        if ($config['headless'] && !$this->hasHeadlessDisabled()) {
            $capabilities->setCapability('moz:firefoxOptions', [
                'args' => ['-headless'],
            ]);
        }

        return RemoteWebDriver::create(
            $_ENV['DUSK_DRIVER_URL'] ?? 'http://localhost:4444',
            $capabilities,
            30000,
            30000
        );
    }

    /**
     * Create Edge driver instance
     */
    protected function createEdgeDriver(): RemoteWebDriver
    {
        $config = $this->browserConfig['edge'];
        $arguments = $config['arguments'];

        if ($config['headless'] && !$this->hasHeadlessDisabled()) {
            $arguments[] = '--headless=new';
        }

        $options = (new ChromeOptions)->addArguments($arguments);

        $options->setExperimentalOption('prefs', [
            'download.default_directory' => storage_path('temp'),
            'credentials_enable_service' => 0,
            'profile.password_manager_enabled' => 0,
        ]);

        return RemoteWebDriver::create(
            $_ENV['DUSK_DRIVER_URL'] ?? 'http://localhost:9515',
            DesiredCapabilities::microsoftEdge()->setCapability(
                ChromeOptions::CAPABILITY, $options
            ),
            30000,
            30000
        );
    }

    /**
     * Run a test across multiple browsers
     *
     * @param array $browsers List of browsers to test
     * @param callable $callback Test callback receiving Browser instance
     */
    protected function browseMultiple(array $browsers, callable $callback): void
    {
        foreach ($browsers as $browserName) {
            $this->setUpBrowser($browserName);

            $this->browse(function (Browser $browser) use ($callback, $browserName) {
                // Set browser name for reporting
                $browser->driver->executeScript(
                    "document.body.setAttribute('data-test-browser', '{$browserName}')"
                );

                $callback($browser, $browserName);
            });
        }
    }

    /**
     * Get browser capabilities report
     */
    protected function getBrowserCapabilities(): array
    {
        $capabilities = [];

        foreach ($this->supportedBrowsers as $browser) {
            $capabilities[$browser] = [
                'available' => $this->isDriverAvailable($browser),
                'headless' => $this->browserConfig[$browser]['headless'] ?? true,
                'window_size' => $this->browserConfig[$browser]['window_size'] ?? [1920, 1080],
            ];
        }

        return $capabilities;
    }

    /**
     * Get user agent string for the current browser
     */
    protected function getUserAgent(Browser $browser): string
    {
        return $browser->driver->executeScript('return navigator.userAgent;');
    }

    /**
     * Assert that the current browser is supported
     */
    protected function assertBrowserSupported(string $browser): void
    {
        $this->assertContains(
            strtolower($browser),
            $this->supportedBrowsers,
            "Browser '{$browser}' is not in the supported browsers list."
        );
    }

    /**
     * Get browser information for reporting
     */
    protected function getBrowserInfo(Browser $browser): array
    {
        return [
            'user_agent' => $this->getUserAgent($browser),
            'viewport' => $browser->driver->executeScript(
                'return {width: window.innerWidth, height: window.innerHeight};'
            ),
            'screen_resolution' => $browser->driver->executeScript(
                'return {width: screen.width, height: screen.height};'
            ),
        ];
    }
}
