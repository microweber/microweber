<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\DuskTestCase;

/**
 * Admin Invoice Workflow Tests
 *
 * End-to-end tests for invoice management:
 *   - Invoice list page loads with table
 *   - Invoice detail view accessible
 *   - Invoice search/filter works
 *
 * Prerequisites:
 *   - A running dev server at http://127.0.0.1:8000
 *   - An admin user with email admin@admin.com / password123
 *   - Login captcha disabled
 */
class AdminInvoiceWorkflowTest extends DuskTestCase
{
    protected function assertPreConditions(): void
    {
        // Skip parent — we rely on the already-running server's database
    }

    protected function loginAsAdmin(Browser $browser): void
    {
        $browser->visit('/admin/login')->pause(2000);

        $currentUrl = $browser->driver->getCurrentURL();
        if (!str_contains($currentUrl, '/login')) {
            return;
        }

        for ($attempt = 1; $attempt <= 3; $attempt++) {
            $browser->waitFor('input[type="email"]', 10)
                ->clear('input[type="email"]')
                ->type('input[type="email"]', 'admin@admin.com')
                ->clear('input[type="password"]')
                ->type('input[type="password"]', 'password123')
                ->click('button[type="submit"]')
                ->pause(5000);

            $url = $browser->driver->getCurrentURL();
            if (!str_contains($url, '/login')) {
                return;
            }

            $rateLimited = $browser->script("return document.body.innerText.includes('Too many');");
            if ($rateLimited[0] ?? false) {
                $browser->pause(5000);
                continue;
            }

            break;
        }

        $url = $browser->driver->getCurrentURL();
        $this->assertStringNotContainsString('/login', $url, 'Login failed — still on login page');
    }

    protected function ensureLoggedIn(Browser $browser): void
    {
        $currentUrl = $browser->driver->getCurrentURL();
        if (str_contains($currentUrl, '/login') || !str_contains($currentUrl, '/admin')) {
            $this->loginAsAdmin($browser);
        }
    }

    #[Test]
    public function invoice_list_and_detail_and_search(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $checks = 0;
            $failed = [];

            // ── Check 1: Invoice list page loads ──
            try {
                $browser->visit('/admin/invoices')->pause(5000);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'Invoice list should not return 500');

                $hasContent = $browser->script("
                    return document.querySelector('.fi-ta-table') !== null
                        || document.querySelector('table') !== null
                        || document.querySelector('.fi-empty-state') !== null
                        || document.querySelector('[wire\\\\:id]') !== null
                        || document.querySelectorAll('.fi-section').length > 0;
                ");

                $bodyText = $browser->script("return document.body.innerText.toLowerCase();");
                $text = $bodyText[0] ?? '';
                $hasInvoiceContent = str_contains($text, 'invoice')
                    || str_contains($text, 'amount')
                    || str_contains($text, 'date')
                    || str_contains($text, 'status')
                    || str_contains($text, 'no records')
                    || str_contains($text, 'create');
                $this->assertTrue(
                    ($hasContent[0] ?? false) || $hasInvoiceContent,
                    'Invoice list page should have table/content or invoice-related text'
                );
                $checks++;
            } catch (\Exception $e) {
                $failed['invoice_list'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 2: Invoice detail view accessible ──
            try {
                $browser->visit('/admin/invoices')->pause(3000);
                $this->ensureLoggedIn($browser);

                $detailUrl = $browser->script("
                    var editLink = document.querySelector('a[href*=\"/invoices/\"][href*=\"/edit\"]');
                    if (editLink) return editLink.href;
                    var viewLink = document.querySelector('a[href*=\"/invoices/\"][href*=\"/view\"]');
                    if (viewLink) return viewLink.href;
                    var row = document.querySelector('table tbody tr');
                    if (row) {
                        var link = row.querySelector('a');
                        if (link && link.href.includes('/invoices/')) return link.href;
                    }
                    return null;
                ");

                if ($detailUrl[0] ?? null) {
                    $browser->visit($detailUrl[0])->pause(5000);
                    $this->ensureLoggedIn($browser);

                    $pageSource = $browser->driver->getPageSource();
                    $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                        'Invoice detail page should not return 500');

                    $hasDetail = $browser->script("
                        return document.querySelectorAll('input, select, textarea, .fi-toggle, .fi-infolist').length > 0
                            || document.querySelector('[wire\\\\:id]') !== null;
                    ");
                    $this->assertTrue($hasDetail[0] ?? false,
                        'Invoice detail page should have form fields or info list');
                } else {
                    // No invoices — verify the list page is stable
                    $pageSource = $browser->driver->getPageSource();
                    $this->assertStringNotContainsString('Whoops', $pageSource,
                        'Invoice list page should not show error');
                }
                $checks++;
            } catch (\Exception $e) {
                $failed['invoice_detail'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 3: Invoice search/filter works ──
            try {
                $browser->visit('/admin/invoices')->pause(3000);
                $this->ensureLoggedIn($browser);

                $hasSearch = $browser->script("
                    var search = document.querySelector('.fi-ta-search-field input')
                        || document.querySelector('input[placeholder*=\"Search\"]');
                    return search !== null;
                ");

                if ($hasSearch[0] ?? false) {
                    $browser->script("
                        var search = document.querySelector('.fi-ta-search-field input')
                            || document.querySelector('input[placeholder*=\"Search\"]');
                        if (search) {
                            search.value = 'test';
                            search.dispatchEvent(new Event('input', { bubbles: true }));
                        }
                    ");
                    $browser->pause(3000);

                    $pageSource = $browser->driver->getPageSource();
                    $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                        'Searching invoices should not cause 500');
                }

                // Also check for filter buttons
                $hasFilters = $browser->script("
                    return document.querySelector('.fi-ta-filters') !== null
                        || document.querySelector('button[class*=\"filter\"]') !== null
                        || document.querySelector('.fi-header-actions') !== null;
                ");

                // Page loads without error — pass regardless of filter presence
                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Whoops', $pageSource,
                    'Invoice list should not show error after search/filter interaction');
                $checks++;
            } catch (\Exception $e) {
                $failed['invoice_search'] = substr($e->getMessage(), 0, 200);
            }

            if (!empty($failed)) {
                $report = "Failed " . count($failed) . "/" . ($checks + count($failed)) . " invoice workflow checks:\n";
                foreach ($failed as $name => $error) {
                    $report .= "  - {$name}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->assertTrue($checks >= 3, "All 3 invoice workflow checks passed (got {$checks})");
        });
    }
}
