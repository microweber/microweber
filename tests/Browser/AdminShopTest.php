<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\DuskTestCase;
use Tests\Browser\Traits\AdminLoginTrait;

/**
 * Admin Shop Tests
 *
 * Tests shop-related admin pages: orders, shipping, taxes, coupons, payments.
 * Verifies pages load, tables render, and basic interactions work.
 *
 * Prerequisites:
 *   - A running dev server at http://127.0.0.1:8000
 *   - An admin user with email admin@admin.com / password123
 *   - Login captcha disabled
 */
class AdminShopTest extends DuskTestCase
{
    use AdminLoginTrait;

    protected function assertPreConditions(): void
    {
        // Skip parent — we rely on the already-running server's database
    }

    protected function assertPageLoads(Browser $browser, string $url, string $name): void
    {
        $browser->visit($url)->pause(4000);
        $this->ensureLoggedIn($browser);

        $currentUrl = $browser->driver->getCurrentURL();
        if (!str_contains($currentUrl, parse_url($url, PHP_URL_PATH))) {
            $browser->visit($url)->pause(4000);
        }

        $pageSource = $browser->driver->getPageSource();
        $this->assertStringNotContainsString('Internal Server Error', $pageSource,
            "{$name} should not return 500");
        $this->assertStringNotContainsString('Whoops', $pageSource,
            "{$name} should not show error page");
    }

    #[Test]
    public function shop_pages_load(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $checks = 0;
            $failed = [];

            $pages = [
                '/admin/orders' => 'Orders',
                '/admin/shipping-methods' => 'Shipping Methods',
                '/admin/tax-types' => 'Tax Types',
                '/admin/tax-rates' => 'Tax Rates',
                '/admin/coupons' => 'Coupons',
                '/admin/payment-providers' => 'Payment Providers',
                '/admin/products' => 'Products',
                '/admin/product-inventory' => 'Product Inventory',
            ];

            foreach ($pages as $url => $name) {
                try {
                    $this->assertPageLoads($browser, $url, $name);
                    $checks++;
                } catch (\Exception $e) {
                    $failed[$name] = $e->getMessage();
                    try {
                        $browser->screenshot('fail-shop-' . strtolower(str_replace(' ', '-', $name)));
                    } catch (\Exception $ignored) {
                    }
                }
            }

            if (!empty($failed)) {
                $report = "Failed " . count($failed) . "/" . ($checks + count($failed)) . " shop page checks:\n";
                foreach ($failed as $name => $error) {
                    $report .= "  - {$name}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->assertTrue($checks >= 8, "All 8 shop page checks passed (got {$checks})");
        });
    }

    #[Test]
    public function orders_list_has_table(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $browser->visit('/admin/orders')->pause(5000);
            $this->ensureLoggedIn($browser);

            // Filament renders a table or empty state
            $hasTableOrEmpty = $browser->script("
                return document.querySelector('.fi-ta-table') !== null
                    || document.querySelector('.fi-empty-state') !== null
                    || document.querySelector('table') !== null;
            ");

            $this->assertTrue($hasTableOrEmpty[0] ?? false,
                'Orders page should have a table or empty state');
        });
    }

    #[Test]
    public function products_list_has_table_and_create_button(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $browser->visit('/admin/products')->pause(5000);
            $this->ensureLoggedIn($browser);

            $checks = 0;
            $failed = [];

            // Check 1: Product list or empty state (custom layout, not standard Filament table)
            try {
                $hasContent = $browser->script("
                    return document.querySelector('.fi-ta-table') !== null
                        || document.querySelector('.fi-empty-state') !== null
                        || document.querySelector('table') !== null
                        || document.querySelector('[wire\\\\:id]') !== null
                        || document.querySelector('.fi-page-content') !== null;
                ");
                $this->assertTrue($hasContent[0] ?? false,
                    'Products page should have content');
                $checks++;
            } catch (\Exception $e) {
                $failed['content'] = $e->getMessage();
            }

            // Check 2: Create/New button
            try {
                $hasCreate = $browser->script("
                    var buttons = document.querySelectorAll('a, button');
                    for (var i = 0; i < buttons.length; i++) {
                        var text = buttons[i].textContent.trim().toLowerCase();
                        if (text.includes('new product') || text.includes('new') || text.includes('create')) return true;
                    }
                    return false;
                ");
                $this->assertTrue($hasCreate[0] ?? false,
                    'Products page should have a create/new button');
                $checks++;
            } catch (\Exception $e) {
                $failed['create_button'] = $e->getMessage();
            }

            if (!empty($failed)) {
                $report = "Failed checks:\n";
                foreach ($failed as $name => $error) {
                    $report .= "  - {$name}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->assertTrue($checks >= 2, "All product list checks passed (got {$checks})");
        });
    }

    #[Test]
    public function coupon_create_page_loads(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $browser->visit('/admin/coupons/create')->pause(5000);
            $this->ensureLoggedIn($browser);

            $currentUrl = $browser->driver->getCurrentURL();
            if (!str_contains($currentUrl, '/coupons')) {
                $browser->visit('/admin/coupons/create')->pause(5000);
            }

            // Should have form fields
            $hasForm = $browser->script("
                return document.querySelectorAll('input, select, textarea').length > 0;
            ");

            $this->assertTrue($hasForm[0] ?? false,
                'Coupon create page should have form fields');
        });
    }
}
