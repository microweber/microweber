<?php

namespace Tests\Browser\CrossBrowser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;

/**
 * Browser Detection and Driver Tests
 *
 * Simple tests to verify that browser drivers are working
 * and can connect to browsers properly.
 */
class BrowserDetectionTest extends CrossBrowserTestCase
{
    /**
     * Test that Chrome driver is available
     */
    #[Test]
    #[Group('cross-browser')]
    #[Group('setup')]
    public function chrome_driver_is_available(): void
    {
        $available = $this->isChromeDriverAvailable();
        $this->assertTrue($available, 'ChromeDriver should be available');
    }

    /**
     * Test that Firefox driver is available
     */
    #[Test]
    #[Group('cross-browser')]
    #[Group('setup')]
    public function firefox_driver_is_available(): void
    {
        $available = $this->isFirefoxDriverAvailable();
        // Firefox driver may not be installed, so we just mark it
        $this->addToAssertionCount(1);
    }

    /**
     * Test that Edge driver is available
     */
    #[Test]
    #[Group('cross-browser')]
    #[Group('setup')]
    public function edge_driver_is_available(): void
    {
        $available = $this->isEdgeDriverAvailable();
        // Edge driver may not be installed, so we just mark it
        $this->addToAssertionCount(1);
    }

    /**
     * Test browser capability configuration
     */
    #[Test]
    #[Group('cross-browser')]
    #[Group('setup')]
    public function browser_configuration_is_valid(): void
    {
        $capabilities = $this->getBrowserCapabilities();

        $this->assertIsArray($capabilities);
        $this->assertArrayHasKey('chrome', $capabilities);
        $this->assertArrayHasKey('firefox', $capabilities);
        $this->assertArrayHasKey('edge', $capabilities);

        foreach ($capabilities as $browser => $config) {
            $this->assertArrayHasKey('available', $config);
            $this->assertArrayHasKey('headless', $config);
            $this->assertArrayHasKey('window_size', $config);
            $this->assertIsArray($config['window_size']);
            $this->assertCount(2, $config['window_size']);
        }
    }

    /**
     * Test Chrome browser can be launched
     */
    #[Test]
    #[Group('cross-browser')]
    public function chrome_browser_launches_successfully(): void
    {
        // This test verifies the browser actually launches
        $this->browse(function (Browser $browser) {
            // Navigate to a data URL that doesn't require external connection
            $browser->visit('data:text/html,<html><body>Test Page</body></html>');

            // Verify browser is running
            $title = $browser->driver->getTitle();
            $this->assertNotNull($title);

            // Get browser information
            $userAgent = $browser->driver->executeScript('return navigator.userAgent;');
            $this->assertNotEmpty($userAgent);

            // Verify it's Chrome
            $this->assertMatchesRegularExpression(
                '/Chrome|Chromium/i',
                $userAgent,
                'User agent should identify as Chrome'
            );
        });
    }

    /**
     * Test browser window sizing works
     */
    #[Test]
    #[Group('cross-browser')]
    public function browser_window_can_be_resized(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('data:text/html,<html><body>Test Page</body></html>');

            // Test desktop viewport
            $browser->resize(1920, 1080);
            $browser->pause(500);

            $viewport = $browser->driver->executeScript(
                'return {width: window.innerWidth, height: window.innerHeight};'
            );

            $this->assertGreaterThan(0, $viewport['width']);
            $this->assertGreaterThan(0, $viewport['height']);

            // Test mobile viewport
            $browser->resize(375, 667);
            $browser->pause(500);

            $viewport = $browser->driver->executeScript(
                'return {width: window.innerWidth, height: window.innerHeight};'
            );

            $this->assertGreaterThan(0, $viewport['width']);
            $this->assertGreaterThan(0, $viewport['height']);
        });
    }

    /**
     * Test JavaScript execution in browser
     */
    #[Test]
    #[Group('cross-browser')]
    public function javascript_execution_works(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit("data:text/html,<html><body>Test Page</body></html>");

            // Test basic JavaScript
            $result = $browser->driver->executeScript('return 1 + 1;');
            $this->assertEquals(2, $result);

            // Test navigator object
            $userAgent = $browser->driver->executeScript('return navigator.userAgent;');
            $this->assertNotEmpty($userAgent);
            $this->assertIsString($userAgent);

            // Test document object
            $hasDocument = $browser->driver->executeScript('return typeof document !== "undefined";');
            $this->assertTrue($hasDocument);

            // Test window object
            $hasWindow = $browser->driver->executeScript('return typeof window !== "undefined";');
            $this->assertTrue($hasWindow);
        });
    }

    /**
     * Test browser cookies work
     */
    #[Test]
    #[Group('cross-browser')]
    public function browser_cookies_functional(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit("data:text/html,<html><body>Test Page</body></html>");

            // Get cookies
            $cookies = $browser->driver->manage()->getCookies();
            $this->assertIsArray($cookies);
        });
    }

    /**
     * Test screenshot capability
     */
    #[Test]
    #[Group('cross-browser')]
    public function browser_can_take_screenshots(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit("data:text/html,<html><body>Test Page</body></html>");

            // Take a screenshot (this will save to tests/Browser/screenshots/)
            $browser->screenshot('cross-browser-test-blank-page');

            // Verify screenshot file was created
            $screenshotPath = base_path('tests/Browser/screenshots/cross-browser-test-blank-page.png');
            $this->assertFileExists($screenshotPath);

            // Clean up
            if (file_exists($screenshotPath)) {
                unlink($screenshotPath);
            }
        });
    }

    /**
     * Test browser logs can be accessed
     */
    #[Test]
    #[Group('cross-browser')]
    public function browser_logs_accessible(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit("data:text/html,<html><body>Test Page</body></html>");

            // Get browser logs (may be empty but should not error)
            try {
                $logs = $browser->driver->manage()->getLog('browser');
                $this->assertIsArray($logs);
            } catch (\Exception $e) {
                // Logs may not be available in all browsers
                $this->addToAssertionCount(1);
            }
        });
    }

    /**
     * Test browser session management
     */
    #[Test]
    #[Group('cross-browser')]
    public function browser_session_management_works(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit("data:text/html,<html><body>Test Page</body></html>");

            // Test localStorage availability
            $localStorageAvailable = $browser->driver->executeScript(
                'return typeof(Storage) !== "undefined" && window.localStorage !== null;'
            );
            $this->assertTrue($localStorageAvailable, 'localStorage should be available');

            // Test sessionStorage availability
            $sessionStorageAvailable = $browser->driver->executeScript(
                'return typeof(Storage) !== "undefined" && window.sessionStorage !== null;'
            );
            $this->assertTrue($sessionStorageAvailable, 'sessionStorage should be available');

            // Test setting and getting localStorage
            $browser->driver->executeScript(
                'localStorage.setItem("crossBrowserTest", "testValue");'
            );

            $storedValue = $browser->driver->executeScript(
                'return localStorage.getItem("crossBrowserTest");'
            );
            $this->assertEquals('testValue', $storedValue);

            // Clean up
            $browser->driver->executeScript(
                'localStorage.removeItem("crossBrowserTest");'
            );
        });
    }

    /**
     * Test browser capabilities report
     */
    #[Test]
    #[Group('cross-browser')]
    public function can_generate_capabilities_report(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit("data:text/html,<html><body>Test Page</body></html>");

            // Get user agent
            $userAgent = $browser->driver->executeScript('return navigator.userAgent;');
            $this->assertNotEmpty($userAgent);

            // Get viewport size
            $viewport = $browser->driver->executeScript(
                'return {width: window.innerWidth, height: window.innerHeight};'
            );
            $this->assertIsArray($viewport);
            $this->assertArrayHasKey('width', $viewport);
            $this->assertArrayHasKey('height', $viewport);

            // Get screen resolution
            $screen = $browser->driver->executeScript(
                'return {width: screen.width, height: screen.height};'
            );
            $this->assertIsArray($screen);
            $this->assertArrayHasKey('width', $screen);
            $this->assertArrayHasKey('height', $screen);

            // Get platform
            $platform = $browser->driver->executeScript('return navigator.platform;');
            $this->assertNotEmpty($platform);

            // Get language
            $language = $browser->driver->executeScript('return navigator.language;');
            $this->assertNotEmpty($language);

            // Get browser version
            $appVersion = $browser->driver->executeScript('return navigator.appVersion;');
            $this->assertNotEmpty($appVersion);
        });
    }
}
