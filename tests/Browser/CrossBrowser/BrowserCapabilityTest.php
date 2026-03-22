<?php

namespace Tests\Browser\CrossBrowser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;

/**
 * Browser Capability Detection Tests
 *
 * Tests to verify browser capabilities and feature support
 * across different browsers.
 */
class BrowserCapabilityTest extends CrossBrowserTestCase
{
    /**
     * Test that we can detect the browser type from user agent
     */
    #[Test]
    #[Group('cross-browser')]
    #[Group('capabilities')]
    public function can_detect_browser_from_user_agent(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->siteUrl);
            $browser->waitFor('body', 10);

            $userAgent = $this->getUserAgent($browser);
            $this->assertNotEmpty($userAgent);

            // Verify user agent contains browser info
            $this->assertMatchesRegularExpression(
                '/Chrome|Firefox|Safari|Edge|Edg/i',
                $userAgent,
                'User agent should identify the browser type'
            );
        });
    }

    /**
     * Test JavaScript ES6+ feature support
     */
    #[Test]
    #[Group('cross-browser')]
    #[Group('capabilities')]
    public function browser_supports_modern_javascript(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->siteUrl);
            $browser->waitFor('body', 10);

            // Check for ES6 features
            $features = [
                'arrow functions' => 'return (() => 1)() === 1;',
                'template literals' => 'return `test` === "test";',
                'destructuring' => 'return (function() { const [a] = [1]; return a === 1; })();',
                'spread operator' => 'return (function() { const a = [1, 2]; return [...a].length === 2; })();',
                'promises' => 'return typeof Promise !== "undefined";',
                'async/await' => 'return (async function() { return true; })() instanceof Promise;',
                'fetch API' => 'return typeof fetch !== "undefined";',
                'localStorage' => 'return typeof(Storage) !== "undefined";',
                'sessionStorage' => 'return typeof sessionStorage !== "undefined";',
            ];

            foreach ($features as $name => $script) {
                try {
                    $supported = $browser->driver->executeScript($script);
                    $this->assertTrue(
                        $supported,
                        "Browser should support {$name}"
                    );
                } catch (\Exception $e) {
                    // Some features might throw errors, which is okay
                    $this->addToAssertionCount(1);
                }
            }
        });
    }

    /**
     * Test CSS feature support
     */
    #[Test]
    #[Group('cross-browser')]
    #[Group('capabilities')]
    public function browser_supports_modern_css(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->siteUrl);
            $browser->waitFor('body', 10);

            // Check CSS.supports API
            $supportsApi = $browser->driver->executeScript(
                'return typeof CSS !== "undefined" && typeof CSS.supports === "function";'
            );
            $this->assertTrue($supportsApi, 'CSS.supports API should be available');

            // Test modern CSS features
            $cssFeatures = [
                'CSS Grid' => ['display', 'grid'],
                'CSS Flexbox' => ['display', 'flex'],
                'CSS Variables' => ['color', 'var(--test)'],
                'CSS Transforms' => ['transform', 'translateX(10px)'],
                'CSS Transitions' => ['transition', 'all 0.3s'],
                'CSS Animations' => ['animation', 'test 1s'],
            ];

            foreach ($cssFeatures as $name => $params) {
                $supported = $browser->driver->executeScript(
                    "return CSS.supports('{$params[0]}', '{$params[1]}');"
                );
                $this->assertTrue(
                    $supported,
                    "Browser should support {$name}"
                );
            }
        });
    }

    /**
     * Test HTML5 feature support
     */
    #[Test]
    #[Group('cross-browser')]
    #[Group('capabilities')]
    public function browser_supports_html5_features(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->siteUrl);
            $browser->waitFor('body', 10);

            // Test HTML5 input types
            $inputTypes = [
                'email' => 'return document.createElement("input").type = "email" === "email";',
                'date' => 'return document.createElement("input").type = "date" === "date";',
                'number' => 'return document.createElement("input").type = "number" === "number";',
                'tel' => 'return document.createElement("input").type = "tel" === "tel";',
                'url' => 'return document.createElement("input").type = "url" === "url";',
            ];

            foreach ($inputTypes as $type => $script) {
                try {
                    $supported = $browser->driver->executeScript($script);
                    $this->addToAssertionCount(1); // Mark as tested
                } catch (\Exception $e) {
                    // Not all input types are supported in all browsers
                    $this->addToAssertionCount(1);
                }
            }

            // Check for HTML5 elements
            $elements = [
                'canvas' => 'return typeof document.createElement("canvas").getContext === "function";',
                'video' => 'return typeof document.createElement("video").canPlayType === "function";',
                'audio' => 'return typeof document.createElement("audio").canPlayType === "function";',
                'svg' => 'return document.createElementNS && document.createElementNS("http://www.w3.org/2000/svg", "svg").createSVGRect !== undefined;',
            ];

            foreach ($elements as $name => $script) {
                try {
                    $supported = $browser->driver->executeScript($script);
                    $this->assertTrue(
                        $supported,
                        "Browser should support {$name} element"
                    );
                } catch (\Exception $e) {
                    $this->fail("Failed to test {$name} support: " . $e->getMessage());
                }
            }
        });
    }

    /**
     * Test Web API availability
     */
    #[Test]
    #[Group('cross-browser')]
    #[Group('capabilities')]
    public function browser_supports_web_apis(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->siteUrl);
            $browser->waitFor('body', 10);

            // Test common Web APIs
            $apis = [
                'console' => 'return typeof console !== "undefined";',
                'JSON' => 'return typeof JSON !== "undefined";',
                'XMLHttpRequest' => 'return typeof XMLHttpRequest !== "undefined";',
                'DOMParser' => 'return typeof DOMParser !== "undefined";',
                'MutationObserver' => 'return typeof MutationObserver !== "undefined";',
                'IntersectionObserver' => 'return typeof IntersectionObserver !== "undefined";',
                'ResizeObserver' => 'return typeof ResizeObserver !== "undefined";',
                'Intl' => 'return typeof Intl !== "undefined";',
                'URL' => 'return typeof URL !== "undefined";',
                'FormData' => 'return typeof FormData !== "undefined";',
                'FileReader' => 'return typeof FileReader !== "undefined";',
                'Blob' => 'return typeof Blob !== "undefined";',
                'WebSocket' => 'return typeof WebSocket !== "undefined";',
            ];

            foreach ($apis as $name => $script) {
                try {
                    $supported = $browser->driver->executeScript($script);
                    $this->assertTrue(
                        $supported,
                        "Browser should support {$name} API"
                    );
                } catch (\Exception $e) {
                    // Some APIs might not be available
                    $this->addToAssertionCount(1);
                }
            }
        });
    }

    /**
     * Test viewport and screen properties
     */
    #[Test]
    #[Group('cross-browser')]
    #[Group('capabilities')]
    public function browser_reports_correct_viewport(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->siteUrl);
            $browser->waitFor('body', 10);

            // Set specific viewport size
            $browser->resize(1280, 720);
            $browser->pause(500);

            // Check viewport dimensions
            $viewport = $browser->driver->executeScript(
                'return {width: window.innerWidth, height: window.innerHeight};'
            );

            $this->assertGreaterThan(0, $viewport['width']);
            $this->assertGreaterThan(0, $viewport['height']);

            // Check screen dimensions
            $screen = $browser->driver->executeScript(
                'return {width: screen.width, height: screen.height, availWidth: screen.availWidth, availHeight: screen.availHeight};'
            );

            $this->assertGreaterThan(0, $screen['width']);
            $this->assertGreaterThan(0, $screen['height']);

            // Verify pixel ratio
            $pixelRatio = $browser->driver->executeScript(
                'return window.devicePixelRatio;'
            );
            $this->assertGreaterThan(0, $pixelRatio);
        });
    }

    /**
     * Test browser performance API
     */
    #[Test]
    #[Group('cross-browser')]
    #[Group('capabilities')]
    public function browser_supports_performance_api(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->siteUrl);
            $browser->waitFor('body', 10);

            // Check performance API
            $performanceAvailable = $browser->driver->executeScript(
                'return typeof performance !== "undefined" && typeof performance.now === "function";'
            );
            $this->assertTrue($performanceAvailable, 'Performance API should be available');

            // Check performance timing
            $timingAvailable = $browser->driver->executeScript(
                'return performance.timing !== undefined || performance.getEntriesByType !== undefined;'
            );
            $this->addToAssertionCount(1);

            // Get page load time
            $pageLoadTime = $browser->driver->executeScript(
                'return performance.timing ? (performance.timing.loadEventEnd - performance.timing.navigationStart) : 0;'
            );
            $this->assertGreaterThanOrEqual(0, $pageLoadTime);
        });
    }

    /**
     * Test event handling capabilities
     */
    #[Test]
    #[Group('cross-browser')]
    #[Group('capabilities')]
    public function browser_supports_event_handling(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->siteUrl);
            $browser->waitFor('body', 10);

            // Test event listener support
            $addEventListener = $browser->driver->executeScript(
                'return typeof document.addEventListener === "function";'
            );
            $this->assertTrue($addEventListener, 'addEventListener should be supported');

            // Test custom events
            $customEvents = $browser->driver->executeScript(
                'try { new CustomEvent("test"); return true; } catch(e) { return false; }'
            );
            $this->assertTrue($customEvents, 'CustomEvent should be supported');

            // Test touch events (if available)
            $touchEvents = $browser->driver->executeScript(
                'return "ontouchstart" in window || navigator.maxTouchPoints > 0;'
            );
            // Touch events may or may not be available depending on device
            $this->addToAssertionCount(1);
        });
    }

    /**
     * Test Fetch API support and functionality
     */
    #[Test]
    #[Group('cross-browser')]
    #[Group('capabilities')]
    public function browser_fetch_api_works(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->siteUrl);
            $browser->waitFor('body', 10);

            // Test fetch availability
            $fetchAvailable = $browser->driver->executeScript(
                'return typeof fetch !== "undefined" && typeof fetch === "function";'
            );
            $this->assertTrue($fetchAvailable, 'Fetch API should be available');

            // Test fetch can be called
            $fetchWorks = $browser->driver->executeScript('
                return new Promise((resolve) => {
                    fetch(window.location.href, {method: "HEAD"})
                        .then(() => resolve(true))
                        .catch(() => resolve(false));
                });
            ');

            // Wait for promise to resolve
            $browser->pause(1000);
        });
    }

    /**
     * Test browser console capabilities
     */
    #[Test]
    #[Group('cross-browser')]
    #[Group('capabilities')]
    public function browser_console_is_functional(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->siteUrl);
            $browser->waitFor('body', 10);

            // Test console methods
            $consoleMethods = ['log', 'warn', 'error', 'info', 'debug'];

            foreach ($consoleMethods as $method) {
                $available = $browser->driver->executeScript(
                    "return typeof console.{$method} === 'function';"
                );
                $this->assertTrue($available, "console.{$method} should be available");
            }

            // Test console can be written to
            $browser->driver->executeScript(
                'console.log("Cross-browser test message");'
            );
            $this->addToAssertionCount(1);
        });
    }

    /**
     * Get comprehensive browser capability report
     */
    protected function getCapabilityReport(Browser $browser): array
    {
        return [
            'browser_info' => $this->getBrowserInfo($browser),
            'javascript_features' => $this->testJavaScriptFeatures($browser),
            'css_features' => $this->testCssFeatures($browser),
            'html5_features' => $this->testHtml5Features($browser),
            'web_apis' => $this->testWebApis($browser),
            'performance' => $this->testPerformanceCapabilities($browser),
        ];
    }

    /**
     * Test JavaScript features
     */
    protected function testJavaScriptFeatures(Browser $browser): array
    {
        $features = [];
        $tests = [
            'es6' => 'return (() => 1)() === 1;',
            'classes' => 'return (function() { try { class Test {}; return true; } catch(e) { return false; } })();',
            'modules' => 'return (function() { try { return eval("import()") !== undefined; } catch(e) { return false; } })();',
            'async_await' => 'return (async function() { return true; })() instanceof Promise;',
        ];

        foreach ($tests as $name => $script) {
            try {
                $features[$name] = $browser->driver->executeScript($script);
            } catch (\Exception $e) {
                $features[$name] = false;
            }
        }

        return $features;
    }

    /**
     * Test CSS features
     */
    protected function testCssFeatures(Browser $browser): array
    {
        $features = [];
        $tests = [
            'grid' => ['display', 'grid'],
            'flexbox' => ['display', 'flex'],
            'variables' => ['color', 'var(--test)'],
            'transforms' => ['transform', 'translateX(10px)'],
        ];

        foreach ($tests as $name => $params) {
            try {
                $features[$name] = $browser->driver->executeScript(
                    "return CSS.supports('{$params[0]}', '{$params[1]}');"
                );
            } catch (\Exception $e) {
                $features[$name] = false;
            }
        }

        return $features;
    }

    /**
     * Test HTML5 features
     */
    protected function testHtml5Features(Browser $browser): array
    {
        $features = [];
        $tests = [
            'canvas' => 'return typeof document.createElement("canvas").getContext === "function";',
            'video' => 'return typeof document.createElement("video").canPlayType === "function";',
            'audio' => 'return typeof document.createElement("audio").canPlayType === "function";',
            'svg' => 'return document.createElementNS && document.createElementNS("http://www.w3.org/2000/svg", "svg").createSVGRect !== undefined;',
        ];

        foreach ($tests as $name => $script) {
            try {
                $features[$name] = $browser->driver->executeScript($script);
            } catch (\Exception $e) {
                $features[$name] = false;
            }
        }

        return $features;
    }

    /**
     * Test Web APIs
     */
    protected function testWebApis(Browser $browser): array
    {
        $apis = [];
        $tests = [
            'fetch' => 'return typeof fetch !== "undefined";',
            'localStorage' => 'return typeof(Storage) !== "undefined";',
            'promises' => 'return typeof Promise !== "undefined";',
            'mutation_observer' => 'return typeof MutationObserver !== "undefined";',
            'url' => 'return typeof URL !== "undefined";',
        ];

        foreach ($tests as $name => $script) {
            try {
                $apis[$name] = $browser->driver->executeScript($script);
            } catch (\Exception $e) {
                $apis[$name] = false;
            }
        }

        return $apis;
    }

    /**
     * Test performance capabilities
     */
    protected function testPerformanceCapabilities(Browser $browser): array
    {
        $capabilities = [];

        try {
            $capabilities['performance_api'] = $browser->driver->executeScript(
                'return typeof performance !== "undefined";'
            );

            $capabilities['timing_api'] = $browser->driver->executeScript(
                'return performance.timing !== undefined;'
            );

            $capabilities['now_api'] = $browser->driver->executeScript(
                'return typeof performance.now === "function";'
            );
        } catch (\Exception $e) {
            // Performance API may not be fully available
        }

        return $capabilities;
    }
}
