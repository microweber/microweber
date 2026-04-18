<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\DuskTestCase;
use Tests\Browser\Traits\AdminLoginTrait;

/**
 * Admin Shipping & Tax Workflow Tests
 *
 * End-to-end tests for shipping and tax management:
 *   - Shipping providers list, create form, edit page
 *   - Tax list, create form, edit page
 *   - Tax rates list and create form
 *
 * Prerequisites:
 *   - A running dev server at http://127.0.0.1:8000
 *   - An admin user with email admin@admin.com / password123
 *   - Login captcha disabled
 */
class AdminShippingTaxWorkflowTest extends DuskTestCase
{
    use AdminLoginTrait;

    protected function assertPreConditions(): void
    {
        // Skip parent — we rely on the already-running server's database
    }

    #[Test]
    public function shipping_providers_list_and_create_form(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $checks = 0;
            $failed = [];

            // ── Check 1: Shipping providers list page loads ──
            try {
                $browser->visit('/admin/shipping-providers')->pause(5000);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'Shipping providers list should not return 500');

                $hasContent = $browser->script("
                    return document.querySelector('.fi-ta-table') !== null
                        || document.querySelector('table') !== null
                        || document.querySelector('.fi-empty-state') !== null
                        || document.querySelector('[wire\\\\:id]') !== null
                        || document.querySelectorAll('.fi-section').length > 0;
                ");

                $bodyText = $browser->script("return document.body.innerText.toLowerCase();");
                $text = $bodyText[0] ?? '';
                $hasShippingContent = str_contains($text, 'shipping')
                    || str_contains($text, 'provider')
                    || str_contains($text, 'delivery')
                    || str_contains($text, 'no records')
                    || str_contains($text, 'create');
                $this->assertTrue(
                    ($hasContent[0] ?? false) || $hasShippingContent,
                    'Shipping providers page should have table/content or shipping-related text'
                );
                $checks++;
            } catch (\Exception $e) {
                $failed['providers_list'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 2: Shipping provider create page has form ──
            try {
                $browser->visit('/admin/shipping-providers/create')->pause(5000);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'Shipping provider create page should not return 500');

                $formInfo = $browser->script("
                    var inputs = document.querySelectorAll('input, select, textarea, .fi-toggle');
                    return { count: inputs.length };
                ");
                $this->assertGreaterThan(0, $formInfo[0]['count'] ?? 0,
                    'Shipping provider create page should have form fields');

                $bodyText = $browser->script("return document.body.innerText.toLowerCase();");
                $text = $bodyText[0] ?? '';
                $hasFormContent = str_contains($text, 'shipping')
                    || str_contains($text, 'provider')
                    || str_contains($text, 'name')
                    || str_contains($text, 'title')
                    || str_contains($text, 'create');
                $this->assertTrue($hasFormContent,
                    'Shipping provider create page should have shipping-related labels');
                $checks++;
            } catch (\Exception $e) {
                $failed['provider_create'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 3: Shipping provider edit page accessible ──
            try {
                $browser->visit('/admin/shipping-providers')->pause(5000);
                $this->ensureLoggedIn($browser);

                $editUrl = $browser->script("
                    var editLink = document.querySelector('a[href*=\"/shipping-providers/\"][href*=\"/edit\"]');
                    if (editLink) return editLink.href;
                    var row = document.querySelector('table tbody tr');
                    if (row) {
                        var link = row.querySelector('a');
                        if (link && link.href.includes('/shipping-providers/')) return link.href;
                    }
                    return null;
                ");

                if ($editUrl[0] ?? null) {
                    $browser->visit($editUrl[0])->pause(5000);
                    $this->ensureLoggedIn($browser);

                    $pageSource = $browser->driver->getPageSource();
                    $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                        'Shipping provider edit page should not return 500');

                    $hasForm = $browser->script("
                        return document.querySelectorAll('input, select, textarea, .fi-toggle').length > 0;
                    ");
                    $this->assertTrue($hasForm[0] ?? false,
                        'Shipping provider edit page should have form fields');
                } else {
                    // No providers yet — page itself is stable
                    $pageSource = $browser->driver->getPageSource();
                    $this->assertStringNotContainsString('Whoops', $pageSource,
                        'Shipping providers page should not show error');
                }
                $checks++;
            } catch (\Exception $e) {
                $failed['provider_edit'] = substr($e->getMessage(), 0, 200);
            }

            if (!empty($failed)) {
                $report = "Failed " . count($failed) . "/" . ($checks + count($failed)) . " shipping provider checks:\n";
                foreach ($failed as $name => $error) {
                    $report .= "  - {$name}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->assertTrue($checks >= 3, "All 3 shipping provider checks passed (got {$checks})");
        });
    }

    #[Test]
    public function tax_list_and_create_form(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $checks = 0;
            $failed = [];

            // ── Check 1: Tax list page loads ──
            try {
                $browser->visit('/admin/taxes')->pause(5000);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'Tax list should not return 500');

                $hasContent = $browser->script("
                    return document.querySelector('.fi-ta-table') !== null
                        || document.querySelector('table') !== null
                        || document.querySelector('.fi-empty-state') !== null
                        || document.querySelector('[wire\\\\:id]') !== null
                        || document.querySelectorAll('.fi-section').length > 0;
                ");

                $bodyText = $browser->script("return document.body.innerText.toLowerCase();");
                $text = $bodyText[0] ?? '';
                $hasTaxContent = str_contains($text, 'tax')
                    || str_contains($text, 'rate')
                    || str_contains($text, 'no records')
                    || str_contains($text, 'create');
                $this->assertTrue(
                    ($hasContent[0] ?? false) || $hasTaxContent,
                    'Tax list page should have table/content or tax-related text'
                );
                $checks++;
            } catch (\Exception $e) {
                $failed['tax_list'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 2: Tax create page has form ──
            try {
                $browser->visit('/admin/taxes/create')->pause(5000);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'Tax create page should not return 500');

                $formInfo = $browser->script("
                    var inputs = document.querySelectorAll('input, select, textarea, .fi-toggle');
                    return { count: inputs.length };
                ");
                $this->assertGreaterThan(0, $formInfo[0]['count'] ?? 0,
                    'Tax create page should have form fields');
                $checks++;
            } catch (\Exception $e) {
                $failed['tax_create'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 3: Tax edit page accessible ──
            try {
                $browser->visit('/admin/taxes')->pause(5000);
                $this->ensureLoggedIn($browser);

                $editUrl = $browser->script("
                    var editLink = document.querySelector('a[href*=\"/taxes/\"][href*=\"/edit\"]');
                    if (editLink) return editLink.href;
                    var row = document.querySelector('table tbody tr');
                    if (row) {
                        var link = row.querySelector('a');
                        if (link && link.href.includes('/taxes/')) return link.href;
                    }
                    return null;
                ");

                if ($editUrl[0] ?? null) {
                    $browser->visit($editUrl[0])->pause(5000);
                    $this->ensureLoggedIn($browser);

                    $pageSource = $browser->driver->getPageSource();
                    $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                        'Tax edit page should not return 500');

                    $hasForm = $browser->script("
                        return document.querySelectorAll('input, select, textarea, .fi-toggle').length > 0;
                    ");
                    $this->assertTrue($hasForm[0] ?? false,
                        'Tax edit page should have form fields');
                } else {
                    $pageSource = $browser->driver->getPageSource();
                    $this->assertStringNotContainsString('Whoops', $pageSource,
                        'Tax list page should not show error');
                }
                $checks++;
            } catch (\Exception $e) {
                $failed['tax_edit'] = substr($e->getMessage(), 0, 200);
            }

            if (!empty($failed)) {
                $report = "Failed " . count($failed) . "/" . ($checks + count($failed)) . " tax checks:\n";
                foreach ($failed as $name => $error) {
                    $report .= "  - {$name}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->assertTrue($checks >= 3, "All 3 tax checks passed (got {$checks})");
        });
    }

    #[Test]
    public function tax_rates_list_and_create_form(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $checks = 0;
            $failed = [];

            // ── Check 1: Tax rates list page loads ──
            try {
                $browser->visit('/admin/tax-rates')->pause(5000);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'Tax rates list should not return 500');

                $hasContent = $browser->script("
                    return document.querySelector('.fi-ta-table') !== null
                        || document.querySelector('table') !== null
                        || document.querySelector('.fi-empty-state') !== null
                        || document.querySelector('[wire\\\\:id]') !== null
                        || document.querySelectorAll('.fi-section').length > 0;
                ");

                $bodyText = $browser->script("return document.body.innerText.toLowerCase();");
                $text = $bodyText[0] ?? '';
                $hasTaxRateContent = str_contains($text, 'tax')
                    || str_contains($text, 'rate')
                    || str_contains($text, 'percentage')
                    || str_contains($text, 'no records')
                    || str_contains($text, 'create');
                $this->assertTrue(
                    ($hasContent[0] ?? false) || $hasTaxRateContent,
                    'Tax rates page should have table/content or tax-rate-related text'
                );
                $checks++;
            } catch (\Exception $e) {
                $failed['tax_rates_list'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 2: Tax rate create page has form ──
            try {
                $browser->visit('/admin/tax-rates/create')->pause(5000);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'Tax rate create page should not return 500');

                $formInfo = $browser->script("
                    var inputs = document.querySelectorAll('input, select, textarea, .fi-toggle');
                    return { count: inputs.length };
                ");
                $this->assertGreaterThan(0, $formInfo[0]['count'] ?? 0,
                    'Tax rate create page should have form fields');

                $bodyText = $browser->script("return document.body.innerText.toLowerCase();");
                $text = $bodyText[0] ?? '';
                $hasRateFields = str_contains($text, 'rate')
                    || str_contains($text, 'percentage')
                    || str_contains($text, 'tax')
                    || str_contains($text, 'name')
                    || str_contains($text, 'create');
                $this->assertTrue($hasRateFields,
                    'Tax rate create page should have rate-related labels');
                $checks++;
            } catch (\Exception $e) {
                $failed['tax_rate_create'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 3: Submit empty tax rate form — validation, not crash ──
            try {
                $browser->script("
                    var saveBtn = Array.from(document.querySelectorAll('button')).find(
                        b => b.textContent.trim().includes('Save') || b.textContent.trim().includes('Create')
                    );
                    if (saveBtn) saveBtn.click();
                ");
                $browser->pause(5000);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'Empty tax rate form submission should not cause 500');
                $checks++;
            } catch (\Exception $e) {
                $failed['tax_rate_validation'] = substr($e->getMessage(), 0, 200);
            }

            if (!empty($failed)) {
                $report = "Failed " . count($failed) . "/" . ($checks + count($failed)) . " tax rate checks:\n";
                foreach ($failed as $name => $error) {
                    $report .= "  - {$name}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->assertTrue($checks >= 3, "All 3 tax rate checks passed (got {$checks})");
        });
    }
}
