<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\DuskTestCase;

/**
 * Per-module Dusk tests exercising modules within the live edit context.
 *
 * Unlike the admin settings page tests, these tests:
 *   - Open the live edit page
 *   - Find modules in the iframe
 *   - Open their settings via onModuleSettingsRequest
 *   - Interact with settings (switch tabs, verify inputs)
 *   - Check for JS errors after interaction
 *
 * Prerequisites:
 *   - A running dev server at http://127.0.0.1:8000
 *   - An admin user with email admin@admin.com / admin
 */
class AdminModuleLiveEditUseCasesTest extends DuskTestCase
{
    use AdminLoginTrait;

    protected function assertPreConditions(): void
    {
        // Skip parent — we rely on the already-running server's database
    }

    /**
     * Get module types and their first element IDs from the live edit iframe.
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
     * Open module settings by dispatching onModuleSettingsRequest.
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
     * Count form elements in the settings panel.
     */
    private function getSettingsFormStats(Browser $browser): array
    {
        $result = $browser->script("
            try {
                var box = document.querySelector('#mw-live-edit-gui-editor-box') || document.body;
                var inputs = box.querySelectorAll('input:not([type=\"hidden\"]), select, textarea, .fi-toggle, .fi-input');
                var tabs = box.querySelectorAll('[role=\"tab\"]');
                var buttons = box.querySelectorAll('button[type=\"submit\"], button[wire\\\\:click]');
                var iframes = box.querySelectorAll('iframe');
                return {
                    inputCount: inputs.length,
                    tabCount: tabs.length,
                    buttonCount: buttons.length,
                    iframeCount: iframes.length
                };
            } catch(e) { return {inputCount: 0, tabCount: 0, buttonCount: 0, iframeCount: 0}; }
        ");
        return $result[0] ?? [];
    }

    /**
     * Click the Design tab in the settings panel if available.
     */
    private function clickDesignTab(Browser $browser): bool
    {
        $result = $browser->script("
            try {
                var tabs = document.querySelectorAll('[role=\"tab\"]');
                for (var i = 0; i < tabs.length; i++) {
                    var text = tabs[i].textContent.trim().toLowerCase();
                    if (text === 'design' || text.indexOf('design') >= 0) {
                        tabs[i].click();
                        return true;
                    }
                }
                return false;
            } catch(e) { return false; }
        ");
        return $result[0] ?? false;
    }

    #[Test]
    public function live_edit_open_settings_for_each_module_batch1(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            $browser->visit('/admin/live-edit')->pause(8000);
            $this->ensureLoggedIn($browser);
            $this->injectErrorListener($browser);

            $moduleTypes = $this->getModuleTypesFromIframe($browser);
            if (empty($moduleTypes)) {
                $this->markTestSkipped('No modules found in live edit iframe');
            }

            $checked = 0;
            $failed = [];

            // Test first half of discovered modules
            $modules = array_slice($moduleTypes, 0, intdiv(count($moduleTypes), 2) + 1, true);

            foreach ($modules as $type => $moduleId) {
                if (in_array($type, ['background', 'spacer'])) {
                    continue;
                }

                try {
                    $result = $this->openModuleSettings($browser, $moduleId);
                    $browser->pause(3000);

                    if ($result !== 'dispatched') {
                        continue; // skip non-dispatchable modules
                    }

                    // Verify the page didn't crash
                    $this->assertPageHasNoErrorMarkers($browser, "Module '{$type}' settings");

                    // Try switching to Design tab
                    $this->clickDesignTab($browser);
                    $browser->pause(1500);

                    $this->assertPageHasNoErrorMarkers($browser, "Design tab for '{$type}'");

                    $checked++;
                } catch (\Exception $e) {
                    $failed["{$type}"] = substr($e->getMessage(), 0, 200);
                }
            }

            if (!empty($failed)) {
                $report = "Failed " . count($failed) . " module settings in live edit:\n";
                foreach ($failed as $name => $error) {
                    $report .= "  - {$name}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->assertGreaterThanOrEqual(2, $checked,
                "Should test at least 2 modules in live edit (tested {$checked})");
        });
    }

    #[Test]
    public function live_edit_open_settings_for_each_module_batch2(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            $browser->visit('/admin/live-edit')->pause(8000);
            $this->ensureLoggedIn($browser);
            $this->injectErrorListener($browser);

            $moduleTypes = $this->getModuleTypesFromIframe($browser);
            if (empty($moduleTypes)) {
                $this->markTestSkipped('No modules found in live edit iframe');
            }

            $checked = 0;
            $failed = [];

            // Test second half of discovered modules
            $half = intdiv(count($moduleTypes), 2) + 1;
            $modules = array_slice($moduleTypes, $half, null, true);

            foreach ($modules as $type => $moduleId) {
                if (in_array($type, ['background', 'spacer'])) {
                    continue;
                }

                try {
                    $result = $this->openModuleSettings($browser, $moduleId);
                    $browser->pause(3000);

                    if ($result !== 'dispatched') {
                        continue;
                    }

                    $this->assertPageHasNoErrorMarkers($browser, "Module '{$type}' settings");

                    $this->clickDesignTab($browser);
                    $browser->pause(1500);

                    $this->assertPageHasNoErrorMarkers($browser, "Design tab for '{$type}'");

                    $checked++;
                } catch (\Exception $e) {
                    $failed["{$type}"] = substr($e->getMessage(), 0, 200);
                }
            }

            if (!empty($failed)) {
                $report = "Failed " . count($failed) . " module settings in live edit:\n";
                foreach ($failed as $name => $error) {
                    $report .= "  - {$name}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->assertGreaterThanOrEqual(1, $checked,
                "Should test at least 1 module in live edit batch 2 (tested {$checked})");
        });
    }

    #[Test]
    public function live_edit_module_settings_have_form_inputs(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            $browser->visit('/admin/live-edit')->pause(8000);
            $this->ensureLoggedIn($browser);

            $moduleTypes = $this->getModuleTypesFromIframe($browser);
            if (empty($moduleTypes)) {
                $this->markTestSkipped('No modules found in live edit iframe');
            }

            // Pick modules known to have rich settings panels
            $richModules = ['btn', 'menu', 'posts', 'logo', 'social_links'];
            $checked = 0;
            $empty = [];

            foreach ($richModules as $type) {
                if (!isset($moduleTypes[$type])) {
                    continue;
                }

                $result = $this->openModuleSettings($browser, $moduleTypes[$type]);
                $browser->pause(3000);

                if ($result !== 'dispatched') {
                    continue;
                }

                $stats = $this->getSettingsFormStats($browser);
                $inputCount = $stats['inputCount'] ?? 0;

                if ($inputCount > 0) {
                    $checked++;
                } else {
                    // Check if settings loaded in an iframe (Livewire component)
                    if (($stats['iframeCount'] ?? 0) > 0) {
                        $checked++;
                    } else {
                        $empty[] = $type;
                    }
                }
            }

            if (!empty($empty)) {
                $this->fail("Modules with empty settings panels in live edit:\n  - " . implode("\n  - ", $empty));
            }

            $this->assertGreaterThanOrEqual(2, $checked,
                "At least 2 rich modules should have form inputs (found {$checked})");
        });
    }

    #[Test]
    public function live_edit_shop_page_modules_open_settings(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            // Navigate to a shop/product page in live edit
            $browser->visit('/admin/live-edit?url=' . urlencode('http://127.0.0.1:8000/shop'))
                ->pause(8000);
            $this->ensureLoggedIn($browser);
            $this->injectErrorListener($browser);

            $moduleTypes = $this->getModuleTypesFromIframe($browser);

            if (empty($moduleTypes)) {
                // Shop page may not exist — not a failure
                $this->addToAssertionCount(1);
                return;
            }

            $checked = 0;
            $failed = [];

            foreach ($moduleTypes as $type => $moduleId) {
                if (in_array($type, ['background', 'spacer'])) {
                    continue;
                }

                try {
                    $result = $this->openModuleSettings($browser, $moduleId);
                    $browser->pause(2500);

                    if ($result !== 'dispatched') {
                        continue;
                    }

                    $this->assertPageHasNoErrorMarkers($browser, "Module '{$type}' on shop page");

                    $checked++;
                } catch (\Exception $e) {
                    $failed["{$type}"] = substr($e->getMessage(), 0, 200);
                }
            }

            if (!empty($failed)) {
                $report = "Failed " . count($failed) . " shop page module checks:\n";
                foreach ($failed as $name => $error) {
                    $report .= "  - {$name}: {$error}\n";
                }
                $this->fail($report);
            }

            // If we reached here with no failures, all tested modules passed
            $this->addToAssertionCount(max(1, $checked));
        });
    }

    #[Test]
    public function live_edit_no_js_errors_after_module_interactions(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            $browser->visit('/admin/live-edit')->pause(8000);
            $this->ensureLoggedIn($browser);
            $this->injectErrorListener($browser);

            $moduleTypes = $this->getModuleTypesFromIframe($browser);
            if (empty($moduleTypes)) {
                $this->markTestSkipped('No modules found in live edit iframe');
            }

            // Open settings for up to 5 modules and check for accumulated JS errors
            $count = 0;
            foreach ($moduleTypes as $type => $moduleId) {
                if (in_array($type, ['background', 'spacer']) || $count >= 5) {
                    continue;
                }

                $this->openModuleSettings($browser, $moduleId);
                $browser->pause(2000);
                $count++;
            }

            // Collect JS errors after all interactions
            $jsErrors = $this->getCriticalErrors($browser);

            $critical = array_filter($jsErrors, function ($err) {
                return stripos($err, 'Cannot use import statement') !== false
                    || stripos($err, 'Unexpected token') !== false
                    || stripos($err, 'is not defined') !== false;
            });

            if (!empty($critical)) {
                $this->fail("Critical JS errors after module interactions:\n  - " . implode("\n  - ", array_slice($critical, 0, 5)));
            }

            $this->addToAssertionCount(1); // No critical JS errors after interacting with modules
        });
    }
}
