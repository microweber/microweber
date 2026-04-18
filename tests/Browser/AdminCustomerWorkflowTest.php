<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\DuskTestCase;

/**
 * Admin Customer Workflow Tests
 *
 * End-to-end tests for customer management:
 *   - Customer list page loads with table
 *   - Customer detail/edit view loads
 *   - Customer create form (if available)
 *
 * Prerequisites:
 *   - A running dev server at http://127.0.0.1:8000
 *   - An admin user with email admin@admin.com / password123
 *   - Login captcha disabled
 */
class AdminCustomerWorkflowTest extends DuskTestCase
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
    public function customer_list_and_detail(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $checks = 0;
            $failed = [];

            // ── Check 1: Customer list page loads with table ──
            try {
                $browser->visit('/admin/customers')->pause(5000);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'Customer list should not return 500');

                $hasContent = $browser->script("
                    return document.querySelector('.fi-ta-table') !== null
                        || document.querySelector('table') !== null
                        || document.querySelector('.fi-empty-state') !== null
                        || document.querySelector('[wire\\\\:id]') !== null
                        || document.querySelectorAll('.fi-section').length > 0;
                ");

                $bodyText = $browser->script("return document.body.innerText.toLowerCase();");
                $text = $bodyText[0] ?? '';
                $hasCustomerContent = str_contains($text, 'customer')
                    || str_contains($text, 'email')
                    || str_contains($text, 'name')
                    || str_contains($text, 'no records')
                    || str_contains($text, 'create');
                $this->assertTrue(
                    ($hasContent[0] ?? false) || $hasCustomerContent,
                    'Customer list page should have table/content or customer-related text'
                );
                $checks++;
            } catch (\Exception $e) {
                $failed['customer_list'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 2: Customer detail/edit view ──
            try {
                $browser->visit('/admin/customers')->pause(5000);
                $this->ensureLoggedIn($browser);

                $editUrl = $browser->script("
                    var editLink = document.querySelector('a[href*=\"/customers/\"][href*=\"/edit\"]');
                    if (editLink) return editLink.href;
                    var row = document.querySelector('table tbody tr');
                    if (row) {
                        var link = row.querySelector('a');
                        if (link && link.href.includes('/customers/')) return link.href;
                    }
                    return null;
                ");

                if ($editUrl[0] ?? null) {
                    $browser->visit($editUrl[0])->pause(5000);
                    $this->ensureLoggedIn($browser);

                    $pageSource = $browser->driver->getPageSource();
                    $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                        'Customer detail page should not return 500');

                    $bodyText = $browser->script("return document.body.innerText.toLowerCase();");
                    $text = $bodyText[0] ?? '';
                    $hasDetailContent = str_contains($text, 'email')
                        || str_contains($text, 'name')
                        || str_contains($text, 'order')
                        || str_contains($text, 'customer')
                        || str_contains($text, 'profile')
                        || str_contains($text, 'save');
                    $this->assertTrue($hasDetailContent,
                        'Customer detail page should show customer info');
                } else {
                    $pageSource = $browser->driver->getPageSource();
                    $this->assertStringNotContainsString('Whoops', $pageSource,
                        'Customer list should not show error when no customers');
                }
                $checks++;
            } catch (\Exception $e) {
                $failed['customer_detail'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 3: Customer create form ──
            try {
                $browser->visit('/admin/customers/create')->pause(5000);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'Customer create page should not return 500');

                $formInfo = $browser->script("
                    var inputs = document.querySelectorAll('input, select, textarea, .fi-toggle');
                    return { count: inputs.length };
                ");
                $this->assertGreaterThan(0, $formInfo[0]['count'] ?? 0,
                    'Customer create page should have form fields');
                $checks++;
            } catch (\Exception $e) {
                $failed['customer_create'] = substr($e->getMessage(), 0, 200);
            }

            if (!empty($failed)) {
                $report = "Failed " . count($failed) . "/" . ($checks + count($failed)) . " customer checks:\n";
                foreach ($failed as $name => $error) {
                    $report .= "  - {$name}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->assertTrue($checks >= 3, "All 3 customer checks passed (got {$checks})");
        });
    }
}
