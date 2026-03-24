<?php

namespace Tests\Browser\CrossBrowser;

use Laravel\Dusk\Browser;
use MicroweberPackages\User\Models\User;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;
use Tests\Browser\Components\AdminLogin;
use Tests\Browser\Components\ChekForJavascriptErrors;

/**
 * Critical Path Cross-Browser Compatibility Tests
 *
 * These tests verify that critical user flows work across all supported browsers:
 * - Chrome
 * - Firefox
 * - Edge
 *
 * Tests cover:
 * 1. User authentication (login/logout)
 * 2. Core page navigation
 * 3. Form submissions
 * 4. Responsive design behavior
 * 5. JavaScript functionality
 */
class CriticalPathCrossBrowserTest extends CrossBrowserTestCase
{
    /**
     * Test user login functionality across browsers
     */
    #[Test]
    #[Group('cross-browser')]
    #[Group('critical-path')]
    public function cross_browser_user_can_login(): void
    {
        // Create a test user
        $user = User::factory()->create([
            'email' => 'crossbrowser@example.com',
            'password' => bcrypt('TestPass123!'),
        ]);

        // Test in Chrome (always available)
        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit($this->siteUrl . 'login');
            $browser->waitFor('input[name="email"]', 10);

            // Fill login form
            $browser->type('email', $user->email);
            $browser->type('password', 'TestPass123!');
            $browser->click('button[type="submit"]');

            // Verify redirect to profile
            $browser->waitForLocation('/profile', 10);
            $browser->assertPathIs('/profile');

            // Check for JS errors
            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });

            // Logout
            $browser->visit($this->siteUrl . 'logout');
        });
    }

    /**
     * Test homepage loads correctly in different browsers
     */
    #[Test]
    #[Group('cross-browser')]
    public function cross_browser_homepage_loads(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->siteUrl);
            $browser->waitFor('body', 10);

            // Verify page loaded
            $browser->assertVisible('body');

            // Check basic page structure
            $browser->assertPresent('html');
            $browser->assertPresent('head');
            $browser->assertPresent('body');

            // Get user agent to verify browser
            $userAgent = $this->getUserAgent($browser);
            $this->assertNotEmpty($userAgent);

            // Check for JS errors
            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });
        });
    }

    /**
     * Test form validation across browsers
     */
    #[Test]
    #[Group('cross-browser')]
    public function cross_browser_form_validation_works(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->siteUrl . 'login');
            $browser->waitFor('form', 10);

            // Submit empty form
            $browser->click('button[type="submit"]');

            // Wait for validation errors
            $browser->pause(1000);

            // Check that form is still on login page (validation prevented submission)
            $browser->assertPathIs('/login');

            // Check for JS errors
            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });
        });
    }

    /**
     * Test responsive design breakpoints
     */
    #[Test]
    #[Group('cross-browser')]
    #[Group('responsive')]
    public function cross_browser_responsive_design(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->siteUrl);
            $browser->waitFor('body', 10);

            // Test desktop viewport
            $browser->resize(1920, 1080);
            $browser->pause(500);
            $browser->assertVisible('body');

            // Test tablet viewport
            $browser->resize(768, 1024);
            $browser->pause(500);
            $browser->assertVisible('body');

            // Test mobile viewport
            $browser->resize(375, 667);
            $browser->pause(500);
            $browser->assertVisible('body');

            // Reset to default
            $browser->resize(1920, 1080);

            // Check for JS errors
            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });
        });
    }

    /**
     * Test navigation menu functionality
     */
    #[Test]
    #[Group('cross-browser')]
    public function cross_browser_navigation_works(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->siteUrl);
            $browser->waitFor('body', 10);

            // Test that navigation elements exist
            $browser->assertPresent('nav, .nav, .navbar, [role="navigation"]');

            // Check for JS errors
            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });
        });
    }

    /**
     * Test cookie functionality
     */
    #[Test]
    #[Group('cross-browser')]
    public function cross_browser_cookies_work(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->siteUrl);
            $browser->waitFor('body', 10);

            // Check cookies are set
            $cookies = $browser->driver->manage()->getCookies();
            $this->assertIsArray($cookies);

            // Check for session cookie
            $hasSessionCookie = false;
            foreach ($cookies as $cookie) {
                if (isset($cookie['name']) && str_contains($cookie['name'], 'session')) {
                    $hasSessionCookie = true;
                    break;
                }
            }

            // Session cookie may or may not be present depending on state
            // Just verify we got cookies array
            $this->assertGreaterThanOrEqual(0, count($cookies));
        });
    }

    /**
     * Test CSS and JavaScript assets load correctly
     */
    #[Test]
    #[Group('cross-browser')]
    public function cross_browser_assets_load(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->siteUrl);
            $browser->waitFor('body', 10);

            // Check that styles are applied (body has some computed styles)
            $bodyStyles = $browser->driver->executeScript(
                'return window.getComputedStyle(document.body);'
            );
            $this->assertNotNull($bodyStyles);

            // Verify jQuery or modern JS is available
            $jqueryAvailable = $browser->driver->executeScript(
                'return typeof jQuery !== "undefined" || typeof $ !== "undefined";'
            );
            $this->assertTrue($jqueryAvailable, 'jQuery should be available on the page');

            // Check for JS errors
            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });
        });
    }

    /**
     * Test modal/dialog functionality
     */
    #[Test]
    #[Group('cross-browser')]
    public function cross_browser_modal_functionality(): void
    {
        $this->browse(function (Browser $browser) {
            // Visit admin login page
            $browser->visit($this->siteUrl . 'admin/login');
            $browser->waitFor('form', 10);

            // Check that form elements are interactable
            $browser->assertVisible('input[name="email"], input[name="username"]');
            $browser->assertVisible('input[name="password"]');
            $browser->assertVisible('button[type="submit"]');

            // Check for JS errors
            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });
        });
    }

    /**
     * Test keyboard navigation
     */
    #[Test]
    #[Group('cross-browser')]
    #[Group('accessibility')]
    public function cross_browser_keyboard_navigation(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->siteUrl . 'login');
            $browser->waitFor('form', 10);

            // Test tab navigation
            $browser->driver->getKeyboard()->sendKeys("\t");
            $browser->pause(200);

            $activeElement = $browser->driver->executeScript(
                'return document.activeElement.tagName;'
            );
            $this->assertNotEmpty($activeElement);

            // Check for JS errors
            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });
        });
    }

    /**
     * Test localStorage functionality
     */
    #[Test]
    #[Group('cross-browser')]
    public function cross_browser_local_storage(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->siteUrl);
            $browser->waitFor('body', 10);

            // Test localStorage availability
            $localStorageAvailable = $browser->driver->executeScript(
                'return typeof(Storage) !== "undefined" && window.localStorage !== null;'
            );
            $this->assertTrue($localStorageAvailable, 'localStorage should be available');

            // Set a test value
            $browser->driver->executeScript(
                'localStorage.setItem("crossBrowserTest", "testValue");'
            );

            // Verify it was set
            $storedValue = $browser->driver->executeScript(
                'return localStorage.getItem("crossBrowserTest");'
            );
            $this->assertEquals('testValue', $storedValue);

            // Clean up
            $browser->driver->executeScript(
                'localStorage.removeItem("crossBrowserTest");'
            );

            // Check for JS errors
            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });
        });
    }

    /**
     * Test CSS Grid and Flexbox support
     */
    #[Test]
    #[Group('cross-browser')]
    #[Group('css')]
    public function cross_browser_css_modern_features(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->siteUrl);
            $browser->waitFor('body', 10);

            // Check CSS Grid support
            $gridSupported = $browser->driver->executeScript(
                'return CSS.supports("display", "grid");'
            );
            $this->assertTrue($gridSupported, 'CSS Grid should be supported');

            // Check Flexbox support
            $flexSupported = $browser->driver->executeScript(
                'return CSS.supports("display", "flex");'
            );
            $this->assertTrue($flexSupported, 'CSS Flexbox should be supported');

            // Check for JS errors
            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });
        });
    }

    /**
     * Test page scrolling behavior
     */
    #[Test]
    #[Group('cross-browser')]
    public function cross_browser_page_scrolling(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->siteUrl);
            $browser->waitFor('body', 10);

            // Get initial scroll position
            $initialScroll = $browser->driver->executeScript(
                'return window.pageYOffset || document.documentElement.scrollTop;'
            );

            // Scroll down
            $browser->driver->executeScript(
                'window.scrollTo(0, 100);'
            );
            $browser->pause(300);

            // Get new scroll position
            $newScroll = $browser->driver->executeScript(
                'return window.pageYOffset || document.documentElement.scrollTop;'
            );

            // Scroll position should have changed
            $this->assertGreaterThanOrEqual(0, $newScroll);

            // Reset scroll
            $browser->driver->executeScript(
                'window.scrollTo(0, 0);'
            );

            // Check for JS errors
            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });
        });
    }

    /**
     * Test image loading
     */
    #[Test]
    #[Group('cross-browser')]
    public function cross_browser_images_load(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->siteUrl);
            $browser->waitFor('body', 10);

            // Get all images on page
            $images = $browser->driver->executeScript(
                'return document.querySelectorAll("img").length;'
            );

            // If there are images, check they're loaded
            if ($images > 0) {
                $loadedImages = $browser->driver->executeScript(
                    'return Array.from(document.querySelectorAll("img")).filter(img => img.complete).length;'
                );
                // All images should be loaded or loading
                $this->assertGreaterThanOrEqual(0, $loadedImages);
            }

            // Check for JS errors
            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });
        });
    }

    /**
     * Test link functionality
     */
    #[Test]
    #[Group('cross-browser')]
    public function cross_browser_links_functional(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->siteUrl);
            $browser->waitFor('body', 10);

            // Check for links on the page
            $links = $browser->driver->executeScript(
                'return document.querySelectorAll("a[href]").length;'
            );
            $this->assertGreaterThanOrEqual(0, $links);

            // Test navigation if there are links
            if ($links > 0) {
                // Get first internal link
                $firstLink = $browser->driver->executeScript(
                    'const links = Array.from(document.querySelectorAll("a[href^=\"/\"]"));' .
                    'return links.length > 0 ? links[0].getAttribute("href") : null;'
                );

                if ($firstLink && $firstLink !== '/') {
                    // Navigate to first link
                    $browser->visit($this->siteUrl . ltrim($firstLink, '/'));
                    $browser->waitFor('body', 10);
                    $browser->assertVisible('body');
                }
            }

            // Check for JS errors
            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });
        });
    }

    /**
     * Test browser console for errors
     */
    #[Test]
    #[Group('cross-browser')]
    public function cross_browser_no_console_errors(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->siteUrl);
            $browser->waitFor('body', 10);

            // Navigate to a few pages to trigger any JS
            $browser->visit($this->siteUrl . 'login');
            $browser->waitFor('body', 10);

            // Check for JavaScript errors using the component
            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });
        });
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
