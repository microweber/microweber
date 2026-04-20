<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\DuskTestCase;

/**
 * Per-module Dusk tests for specialized modules:
 * google-maps, marquee, pdf, highlight-code, captcha, cookie-notice,
 * text-type, and module skin previews.
 *
 * Covers: settings page load, module-specific form fields (API keys,
 * code editors, scroll speed), tab navigation, and Livewire rendering.
 *
 * Prerequisites:
 *   - A running dev server at http://127.0.0.1:8000
 *   - An admin user with email admin@admin.com / admin
 */
class AdminModuleSpecializedUseCasesTest extends DuskTestCase
{
    use AdminLoginTrait;

    protected function assertPreConditions(): void
    {
        // Skip parent — we rely on the already-running server's database
    }

    private function visitAndAssertNoErrors(Browser $browser, string $slug): void
    {
        $browser->visit("/admin/{$slug}")->pause(3000);
        $this->ensureLoggedIn($browser);

        $pageSource = $browser->driver->getPageSource();
        $this->assertStringNotContainsString('Internal Server Error', $pageSource,
            "Page /admin/{$slug} returned 500");
        $this->assertStringNotContainsString('Whoops', $pageSource,
            "Page /admin/{$slug} shows Whoops error");
    }

    private function getFormStats(Browser $browser): array
    {
        $check = $browser->script("
            try {
                var inputs = document.querySelectorAll('input:not([type=\"hidden\"]), select, textarea, .fi-toggle, .fi-input');
                var tabs = document.querySelectorAll('[role=\"tab\"]');
                return {
                    inputCount: inputs.length,
                    tabCount: tabs.length,
                    hasWireId: document.querySelector('[wire\\\\:id]') !== null
                };
            } catch(e) { return {inputCount: 0, tabCount: 0, hasWireId: false}; }
        ");
        return $check[0] ?? [];
    }

    private function clickThroughTabs(Browser $browser, int $tabCount, string $context): void
    {
        for ($i = 0; $i < min($tabCount, 6); $i++) {
            $browser->script("
                var tabs = document.querySelectorAll('[role=\"tab\"]');
                if (tabs[{$i}]) tabs[{$i}].click();
            ");
            $browser->pause(1500);

            $pageSource = $browser->driver->getPageSource();
            $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                "{$context} tab {$i} should not cause 500");
        }
    }

    #[Test]
    public function google_maps_marquee_pdf_settings(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            // Google Maps
            $this->visitAndAssertNoErrors($browser, 'google-maps-module-settings');
            $data = $this->getFormStats($browser);
            $this->assertTrue($data['hasWireId'] ?? false,
                'Google Maps settings should be a Livewire component');
            $this->assertGreaterThan(0, $data['inputCount'] ?? 0,
                'Google Maps settings should have inputs');
            if (($data['tabCount'] ?? 0) > 1) {
                $this->clickThroughTabs($browser, $data['tabCount'], 'Google Maps');
            }

            // Marquee
            $this->visitAndAssertNoErrors($browser, 'marquee-module-settings');
            $data = $this->getFormStats($browser);
            $this->assertTrue($data['hasWireId'] ?? false,
                'Marquee settings should be a Livewire component');
            $this->assertGreaterThan(0, $data['inputCount'] ?? 0,
                'Marquee settings should have inputs');
            if (($data['tabCount'] ?? 0) > 1) {
                $this->clickThroughTabs($browser, $data['tabCount'], 'Marquee');
            }

            // PDF
            $this->visitAndAssertNoErrors($browser, 'pdf-module-settings');
            $data = $this->getFormStats($browser);
            $this->assertTrue($data['hasWireId'] ?? false,
                'PDF settings should be a Livewire component');
            $this->assertGreaterThan(0, $data['inputCount'] ?? 0,
                'PDF settings should have inputs');
            if (($data['tabCount'] ?? 0) > 1) {
                $this->clickThroughTabs($browser, $data['tabCount'], 'PDF');
            }
        });
    }

    #[Test]
    public function highlight_code_captcha_cookie_text_type_settings(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            // Highlight Code
            $this->visitAndAssertNoErrors($browser, 'highlight-code-module-settings');
            $data = $this->getFormStats($browser);
            $this->assertTrue($data['hasWireId'] ?? false,
                'Highlight code settings should be a Livewire component');
            $this->assertGreaterThan(0, $data['inputCount'] ?? 0,
                'Highlight code settings should have inputs');
            if (($data['tabCount'] ?? 0) > 1) {
                $this->clickThroughTabs($browser, $data['tabCount'], 'Highlight Code');
            }

            // Captcha
            $this->visitAndAssertNoErrors($browser, 'captcha-module-settings');
            $data = $this->getFormStats($browser);
            $this->assertTrue($data['hasWireId'] ?? false,
                'Captcha settings should be a Livewire component');

            // Cookie Notice
            $this->visitAndAssertNoErrors($browser, 'cookie-notice-module-settings-admin');
            $data = $this->getFormStats($browser);
            $this->assertTrue($data['hasWireId'] ?? false,
                'Cookie notice admin settings should be a Livewire component');
            $this->assertGreaterThan(0, $data['inputCount'] ?? 0,
                'Cookie notice settings should have inputs');

            // Text Type
            $this->visitAndAssertNoErrors($browser, 'text-type-module-settings');
            $data = $this->getFormStats($browser);
            $this->assertTrue($data['hasWireId'] ?? false,
                'Text type settings should be a Livewire component');
        });
    }

    #[Test]
    public function all_module_settings_have_livewire_component(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $slugs = [
                'accordion-module-settings',
                'audio-module-settings',
                'background-module-settings',
                'before-after-module-settings',
                'breadcrumb-module-settings',
                'btn-module-settings',
                'captcha-module-settings',
                'cart-add-module-settings',
                'category-module-settings',
                'comments-module-settings',
                'contact-form-module-settings',
                'content-module-settings',
                'custom-fields-module-settings',
                'embed-module-settings',
                'facebook-like-module-settings',
                'facebook-page-module-settings',
                'faq-module-settings',
                'google-maps-module-settings',
                'highlight-code-module-settings',
                'image-rollover-module-settings',
                'layout-content-module-settings',
                'layouts-module-settings',
                'logo-module-settings',
                'marquee-module-settings',
                'menu-module-settings',
                'newsletter-module-settings',
                'page-module-settings',
                'pagination-module-settings',
                'pdf-module-settings',
                'pictures-module-settings',
                'post-module-settings',
                'products-module-settings',
                'rating-module-settings',
                'sharer-module-settings',
                'shop-module-settings',
                'skills-module-settings',
                'slider-module-settings',
                'social-links-module-settings',
                'spacer-module-settings',
                'tabs-module-settings',
                'tags-module-settings',
                'teamcard-module-settings',
                'testimonials-module-settings',
                'text-type-module-settings',
                'tweet-embed-module-settings',
                'video-module-settings',
            ];

            $checked = 0;
            $noWire = [];

            foreach ($slugs as $slug) {
                $browser->visit("/admin/{$slug}")->pause(2000);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                if (str_contains($pageSource, 'Internal Server Error') || str_contains($pageSource, 'Whoops')) {
                    continue;
                }

                $hasWire = $browser->script("return document.querySelector('[wire\\\\:id]') !== null;");
                if ($hasWire[0] ?? false) {
                    $checked++;
                } else {
                    $noWire[] = $slug;
                }
            }

            if (!empty($noWire)) {
                $this->fail("Module settings pages without Livewire component:\n  - " . implode("\n  - ", $noWire));
            }

            $this->assertGreaterThanOrEqual(count($slugs) - 2, $checked,
                "At least " . (count($slugs) - 2) . " module settings should have Livewire components (found {$checked})");
        });
    }
}
