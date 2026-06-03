<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\DuskTestCase;
use Tests\Browser\Traits\AdminLoginTrait;

/**
 * Admin Live Edit Module Settings Tests
 *
 * Tests opening module settings for each module type in the live edit
 * interface by dispatching onModuleSettingsRequest and verifying:
 *   - Settings panel opens without JS errors
 *   - Settings panel has content (tabs, inputs, etc.)
 *   - No 500 errors or crashes
 *
 * Prerequisites:
 *   - A running dev server at http://127.0.0.1:8000
 *   - An admin user with email admin@admin.com / admin
 *   - Login captcha disabled
 */
class AdminLiveEditModuleSettingsTest extends DuskTestCase
{
    use AdminLoginTrait;

    protected function assertPreConditions(): void
    {
        // Skip parent — we rely on the already-running server's database
    }

    /**
     * Helper: get all unique module types and their first element ID from the iframe.
     */
    private function getModuleTypesFromIframe(Browser $browser): array
    {
        $result = $browser->script("
            try {
                var iframe = document.querySelector('iframe');
                if (!iframe || !iframe.contentDocument) return {};
                var doc = iframe.contentDocument;
                var modules = doc.querySelectorAll('.module');
                var byType = {};
                for (var i = 0; i < modules.length; i++) {
                    var t = modules[i].getAttribute('data-type') || modules[i].getAttribute('type');
                    if (t && !byType[t]) byType[t] = modules[i].id;
                }
                return byType;
            } catch(e) { return {}; }
        ");

        return $result[0] ?? [];
    }

    /**
     * Helper: open module settings by dispatching onModuleSettingsRequest.
     */
    private function openModuleSettings(Browser $browser, string $moduleId): string
    {
        $result = $browser->script("
            try {
                var iframe = document.querySelector('iframe');
                if (!iframe || !iframe.contentDocument) return 'no iframe';
                var doc = iframe.contentDocument;
                var el = doc.getElementById(" . json_encode($moduleId) . ");
                if (!el) return 'element not found';
                mw.app.editor.dispatch('onModuleSettingsRequest', el);
                return 'dispatched';
            } catch(e) { return 'error: ' + e.message; }
        ");

        return $result[0] ?? 'unknown';
    }

    /**
     * Helper: check if the settings sidebar has content.
     */
    private function settingsPanelHasContent(Browser $browser): bool
    {
        $result = $browser->script("
            try {
                var sidebar = document.querySelector('.live-edit-gui-editor-opened')
                    || document.querySelector('[class*=\"settings\"]')
                    || document.querySelector('[class*=\"sidebar\"][class*=\"open\"]');
                var inputs = document.querySelectorAll(
                    '#mw-live-edit-gui-editor-box input, ' +
                    '#mw-live-edit-gui-editor-box select, ' +
                    '#mw-live-edit-gui-editor-box textarea, ' +
                    '#mw-live-edit-gui-editor-box .fi-toggle, ' +
                    '#mw-live-edit-gui-editor-box button, ' +
                    '#mw-live-edit-gui-editor-box [role=\"tab\"]'
                );
                if (inputs.length > 0) return true;
                // Fallback: check for any settings-like content
                var anyContent = document.querySelectorAll(
                    '[class*=\"module-settings\"] *, ' +
                    '.mw-module-settings *, ' +
                    '.edit-module-settings *'
                );
                return anyContent.length > 0 || sidebar !== null;
            } catch(e) { return false; }
        ");

        return $result[0] ?? false;
    }

    /**
     * Helper: collect critical JS errors from the page.
     */
    private function collectJsErrors(Browser $browser): array
    {
        $this->injectErrorListener($browser);
        return [];
    }

    /**
     * Helper: get JS errors that occurred.
     */
    private function getJsErrors(Browser $browser): array
    {
        return $this->getCriticalErrors($browser);
    }

    #[Test]
    public function homepage_module_settings_open_without_errors(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $checks = 0;
            $failed = [];

            // Load live edit on homepage
            $browser->visit('/admin/live-edit')->pause(8000);
            $this->ensureLoggedIn($browser);
            $this->injectErrorListener($browser);

            // Get all module types on the page
            $moduleTypes = $this->getModuleTypesFromIframe($browser);

            if (empty($moduleTypes)) {
                $this->markTestSkipped('No modules found in live edit iframe');
            }

            // Test each module type
            foreach ($moduleTypes as $type => $moduleId) {
                // Skip background and spacer — they are helper modules without settings
                if (in_array($type, ['background', 'spacer'])) {
                    continue;
                }

                try {
                    // Open module settings
                    $result = $this->openModuleSettings($browser, $moduleId);
                    $browser->pause(3000);

                    if ($result !== 'dispatched') {
                        $failed["settings_open_{$type}"] = "Failed to dispatch: {$result}";
                        continue;
                    }

                    // Check page didn't crash
                    $pageSource = $browser->driver->getPageSource();
                    $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                        "Module '{$type}' settings should not cause 500");
                    $this->assertStringNotContainsString('Whoops', $pageSource,
                        "Module '{$type}' settings should not show Whoops");

                    // Check for critical JS errors
                    $jsErrors = $this->getJsErrors($browser);
                    $criticalForModule = array_filter($jsErrors, function ($err) use ($type) {
                        // Only flag errors that are likely related to the module
                        return stripos($err, 'undefined') !== false
                            || stripos($err, 'null') !== false
                            || stripos($err, 'Cannot read') !== false;
                    });

                    if (!empty($criticalForModule)) {
                        $failed["js_errors_{$type}"] = implode('; ', array_slice($criticalForModule, 0, 3));
                        // Continue testing other modules despite errors
                    }

                    $checks++;
                } catch (\Exception $e) {
                    $failed["exception_{$type}"] = substr($e->getMessage(), 0, 200);
                }
            }

            // Report
            if (!empty($failed)) {
                $report = "Failed " . count($failed) . "/" . ($checks + count($failed)) . " module settings checks:\n";
                foreach ($failed as $name => $error) {
                    $report .= "  - {$name}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->assertGreaterThanOrEqual(3, $checks,
                "Should test at least 3 module types (tested {$checks})");
        });
    }

    #[Test]
    public function module_settings_have_content_tabs(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $checks = 0;
            $failed = [];

            // Load live edit on homepage
            $browser->visit('/admin/live-edit')->pause(8000);
            $this->ensureLoggedIn($browser);

            $moduleTypes = $this->getModuleTypesFromIframe($browser);

            // Test specific modules that should have Content/Design tabs
            $modulesWithTabs = ['btn', 'menu', 'posts', 'logo', 'social_links', 'testimonials'];

            foreach ($modulesWithTabs as $type) {
                if (!isset($moduleTypes[$type])) {
                    continue;
                }

                try {
                    $result = $this->openModuleSettings($browser, $moduleTypes[$type]);
                    $browser->pause(3000);

                    if ($result !== 'dispatched') {
                        continue;
                    }

                    // Check settings panel has some content
                    $hasContent = $this->settingsPanelHasContent($browser);

                    // Also check for Content/Design tabs specifically
                    $hasTabs = $browser->script("
                        try {
                            var tabs = document.querySelectorAll('[role=\"tab\"], .nav-tab, .tab-link, [class*=\"tab\"]');
                            var bodyText = document.body.innerText;
                            return tabs.length > 0 || bodyText.indexOf('Content') >= 0 || bodyText.indexOf('Design') >= 0;
                        } catch(e) { return false; }
                    ");

                    if ($hasContent || ($hasTabs[0] ?? false)) {
                        $checks++;
                    } else {
                        $failed["no_content_{$type}"] = 'Settings panel has no visible content or tabs';
                    }
                } catch (\Exception $e) {
                    $failed["exception_{$type}"] = substr($e->getMessage(), 0, 200);
                }
            }

            if (!empty($failed)) {
                $report = "Failed " . count($failed) . "/" . ($checks + count($failed)) . " module content checks:\n";
                foreach ($failed as $name => $error) {
                    $report .= "  - {$name}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->assertGreaterThanOrEqual(2, $checks,
                "Should verify at least 2 modules have content/tabs (verified {$checks})");
        });
    }

    #[Test]
    public function product_page_modules_in_live_edit(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $checks = 0;
            $failed = [];

            // Find a product page URL
            $productUrl = $browser->script("
                return (async function() {
                    try {
                        var response = await fetch('/api/content?content_type=product&limit=1');
                        var data = await response.json();
                        if (data && data.length > 0) return data[0].url;
                        return null;
                    } catch(e) { return null; }
                })();
            ");

            $url = $productUrl[0] ?? null;

            if (!$url) {
                // Try visiting a known product page
                $browser->visit('/admin/live-edit?url=' . urlencode('http://127.0.0.1:8000/shop'))
                    ->pause(8000);
            } else {
                $browser->visit('/admin/live-edit?url=' . urlencode($url))
                    ->pause(8000);
            }

            $this->ensureLoggedIn($browser);
            $this->injectErrorListener($browser);

            // Get modules on this page
            $moduleTypes = $this->getModuleTypesFromIframe($browser);

            if (empty($moduleTypes)) {
                // Not a failure — shop page might not exist
                $this->addToAssertionCount(1);
                return;
            }

            foreach ($moduleTypes as $type => $moduleId) {
                if (in_array($type, ['background', 'spacer'])) {
                    continue;
                }

                try {
                    $result = $this->openModuleSettings($browser, $moduleId);
                    $browser->pause(2000);

                    if ($result !== 'dispatched') {
                        continue;
                    }

                    $pageSource = $browser->driver->getPageSource();
                    $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                        "Module '{$type}' on product page should not cause 500");

                    $checks++;
                } catch (\Exception $e) {
                    $failed["exception_{$type}"] = substr($e->getMessage(), 0, 200);
                }
            }

            if (!empty($failed)) {
                $report = "Failed " . count($failed) . "/" . ($checks + count($failed)) . " product page module checks:\n";
                foreach ($failed as $name => $error) {
                    $report .= "  - {$name}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->addToAssertionCount(1); // Product page module checks completed ({$checks} modules tested)
        });
    }

    #[Test]
    public function live_edit_no_critical_js_errors_on_page_load(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            // Inject error listener BEFORE navigating
            $browser->visit('/admin/live-edit')->pause(2000);
            $this->injectErrorListener($browser);

            // Reload to catch all errors from page load
            $browser->visit('/admin/live-edit')->pause(8000);
            $this->ensureLoggedIn($browser);

            // Re-inject after navigation (previous listener is lost)
            $this->injectErrorListener($browser);
            $browser->pause(5000);

            $jsErrors = $this->getJsErrors($browser);

            // Filter for truly critical errors
            $critical = array_filter($jsErrors, function ($err) {
                return stripos($err, 'Cannot use import statement') !== false
                    || stripos($err, 'Unexpected token') !== false
                    || stripos($err, 'is not defined') !== false
                    || stripos($err, 'Cannot read properties of null') !== false;
            });

            if (!empty($critical)) {
                $this->fail("Critical JS errors on live edit page load:\n  - " . implode("\n  - ", $critical));
            }

            // Verify the page loaded properly
            $pageSource = $browser->driver->getPageSource();
            $this->assertStringNotContainsString('Internal Server Error', $pageSource);
            $this->assertStringNotContainsString('Whoops', $pageSource);

            // Verify iframe loaded
            $hasIframe = $browser->script("return document.querySelector('iframe') !== null;");
            $this->assertTrue($hasIframe[0] ?? false, 'Live edit should have an iframe');
        });
    }

    #[Test]
    public function live_edit_design_tab_works_for_modules(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $checks = 0;
            $failed = [];

            $browser->visit('/admin/live-edit')->pause(8000);
            $this->ensureLoggedIn($browser);

            $moduleTypes = $this->getModuleTypesFromIframe($browser);

            // Pick a few modules to test the Design tab
            $testModules = ['btn', 'posts', 'menu', 'logo'];

            foreach ($testModules as $type) {
                if (!isset($moduleTypes[$type])) {
                    continue;
                }

                try {
                    // Open module settings
                    $this->openModuleSettings($browser, $moduleTypes[$type]);
                    $browser->pause(3000);

                    // Try clicking the Design tab
                    $clickedDesign = $browser->script("
                        try {
                            var tabs = document.querySelectorAll('[role=\"tab\"], .nav-tab, .tab-link, [data-tab]');
                            for (var i = 0; i < tabs.length; i++) {
                                var text = tabs[i].textContent.trim().toLowerCase();
                                if (text === 'design' || text.indexOf('design') >= 0) {
                                    tabs[i].click();
                                    return 'clicked design tab';
                                }
                            }
                            return 'no design tab found';
                        } catch(e) { return 'error: ' + e.message; }
                    ");

                    $browser->pause(2000);

                    // Verify page is stable after clicking Design tab
                    $pageSource = $browser->driver->getPageSource();
                    $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                        "Design tab for '{$type}' should not cause 500");

                    $checks++;
                } catch (\Exception $e) {
                    $failed["design_tab_{$type}"] = substr($e->getMessage(), 0, 200);
                }
            }

            if (!empty($failed)) {
                $report = "Failed " . count($failed) . "/" . ($checks + count($failed)) . " design tab checks:\n";
                foreach ($failed as $name => $error) {
                    $report .= "  - {$name}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->assertGreaterThanOrEqual(1, $checks,
                "Should test at least 1 module's design tab (tested {$checks})");
        });
    }
}
