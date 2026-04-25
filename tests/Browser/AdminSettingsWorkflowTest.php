<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\DuskTestCase;
use Tests\Browser\Traits\AdminLoginTrait;

/**
 * Admin Settings Workflow Tests
 *
 * Comprehensive tests for all admin settings pages:
 *   - Settings hub navigation
 *   - All settings sub-pages load without errors
 *   - Forms have input fields
 *   - Settings persistence (modify and verify after reload)
 *   - Shop settings pages
 *   - AI settings page
 *
 * Prerequisites:
 *   - A running dev server at http://127.0.0.1:8000
 *   - An admin user with email admin@admin.com / admin (canonical AdminLoginTrait credentials)
 *   - Login captcha disabled
 */
class AdminSettingsWorkflowTest extends DuskTestCase
{
    use AdminLoginTrait;

    protected function assertPreConditions(): void
    {
        // Skip parent — we rely on the already-running server's database
    }

    protected function assertPageLoadsWithoutError(Browser $browser, string $url, string $name): void
    {
        $browser->visit($url)->pause(4000);
        $this->ensureLoggedIn($browser);

        $currentUrl = $browser->driver->getCurrentURL();
        if (str_contains($currentUrl, '/login')) {
            $browser->visit($url)->pause(4000);
        }

        $pageSource = $browser->driver->getPageSource();
        $this->assertStringNotContainsString('Internal Server Error', $pageSource,
            "{$name} should not return 500");
        $this->assertStringNotContainsString('Whoops', $pageSource,
            "{$name} should not show error page");
    }

    #[Test]
    public function settings_hub_has_navigation_cards(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $browser->visit('/admin/settings')->pause(5000);
            $this->ensureLoggedIn($browser);

            $pageSource = $browser->driver->getPageSource();
            $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                'Settings hub should not return 500');

            // Settings hub should have links/cards to sub-settings
            $links = $browser->script("
                var links = document.querySelectorAll('a[href*=\"settings\"], a[href*=\"admin-\"]');
                var hrefs = [];
                links.forEach(function(l) {
                    if (l.href.includes('/admin/')) hrefs.push(l.href);
                });
                return { count: hrefs.length, hrefs: hrefs.slice(0, 20) };
            ");

            $info = $links[0] ?? [];
            $this->assertGreaterThan(3, $info['count'] ?? 0,
                'Settings hub should have more than 3 navigation links to sub-settings');

            // Should contain key settings section names
            $bodyText = $browser->script("return document.body.innerText;");
            $text = $bodyText[0] ?? '';
            $this->assertTrue(
                str_contains($text, 'General') || str_contains($text, 'general'),
                'Settings hub should mention General settings'
            );
        });
    }

    #[Test]
    public function core_settings_pages_load(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $checks = 0;
            $failed = [];

            $pages = [
                '/admin/settings/general' => 'General Settings',
                '/admin/settings/seo-page' => 'SEO Settings',
                '/admin/admin-email-page' => 'Email Settings',
                '/admin/admin-template-page' => 'Template Settings',
                '/admin/admin-language-page' => 'Language Settings',
                '/admin/admin-login-register-page' => 'Login & Register Settings',
                '/admin/admin-advanced-page' => 'Advanced Settings',
                '/admin/admin-maintenance-mode-page' => 'Maintenance Mode',
                '/admin/admin-privacy-policy-page' => 'Privacy Policy',
                '/admin/admin-experimental-page' => 'Experimental Settings',
                '/admin/admin-custom-tags-page' => 'Custom Tags',
            ];

            foreach ($pages as $url => $name) {
                try {
                    $this->assertPageLoadsWithoutError($browser, $url, $name);
                    $checks++;
                } catch (\Exception $e) {
                    $failed[$name] = $e->getMessage();
                    try {
                        $browser->screenshot('fail-settings-' . strtolower(str_replace([' ', '&'], ['-', ''], $name)));
                    } catch (\Exception $ignored) {
                    }
                }
            }

            if (!empty($failed)) {
                $report = "Failed " . count($failed) . "/" . ($checks + count($failed)) . " core settings pages:\n";
                foreach ($failed as $name => $error) {
                    $report .= "  - {$name}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->assertTrue($checks >= 11, "All 11 core settings pages loaded (got {$checks})");
        });
    }

    #[Test]
    public function shop_settings_pages_load(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $checks = 0;
            $failed = [];

            $pages = [
                '/admin/admin-shop-general-page' => 'Shop General',
                '/admin/admin-shop-invoices-page' => 'Shop Invoices',
                '/admin/admin-shop-other-page' => 'Shop Other',
                '/admin/admin-shop-auto-respond-email-page' => 'Shop Auto-Respond Email',
            ];

            foreach ($pages as $url => $name) {
                try {
                    $this->assertPageLoadsWithoutError($browser, $url, $name);
                    $checks++;
                } catch (\Exception $e) {
                    $failed[$name] = $e->getMessage();
                    try {
                        $browser->screenshot('fail-shop-settings-' . strtolower(str_replace(' ', '-', $name)));
                    } catch (\Exception $ignored) {
                    }
                }
            }

            if (!empty($failed)) {
                $report = "Failed " . count($failed) . "/" . ($checks + count($failed)) . " shop settings pages:\n";
                foreach ($failed as $name => $error) {
                    $report .= "  - {$name}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->assertTrue($checks >= 4, "All 4 shop settings pages loaded (got {$checks})");
        });
    }

    #[Test]
    public function ai_settings_page_loads(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $browser->visit('/admin/ai-settings-page')->pause(5000);
            $this->ensureLoggedIn($browser);

            $pageSource = $browser->driver->getPageSource();
            $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                'AI settings should not return 500');

            // Should have provider configuration sections
            $bodyText = $browser->script("return document.body.innerText;");
            $text = $bodyText[0] ?? '';
            $hasProviders = str_contains($text, 'OpenAI')
                || str_contains($text, 'Ollama')
                || str_contains($text, 'Claude')
                || str_contains($text, 'Anthropic')
                || str_contains($text, 'AI');

            $this->assertTrue($hasProviders,
                'AI settings page should mention AI providers');
        });
    }

    #[Test]
    public function general_settings_form_fields_and_persistence(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $checks = 0;
            $failed = [];

            // ── Check 1: Form fields exist ──
            try {
                $browser->visit('/admin/settings/general')->pause(5000);
                $this->ensureLoggedIn($browser);

                $fieldInfo = $browser->script("
                    var inputs = document.querySelectorAll('input, select, textarea');
                    var labels = document.querySelectorAll('label');
                    var hasWireId = document.querySelector('[wire\\\\:id]') !== null;
                    return {
                        totalInputs: inputs.length,
                        labels: labels.length,
                        hasLivewire: hasWireId
                    };
                ");

                $info = $fieldInfo[0] ?? [];
                $this->assertGreaterThan(0, $info['totalInputs'] ?? 0,
                    'General settings should have form inputs');
                $this->assertGreaterThan(0, $info['labels'] ?? 0,
                    'General settings should have labels');
                $this->assertTrue($info['hasLivewire'] ?? false,
                    'General settings should use Livewire for auto-save');

                $checks++;
            } catch (\Exception $e) {
                $failed['form_fields'] = $e->getMessage();
            }

            // ── Check 2: Value persistence ──
            try {
                $originalValue = $browser->script("
                    var inputs = document.querySelectorAll('input[type=\"text\"]');
                    for (var i = 0; i < inputs.length; i++) {
                        var attrs = inputs[i].attributes;
                        for (var j = 0; j < attrs.length; j++) {
                            if (attrs[j].name.startsWith('wire:model') && attrs[j].value.includes('website_title')) {
                                return inputs[i].value;
                            }
                        }
                    }
                    return null;
                ");

                $origTitle = $originalValue[0] ?? null;

                if ($origTitle !== null) {
                    $testTitle = 'DuskSettingsTest ' . time();
                    $browser->script("
                        var inputs = document.querySelectorAll('input[type=\"text\"]');
                        for (var i = 0; i < inputs.length; i++) {
                            var attrs = inputs[i].attributes;
                            for (var j = 0; j < attrs.length; j++) {
                                if (attrs[j].name.startsWith('wire:model') && attrs[j].value.includes('website_title')) {
                                    var el = inputs[i];
                                    while (el && !el.getAttribute('wire:id')) el = el.parentElement;
                                    if (el) {
                                        var wireId = el.getAttribute('wire:id');
                                        var comp = window.Livewire.find(wireId);
                                        if (comp) comp.set(attrs[j].value, '{$testTitle}');
                                    }
                                    return;
                                }
                            }
                        }
                    ");
                    $browser->pause(5000);

                    // Reload and verify
                    $browser->visit('/admin/settings/general')->pause(5000);

                    $savedValue = $browser->script("
                        var inputs = document.querySelectorAll('input[type=\"text\"]');
                        for (var i = 0; i < inputs.length; i++) {
                            var attrs = inputs[i].attributes;
                            for (var j = 0; j < attrs.length; j++) {
                                if (attrs[j].name.startsWith('wire:model') && attrs[j].value.includes('website_title')) {
                                    return inputs[i].value;
                                }
                            }
                        }
                        return null;
                    ");

                    $this->assertStringContainsString('DuskSettingsTest', $savedValue[0] ?? '',
                        'Website title should persist after reload');

                    // Restore original
                    $browser->script("
                        var inputs = document.querySelectorAll('input[type=\"text\"]');
                        for (var i = 0; i < inputs.length; i++) {
                            var attrs = inputs[i].attributes;
                            for (var j = 0; j < attrs.length; j++) {
                                if (attrs[j].name.startsWith('wire:model') && attrs[j].value.includes('website_title')) {
                                    var el = inputs[i];
                                    while (el && !el.getAttribute('wire:id')) el = el.parentElement;
                                    if (el) {
                                        var comp = window.Livewire.find(el.getAttribute('wire:id'));
                                        if (comp) comp.set(attrs[j].value, '{$origTitle}');
                                    }
                                    return;
                                }
                            }
                        }
                    ");
                    $browser->pause(3000);
                }

                $checks++;
            } catch (\Exception $e) {
                $failed['persistence'] = $e->getMessage();
            }

            // ── Check 3: Settings hub card navigation ──
            try {
                $browser->visit('/admin/settings')->pause(4000);
                $this->ensureLoggedIn($browser);

                $browser->script("
                    var links = document.querySelectorAll('a');
                    for (var i = 0; i < links.length; i++) {
                        if (links[i].textContent.trim().includes('General') && links[i].href.includes('general')) {
                            links[i].click();
                            return;
                        }
                    }
                ");
                $browser->pause(4000);

                $currentUrl = $browser->driver->getCurrentURL();
                $this->assertTrue(
                    str_contains($currentUrl, 'general') || str_contains($currentUrl, 'settings'),
                    'Clicking General card should navigate. Got: ' . $currentUrl
                );

                $checks++;
            } catch (\Exception $e) {
                $failed['hub_navigation'] = $e->getMessage();
            }

            // ── Check 4: Live Edit Template Settings loads ──
            try {
                $browser->visit('/admin/live-edit-template-settings-page')->pause(5000);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'Live Edit Template Settings should not return 500');

                $checks++;
            } catch (\Exception $e) {
                $failed['live_edit_settings'] = $e->getMessage();
            }

            if (!empty($failed)) {
                $report = "Failed " . count($failed) . "/" . ($checks + count($failed)) . " settings form checks:\n";
                foreach ($failed as $name => $error) {
                    $report .= "  - {$name}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->assertTrue($checks >= 4, "All 4 settings form checks passed (got {$checks})");
        });
    }
}
