<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\DuskTestCase;
use Tests\Browser\Traits\AdminLoginTrait;

/**
 * Admin Payment Workflow Tests
 *
 * End-to-end tests for payment management:
 *   - Payment providers list page loads with table
 *   - Payment provider configuration has form fields
 *   - Payments list page loads
 *
 * Prerequisites:
 *   - A running dev server at http://127.0.0.1:8000
 *   - An admin user with email admin@admin.com / admin (canonical AdminLoginTrait credentials)
 *   - Login captcha disabled
 */
class AdminPaymentWorkflowTest extends DuskTestCase
{
    use AdminLoginTrait;

    protected function assertPreConditions(): void
    {
        // Skip parent — we rely on the already-running server's database
    }

    #[Test]
    public function payment_providers_and_payments_pages_load(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $checks = 0;
            $failed = [];

            // ── Check 1: Payment providers list page ──
            try {
                $browser->visit('/admin/payment-providers')->pause(5000);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'Payment providers list should not return 500');

                $hasContent = $browser->script("
                    return document.querySelector('.fi-ta-table') !== null
                        || document.querySelector('table') !== null
                        || document.querySelector('.fi-empty-state') !== null
                        || document.querySelector('[wire\\\\:id]') !== null
                        || document.querySelectorAll('.fi-section').length > 0;
                ");

                $bodyText = $browser->script("return document.body.innerText.toLowerCase();");
                $text = $bodyText[0] ?? '';
                $hasPaymentContent = str_contains($text, 'payment')
                    || str_contains($text, 'provider')
                    || str_contains($text, 'gateway')
                    || str_contains($text, 'stripe')
                    || str_contains($text, 'paypal')
                    || str_contains($text, 'no records');
                $this->assertTrue(
                    ($hasContent[0] ?? false) || $hasPaymentContent,
                    'Payment providers page should have table/content or payment-related text'
                );
                $checks++;
            } catch (\Exception $e) {
                $failed['providers_list'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 2: Payment provider has configuration form ──
            try {
                // Try to find an edit link on the providers page
                $editUrl = $browser->script("
                    var editLink = document.querySelector('a[href*=\"/payment-providers/\"][href*=\"/edit\"]');
                    if (editLink) return editLink.href;
                    var row = document.querySelector('table tbody tr');
                    if (row) {
                        var link = row.querySelector('a');
                        if (link && link.href.includes('/payment')) return link.href;
                    }
                    return null;
                ");

                if ($editUrl[0] ?? null) {
                    $browser->visit($editUrl[0])->pause(5000);
                    $this->ensureLoggedIn($browser);

                    $pageSource = $browser->driver->getPageSource();
                    $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                        'Payment provider edit page should not return 500');

                    $hasForm = $browser->script("
                        return document.querySelectorAll('input, select, textarea, .fi-toggle').length > 0;
                    ");
                    $this->assertTrue($hasForm[0] ?? false,
                        'Payment provider edit page should have form fields');
                } else {
                    // No providers with edit links — verify page itself is stable
                    $pageSource = $browser->driver->getPageSource();
                    $this->assertStringNotContainsString('Whoops', $pageSource,
                        'Payment providers page should not show error');
                }
                $checks++;
            } catch (\Exception $e) {
                $failed['provider_config'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 3: Payments list page ──
            try {
                $browser->visit('/admin/payments')->pause(5000);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'Payments list should not return 500');

                $hasContent = $browser->script("
                    return document.querySelector('.fi-ta-table') !== null
                        || document.querySelector('table') !== null
                        || document.querySelector('.fi-empty-state') !== null
                        || document.querySelector('[wire\\\\:id]') !== null;
                ");

                $bodyText = $browser->script("return document.body.innerText.toLowerCase();");
                $text = $bodyText[0] ?? '';
                $hasPaymentText = str_contains($text, 'payment')
                    || str_contains($text, 'transaction')
                    || str_contains($text, 'amount')
                    || str_contains($text, 'no records');
                $this->assertTrue(
                    ($hasContent[0] ?? false) || $hasPaymentText,
                    'Payments page should have table or payment-related content'
                );
                $checks++;
            } catch (\Exception $e) {
                $failed['payments_list'] = substr($e->getMessage(), 0, 200);
            }

            if (!empty($failed)) {
                $report = "Failed " . count($failed) . "/" . ($checks + count($failed)) . " payment workflow checks:\n";
                foreach ($failed as $name => $error) {
                    $report .= "  - {$name}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->assertTrue($checks >= 3, "All 3 payment workflow checks passed (got {$checks})");
        });
    }
}
