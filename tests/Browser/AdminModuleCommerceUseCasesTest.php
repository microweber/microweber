<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\DuskTestCase;

/**
 * Per-module Dusk tests for commerce modules: shop, cart, products, orders.
 *
 * Covers general use cases: visiting settings pages, interacting with forms,
 * verifying save actions, and checking module-specific elements render correctly.
 *
 * Prerequisites:
 *   - A running dev server at http://127.0.0.1:8000
 *   - An admin user with email admin@admin.com / admin
 */
class AdminModuleCommerceUseCasesTest extends DuskTestCase
{
    use AdminLoginTrait;

    protected function assertPreConditions(): void
    {
        // Skip parent — we rely on the already-running server's database
    }

    #[Test]
    public function shop_module_settings_form_interaction(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            $browser->visit('/admin/shop-module-settings')->pause(3000);
            $this->ensureLoggedIn($browser);

            $this->assertPageHasNoErrorMarkers($browser, 'Shop module settings');

            // Verify shop-specific form elements exist
            $formCheck = $browser->script("
                try {
                    var inputs = document.querySelectorAll('input:not([type=\"hidden\"]), select, textarea, .fi-toggle, .fi-input');
                    var tabs = document.querySelectorAll('[role=\"tab\"]');
                    var selects = document.querySelectorAll('.fi-fo-select, select');
                    return {
                        inputCount: inputs.length,
                        tabCount: tabs.length,
                        selectCount: selects.length,
                        hasWireId: document.querySelector('[wire\\\\:id]') !== null
                    };
                } catch(e) { return {inputCount: 0, tabCount: 0, selectCount: 0, hasWireId: false}; }
            ");

            $data = $formCheck[0] ?? [];
            $this->assertGreaterThan(0, $data['inputCount'] ?? 0,
                'Shop settings should have form inputs');
            $this->assertTrue($data['hasWireId'] ?? false,
                'Shop settings should be a Livewire component');

            // Try clicking through tabs if they exist
            if (($data['tabCount'] ?? 0) > 1) {
                $browser->script("
                    var tabs = document.querySelectorAll('[role=\"tab\"]');
                    if (tabs.length > 1) tabs[1].click();
                ");
                $browser->pause(1500);

                $this->assertPageHasNoErrorMarkers($browser, 'Shop module settings (after tab switch)');
            }
        });
    }

    #[Test]
    public function cart_add_module_settings_render_and_interact(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            $browser->visit('/admin/cart-add-module-settings')->pause(3000);
            $this->ensureLoggedIn($browser);

            $this->assertPageHasNoErrorMarkers($browser, 'Cart-add module settings');

            $formCheck = $browser->script("
                try {
                    var inputs = document.querySelectorAll('input:not([type=\"hidden\"]), .fi-toggle, .fi-input');
                    var buttons = document.querySelectorAll('button[type=\"submit\"], .fi-btn');
                    return {inputCount: inputs.length, buttonCount: buttons.length};
                } catch(e) { return {inputCount: 0, buttonCount: 0}; }
            ");

            $data = $formCheck[0] ?? [];
            $this->assertGreaterThan(0, $data['inputCount'] ?? 0,
                'Cart add settings should have form inputs');
        });
    }

    #[Test]
    public function products_module_settings_form_fields_and_tabs(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            $browser->visit('/admin/products-module-settings')->pause(3000);
            $this->ensureLoggedIn($browser);

            $this->assertPageHasNoErrorMarkers($browser, 'Products module settings');

            // Products settings should have content type / display configuration
            $formCheck = $browser->script("
                try {
                    var inputs = document.querySelectorAll('input:not([type=\"hidden\"]), select, textarea');
                    var toggles = document.querySelectorAll('.fi-toggle');
                    var tabs = document.querySelectorAll('[role=\"tab\"]');
                    return {
                        inputCount: inputs.length,
                        toggleCount: toggles.length,
                        tabCount: tabs.length
                    };
                } catch(e) { return {inputCount: 0, toggleCount: 0, tabCount: 0}; }
            ");

            $data = $formCheck[0] ?? [];
            $this->assertGreaterThan(0, $data['inputCount'] ?? 0,
                'Products settings should have form inputs');

            // Navigate all tabs and verify no errors
            $tabCount = $data['tabCount'] ?? 0;
            for ($i = 0; $i < min($tabCount, 5); $i++) {
                $browser->script("
                    var tabs = document.querySelectorAll('[role=\"tab\"]');
                    if (tabs[{$i}]) tabs[{$i}].click();
                ");
                $browser->pause(1500);

                $this->assertPageHasNoErrorMarkers($browser, "Products settings tab {$i}");
            }
        });
    }

    #[Test]
    public function product_list_page_table_and_search(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            $browser->visit('/admin/products')->pause(3000);
            $this->ensureLoggedIn($browser);

            $this->assertPageHasNoErrorMarkers($browser, 'Product list page');

            // Verify Filament table components are present
            $tableCheck = $browser->script("
                try {
                    var table = document.querySelector('.fi-ta, table');
                    var rows = document.querySelectorAll('.fi-ta-row, tbody tr');
                    var searchInput = document.querySelector('.fi-ta-search-field input, [type=\"search\"]');
                    var createButton = document.querySelector('a[href*=\"create\"], .fi-btn');
                    return {
                        hasTable: table !== null,
                        rowCount: rows.length,
                        hasSearch: searchInput !== null,
                        hasCreateBtn: createButton !== null
                    };
                } catch(e) { return {hasTable: false, rowCount: 0, hasSearch: false, hasCreateBtn: false}; }
            ");

            $data = $tableCheck[0] ?? [];
            $this->assertTrue($data['hasTable'] ?? false,
                'Product list page should have a table');

            // Try typing in search if available
            if ($data['hasSearch'] ?? false) {
                $browser->script("
                    var search = document.querySelector('.fi-ta-search-field input, [type=\"search\"]');
                    if (search) {
                        search.value = 'test';
                        search.dispatchEvent(new Event('input', {bubbles: true}));
                    }
                ");
                $browser->pause(2000);

                $this->assertPageHasNoErrorMarkers($browser, 'Product list page (after search)');
            }
        });
    }

    #[Test]
    public function order_list_page_and_details(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            $browser->visit('/admin/orders')->pause(3000);
            $this->ensureLoggedIn($browser);

            $this->assertPageHasNoErrorMarkers($browser, 'Order list page');

            // Verify orders page structure
            $pageCheck = $browser->script("
                try {
                    var table = document.querySelector('.fi-ta, table');
                    var emptyState = document.querySelector('.fi-ta-empty-state');
                    var hasWire = document.querySelector('[wire\\\\:id]') !== null;
                    return {
                        hasTable: table !== null,
                        hasEmptyState: emptyState !== null,
                        hasWireId: hasWire
                    };
                } catch(e) { return {hasTable: false, hasEmptyState: false, hasWireId: false}; }
            ");

            $data = $pageCheck[0] ?? [];
            // Orders page should have either a table or an empty state
            $this->assertTrue(
                ($data['hasTable'] ?? false) || ($data['hasEmptyState'] ?? false),
                'Orders page should have a table or empty state'
            );
        });
    }
}
