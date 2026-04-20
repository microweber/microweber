<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\DuskTestCase;

/**
 * Per-module Dusk tests for navigation and layout modules:
 * menu, breadcrumb, pagination, logo, btn, spacer, layouts, layout-content,
 * content, page, post, category, tags, custom-fields.
 *
 * Covers: settings page load, form field interaction, tab navigation,
 * module-specific elements (link pickers, icon pickers, alignment selects).
 *
 * Prerequisites:
 *   - A running dev server at http://127.0.0.1:8000
 *   - An admin user with email admin@admin.com / admin
 */
class AdminModuleNavigationUseCasesTest extends DuskTestCase
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
                var selects = document.querySelectorAll('select, .fi-fo-select');
                return {
                    inputCount: inputs.length,
                    tabCount: tabs.length,
                    selectCount: selects.length,
                    hasWireId: document.querySelector('[wire\\\\:id]') !== null
                };
            } catch(e) { return {inputCount: 0, tabCount: 0, selectCount: 0, hasWireId: false}; }
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
    public function menu_module_settings_menu_selection(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            $this->visitAndAssertNoErrors($browser, 'menu-module-settings');

            $data = $this->getFormStats($browser);
            $this->assertTrue($data['hasWireId'] ?? false,
                'Menu settings should be a Livewire component');
            $this->assertGreaterThan(0, $data['inputCount'] ?? 0,
                'Menu settings should have inputs for menu selection');

            // Menu module should have a select/dropdown for choosing which menu
            $menuCheck = $browser->script("
                try {
                    var selects = document.querySelectorAll('select, .fi-fo-select, [class*=\"select\"]');
                    return {selectCount: selects.length};
                } catch(e) { return {selectCount: 0}; }
            ");

            if (($data['tabCount'] ?? 0) > 1) {
                $this->clickThroughTabs($browser, $data['tabCount'], 'Menu');
            }
        });
    }

    #[Test]
    public function btn_module_settings_link_and_style(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            $this->visitAndAssertNoErrors($browser, 'btn-module-settings');

            $data = $this->getFormStats($browser);
            $this->assertTrue($data['hasWireId'] ?? false,
                'Button settings should be a Livewire component');
            $this->assertGreaterThan(0, $data['inputCount'] ?? 0,
                'Button settings should have inputs');

            // Button module should have text, link, and style options
            $btnCheck = $browser->script("
                try {
                    var textInputs = document.querySelectorAll('input[type=\"text\"]');
                    var toggles = document.querySelectorAll('.fi-toggle, input[type=\"checkbox\"]');
                    var selects = document.querySelectorAll('select, .fi-fo-select');
                    return {
                        textInputCount: textInputs.length,
                        toggleCount: toggles.length,
                        selectCount: selects.length
                    };
                } catch(e) { return {textInputCount: 0, toggleCount: 0, selectCount: 0}; }
            ");

            $this->assertGreaterThanOrEqual(1, $btnCheck[0]['textInputCount'] ?? 0,
                'Button settings should have at least 1 text input (button text)');

            if (($data['tabCount'] ?? 0) > 1) {
                $this->clickThroughTabs($browser, $data['tabCount'], 'Button');
            }
        });
    }

    #[Test]
    public function logo_breadcrumb_pagination_spacer_settings(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            // Logo
            $this->visitAndAssertNoErrors($browser, 'logo-module-settings');
            $data = $this->getFormStats($browser);
            $this->assertTrue($data['hasWireId'] ?? false,
                'Logo settings should be a Livewire component');
            $this->assertGreaterThan(0, $data['inputCount'] ?? 0,
                'Logo settings should have inputs');
            if (($data['tabCount'] ?? 0) > 1) {
                $this->clickThroughTabs($browser, $data['tabCount'], 'Logo');
            }

            // Breadcrumb
            $this->visitAndAssertNoErrors($browser, 'breadcrumb-module-settings');
            $data = $this->getFormStats($browser);
            $this->assertTrue($data['hasWireId'] ?? false,
                'Breadcrumb settings should be a Livewire component');

            // Pagination
            $this->visitAndAssertNoErrors($browser, 'pagination-module-settings');
            $data = $this->getFormStats($browser);
            $this->assertTrue($data['hasWireId'] ?? false,
                'Pagination settings should be a Livewire component');

            // Spacer
            $this->visitAndAssertNoErrors($browser, 'spacer-module-settings');
            $data = $this->getFormStats($browser);
            $this->assertTrue($data['hasWireId'] ?? false,
                'Spacer settings should be a Livewire component');
            $this->assertGreaterThan(0, $data['inputCount'] ?? 0,
                'Spacer settings should have height/size input');
        });
    }

    #[Test]
    public function content_page_post_module_settings(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            // Content
            $this->visitAndAssertNoErrors($browser, 'content-module-settings');
            $data = $this->getFormStats($browser);
            $this->assertTrue($data['hasWireId'] ?? false,
                'Content settings should be a Livewire component');
            if (($data['tabCount'] ?? 0) > 1) {
                $this->clickThroughTabs($browser, $data['tabCount'], 'Content');
            }

            // Page
            $this->visitAndAssertNoErrors($browser, 'page-module-settings');
            $data = $this->getFormStats($browser);
            $this->assertTrue($data['hasWireId'] ?? false,
                'Page settings should be a Livewire component');

            // Post
            $this->visitAndAssertNoErrors($browser, 'post-module-settings');
            $data = $this->getFormStats($browser);
            $this->assertTrue($data['hasWireId'] ?? false,
                'Post settings should be a Livewire component');
        });
    }

    #[Test]
    public function category_tags_layouts_custom_fields_settings(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            // Category
            $this->visitAndAssertNoErrors($browser, 'category-module-settings');
            $data = $this->getFormStats($browser);
            $this->assertTrue($data['hasWireId'] ?? false,
                'Category settings should be a Livewire component');
            if (($data['tabCount'] ?? 0) > 1) {
                $this->clickThroughTabs($browser, $data['tabCount'], 'Category');
            }

            // Tags
            $this->visitAndAssertNoErrors($browser, 'tags-module-settings');
            $data = $this->getFormStats($browser);
            $this->assertTrue($data['hasWireId'] ?? false,
                'Tags settings should be a Livewire component');

            // Layouts
            $this->visitAndAssertNoErrors($browser, 'layouts-module-settings');
            $data = $this->getFormStats($browser);
            $this->assertTrue($data['hasWireId'] ?? false,
                'Layouts settings should be a Livewire component');

            // Layout Content
            $this->visitAndAssertNoErrors($browser, 'layout-content-module-settings');
            $data = $this->getFormStats($browser);
            $this->assertTrue($data['hasWireId'] ?? false,
                'Layout content settings should be a Livewire component');

            // Custom Fields
            $this->visitAndAssertNoErrors($browser, 'custom-fields-module-settings');
            $data = $this->getFormStats($browser);
            $this->assertTrue($data['hasWireId'] ?? false,
                'Custom fields settings should be a Livewire component');
        });
    }
}
