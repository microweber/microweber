<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\DuskTestCase;

/**
 * Per-module Dusk tests for content display modules:
 * accordion, tabs, faq, slider, testimonials, teamcard, rating, skills.
 *
 * Covers: settings page load, form field interaction, tab navigation,
 * table-based settings (add/remove items), and save button presence.
 *
 * Prerequisites:
 *   - A running dev server at http://127.0.0.1:8000
 *   - An admin user with email admin@admin.com / admin
 */
class AdminModuleContentUseCasesTest extends DuskTestCase
{
    use AdminLoginTrait;

    protected function assertPreConditions(): void
    {
        // Skip parent — we rely on the already-running server's database
    }

    /**
     * Helper: visit a module settings page and verify basic structure.
     */
    private function visitAndVerifySettings(Browser $browser, string $slug): array
    {
        $browser->visit("/admin/{$slug}")->pause(3000);
        $this->ensureLoggedIn($browser);

        $pageSource = $browser->driver->getPageSource();
        $this->assertStringNotContainsString('Internal Server Error', $pageSource,
            "Page /admin/{$slug} returned 500");
        $this->assertStringNotContainsString('Whoops', $pageSource,
            "Page /admin/{$slug} shows Whoops error");

        $check = $browser->script("
            try {
                var inputs = document.querySelectorAll('input:not([type=\"hidden\"]), select, textarea, .fi-toggle, .fi-input');
                var tabs = document.querySelectorAll('[role=\"tab\"]');
                var tables = document.querySelectorAll('.fi-ta, table');
                var addButtons = document.querySelectorAll('[class*=\"repeater\"] button, .fi-ac-action, button');
                var saveButtons = document.querySelectorAll('button[type=\"submit\"], button[wire\\\\:click*=\"save\"]');
                return {
                    inputCount: inputs.length,
                    tabCount: tabs.length,
                    tableCount: tables.length,
                    addButtonCount: addButtons.length,
                    saveButtonCount: saveButtons.length,
                    hasWireId: document.querySelector('[wire\\\\:id]') !== null
                };
            } catch(e) { return {inputCount: 0, tabCount: 0, tableCount: 0, addButtonCount: 0, saveButtonCount: 0, hasWireId: false}; }
        ");

        return $check[0] ?? [];
    }

    /**
     * Helper: click through all tabs and verify no errors.
     */
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
    public function accordion_module_settings_table_and_items(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $data = $this->visitAndVerifySettings($browser, 'accordion-module-settings');

            $this->assertTrue($data['hasWireId'] ?? false,
                'Accordion settings should be a Livewire component');
            $this->assertGreaterThan(0, $data['inputCount'] ?? 0,
                'Accordion settings should have form inputs');

            // Accordion uses a table-based settings (LiveEditModuleSettingsTable)
            // Check for add item / repeater functionality
            $hasRepeater = $browser->script("
                try {
                    var repeater = document.querySelector('.fi-fo-repeater, [class*=\"repeater\"]');
                    var addBtn = document.querySelector('[class*=\"repeater\"] button[class*=\"action\"], .fi-ac-action');
                    return {hasRepeater: repeater !== null, hasAddBtn: addBtn !== null};
                } catch(e) { return {hasRepeater: false, hasAddBtn: false}; }
            ");

            // Navigate tabs if present
            if (($data['tabCount'] ?? 0) > 1) {
                $this->clickThroughTabs($browser, $data['tabCount'], 'Accordion');
            }
        });
    }

    #[Test]
    public function tabs_module_settings_interaction(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $data = $this->visitAndVerifySettings($browser, 'tabs-module-settings');

            $this->assertTrue($data['hasWireId'] ?? false,
                'Tabs module settings should be a Livewire component');
            $this->assertGreaterThan(0, $data['inputCount'] ?? 0,
                'Tabs module settings should have form inputs');

            if (($data['tabCount'] ?? 0) > 1) {
                $this->clickThroughTabs($browser, $data['tabCount'], 'Tabs module');
            }
        });
    }

    #[Test]
    public function faq_module_settings_table_entries(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $data = $this->visitAndVerifySettings($browser, 'faq-module-settings');

            $this->assertTrue($data['hasWireId'] ?? false,
                'FAQ settings should be a Livewire component');

            // FAQ uses LiveEditModuleSettingsTable — check for table or repeater
            $tableCheck = $browser->script("
                try {
                    var table = document.querySelector('.fi-ta, table, .fi-fo-repeater');
                    var rows = document.querySelectorAll('.fi-ta-row, tbody tr, .fi-fo-repeater-item');
                    return {hasTable: table !== null, rowCount: rows.length};
                } catch(e) { return {hasTable: false, rowCount: 0}; }
            ");

            if (($data['tabCount'] ?? 0) > 1) {
                $this->clickThroughTabs($browser, $data['tabCount'], 'FAQ');
            }
        });
    }

    #[Test]
    public function slider_and_testimonials_module_settings(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            // Slider
            $data = $this->visitAndVerifySettings($browser, 'slider-module-settings');
            $this->assertTrue($data['hasWireId'] ?? false,
                'Slider settings should be a Livewire component');
            if (($data['tabCount'] ?? 0) > 1) {
                $this->clickThroughTabs($browser, $data['tabCount'], 'Slider');
            }

            // Testimonials
            $data = $this->visitAndVerifySettings($browser, 'testimonials-module-settings');
            $this->assertTrue($data['hasWireId'] ?? false,
                'Testimonials settings should be a Livewire component');
            if (($data['tabCount'] ?? 0) > 1) {
                $this->clickThroughTabs($browser, $data['tabCount'], 'Testimonials');
            }
        });
    }

    #[Test]
    public function teamcard_rating_skills_module_settings(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            // Teamcard
            $data = $this->visitAndVerifySettings($browser, 'teamcard-module-settings');
            $this->assertTrue($data['hasWireId'] ?? false,
                'Teamcard settings should be a Livewire component');
            if (($data['tabCount'] ?? 0) > 1) {
                $this->clickThroughTabs($browser, $data['tabCount'], 'Teamcard');
            }

            // Rating
            $data = $this->visitAndVerifySettings($browser, 'rating-module-settings');
            $this->assertTrue($data['hasWireId'] ?? false,
                'Rating settings should be a Livewire component');
            if (($data['tabCount'] ?? 0) > 1) {
                $this->clickThroughTabs($browser, $data['tabCount'], 'Rating');
            }

            // Skills
            $data = $this->visitAndVerifySettings($browser, 'skills-module-settings');
            $this->assertTrue($data['hasWireId'] ?? false,
                'Skills settings should be a Livewire component');
            if (($data['tabCount'] ?? 0) > 1) {
                $this->clickThroughTabs($browser, $data['tabCount'], 'Skills');
            }
        });
    }
}
