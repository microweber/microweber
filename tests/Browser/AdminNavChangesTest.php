<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\DuskTestCase;
use Tests\Browser\Traits\AdminLoginTrait;

/**
 * Admin navigation changes
 *
 * Read-only smokes for the shop hub, sidebar entries, payments pages,
 * backup settings, and the invoice settings label.
 *
 * Prerequisites:
 *   - A running dev server at http://127.0.0.1:8000
 *   - An admin user with email admin@admin.com / admin (canonical AdminLoginTrait credentials)
 *   - Login captcha disabled
 */
class AdminNavChangesTest extends DuskTestCase
{
    use AdminLoginTrait;

    protected function assertPreConditions(): void
    {
        // Skip parent — we rely on the already-running server's database
    }

    #[Test]
    public function shop_more_page_loads_with_cards(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $browser->visit('/admin/shop-more')->pause(4000);
            $this->ensureLoggedIn($browser);

            $pageSource = $browser->driver->getPageSource();
            $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                'Shop more page should not return 500');

            $this->assertStringContainsString('Shop Advanced', $pageSource,
                'Shop more page should show Shop Advanced');
            $this->assertStringContainsString('Subscriptions', $pageSource,
                'Shop more page should show the Subscriptions card');
            $this->assertStringContainsString('Payments', $pageSource,
                'Shop more page should show the Payments card');
            $this->assertStringContainsString('Customers', $pageSource,
                'Shop more page should show the Customers card');
            $this->assertStringContainsString('Invoices', $pageSource,
                'Shop more page should show the Invoices card');
            $this->assertStringContainsString('Shop settings', $pageSource,
                'Shop more page should show the Shop settings group');
        });
    }

    #[Test]
    public function shop_sidebar_shows_more_and_product_categories(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $browser->visit('/admin')->pause(4000);
            $this->ensureLoggedIn($browser);

            $sidebarItems = $browser->script("
                var items = [];
                document.querySelectorAll('.fi-sidebar-item').forEach(function (item) {
                    items.push(item.textContent.trim());
                });
                return items;
            ");
            $itemTexts = $sidebarItems[0] ?? [];

            $groupLabelResult = $browser->script("
                var groups = [];
                document.querySelectorAll('.fi-sidebar-group-label, .fi-sidebar-group-button').forEach(function (el) {
                    groups.push(el.textContent.trim());
                });
                return groups;
            ");
            $groupLabels = $groupLabelResult[0] ?? [];

            $flatItems = implode("\n", array_map(static fn ($text): string => trim((string) $text), $itemTexts));
            $this->assertStringContainsString('More', $flatItems,
                'Sidebar items should include More');
            $this->assertStringContainsString('Product Categories', $flatItems,
                'Sidebar items should include Product Categories');

            $normalizedGroups = array_map(static function ($label): string {
                $text = trim((string) $label);
                $collapsed = preg_replace('/\s+/', ' ', $text);

                return $collapsed ?? $text;
            }, $groupLabels);
            $this->assertNotContains('Billing', $normalizedGroups,
                'Billing sidebar group should be removed');
        });
    }

    #[Test]
    public function payments_page_has_add_payment(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $browser->visit('/admin/payments')->pause(4000);
            $this->ensureLoggedIn($browser);

            $pageSource = $browser->driver->getPageSource();
            $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                'Payments page should not return 500');
            $this->assertStringContainsString('Add payment', $pageSource,
                'Payments page should show Add payment');

            $hasCreateLink = $browser->script("return document.querySelector('a[href*=\"payments/create\"]') !== null;");
            $this->assertTrue($hasCreateLink[0] ?? false,
                'Payments page should have an anchor whose href contains payments/create');
        });
    }

    #[Test]
    public function payments_create_page_loads(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $browser->visit('/admin/payments/create')->pause(4000);
            $this->ensureLoggedIn($browser);

            $pageSource = $browser->driver->getPageSource();
            $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                'Payments create page should not return 500');
            $this->assertStringContainsString('Amount', $pageSource,
                'Payments create page should show Amount');
            $this->assertStringContainsString('Currency', $pageSource,
                'Payments create page should show Currency');
        });
    }

    #[Test]
    public function backup_settings_section_on_settings_page(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $browser->visit('/admin/settings')->pause(4000);
            $this->ensureLoggedIn($browser);

            $pageSource = $browser->driver->getPageSource();
            $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                'Settings page should not return 500');
            $this->assertStringContainsString('Backup Schedules', $pageSource,
                'Settings page should show Backup Schedules');
            $this->assertStringContainsString('Backup History', $pageSource,
                'Settings page should show Backup History');
        });
    }

    #[Test]
    public function invoice_settings_label_is_distinct(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $browser->visit('/admin/settings')->pause(4000);
            $this->ensureLoggedIn($browser);

            $pageSource = $browser->driver->getPageSource();
            $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                'Settings page should not return 500');
            $this->assertStringContainsString('Invoice Settings', $pageSource,
                'Settings page should show Invoice Settings');
        });
    }
}
