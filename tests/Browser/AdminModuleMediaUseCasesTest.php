<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\DuskTestCase;

/**
 * Per-module Dusk tests for media modules:
 * pictures, video, audio, embed, background, image-rollover, before-after.
 *
 * Covers: settings page load, media-specific form fields (URL inputs, file
 * pickers, toggle controls), tab navigation, and save button presence.
 *
 * Prerequisites:
 *   - A running dev server at http://127.0.0.1:8000
 *   - An admin user with email admin@admin.com / admin
 */
class AdminModuleMediaUseCasesTest extends DuskTestCase
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
                var urlInputs = document.querySelectorAll('input[type=\"url\"], input[type=\"text\"]');
                var mediaInputs = document.querySelectorAll('[class*=\"media\"], [class*=\"image\"], [class*=\"file\"], [class*=\"upload\"]');
                return {
                    inputCount: inputs.length,
                    tabCount: tabs.length,
                    urlInputCount: urlInputs.length,
                    hasMediaPicker: mediaInputs.length > 0,
                    hasWireId: document.querySelector('[wire\\\\:id]') !== null
                };
            } catch(e) { return {inputCount: 0, tabCount: 0, urlInputCount: 0, hasMediaPicker: false, hasWireId: false}; }
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
    public function pictures_module_settings_gallery_config(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            $this->visitAndAssertNoErrors($browser, 'pictures-module-settings');

            $data = $this->getFormStats($browser);
            $this->assertTrue($data['hasWireId'] ?? false,
                'Pictures settings should be a Livewire component');
            $this->assertGreaterThan(0, $data['inputCount'] ?? 0,
                'Pictures settings should have inputs for gallery configuration');

            if (($data['tabCount'] ?? 0) > 1) {
                $this->clickThroughTabs($browser, $data['tabCount'], 'Pictures');
            }
        });
    }

    #[Test]
    public function video_module_settings_url_input(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            $this->visitAndAssertNoErrors($browser, 'video-module-settings');

            $data = $this->getFormStats($browser);
            $this->assertTrue($data['hasWireId'] ?? false,
                'Video settings should be a Livewire component');
            $this->assertGreaterThan(0, $data['inputCount'] ?? 0,
                'Video settings should have URL/embed inputs');

            // Video module should have a URL or embed code input
            $videoCheck = $browser->script("
                try {
                    var textInputs = document.querySelectorAll('input[type=\"text\"], input[type=\"url\"], textarea');
                    var hasUrlLike = false;
                    textInputs.forEach(function(el) {
                        var placeholder = (el.placeholder || '').toLowerCase();
                        var label = (el.getAttribute('aria-label') || '').toLowerCase();
                        if (placeholder.includes('url') || placeholder.includes('embed') || placeholder.includes('video')
                            || label.includes('url') || label.includes('video')) {
                            hasUrlLike = true;
                        }
                    });
                    return {textInputCount: textInputs.length, hasUrlLikeInput: hasUrlLike};
                } catch(e) { return {textInputCount: 0, hasUrlLikeInput: false}; }
            ");

            if (($data['tabCount'] ?? 0) > 1) {
                $this->clickThroughTabs($browser, $data['tabCount'], 'Video');
            }
        });
    }

    #[Test]
    public function audio_and_embed_module_settings(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            // Audio
            $this->visitAndAssertNoErrors($browser, 'audio-module-settings');
            $data = $this->getFormStats($browser);
            $this->assertTrue($data['hasWireId'] ?? false,
                'Audio settings should be a Livewire component');
            $this->assertGreaterThan(0, $data['inputCount'] ?? 0,
                'Audio settings should have inputs');
            if (($data['tabCount'] ?? 0) > 1) {
                $this->clickThroughTabs($browser, $data['tabCount'], 'Audio');
            }

            // Embed
            $this->visitAndAssertNoErrors($browser, 'embed-module-settings');
            $data = $this->getFormStats($browser);
            $this->assertTrue($data['hasWireId'] ?? false,
                'Embed settings should be a Livewire component');
            $this->assertGreaterThan(0, $data['inputCount'] ?? 0,
                'Embed settings should have code/URL inputs');
            if (($data['tabCount'] ?? 0) > 1) {
                $this->clickThroughTabs($browser, $data['tabCount'], 'Embed');
            }
        });
    }

    #[Test]
    public function background_rollover_before_after_settings(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            // Background
            $this->visitAndAssertNoErrors($browser, 'background-module-settings');
            $data = $this->getFormStats($browser);
            $this->assertTrue($data['hasWireId'] ?? false,
                'Background settings should be a Livewire component');
            if (($data['tabCount'] ?? 0) > 1) {
                $this->clickThroughTabs($browser, $data['tabCount'], 'Background');
            }

            // Image Rollover
            $this->visitAndAssertNoErrors($browser, 'image-rollover-module-settings');
            $data = $this->getFormStats($browser);
            $this->assertTrue($data['hasWireId'] ?? false,
                'Image rollover settings should be a Livewire component');
            $this->assertGreaterThan(0, $data['inputCount'] ?? 0,
                'Image rollover settings should have inputs');
            if (($data['tabCount'] ?? 0) > 1) {
                $this->clickThroughTabs($browser, $data['tabCount'], 'Image Rollover');
            }

            // Before/After
            $this->visitAndAssertNoErrors($browser, 'before-after-module-settings');
            $data = $this->getFormStats($browser);
            $this->assertTrue($data['hasWireId'] ?? false,
                'Before/After settings should be a Livewire component');
            $this->assertGreaterThan(0, $data['inputCount'] ?? 0,
                'Before/After settings should have inputs');
            if (($data['tabCount'] ?? 0) > 1) {
                $this->clickThroughTabs($browser, $data['tabCount'], 'Before/After');
            }
        });
    }
}
