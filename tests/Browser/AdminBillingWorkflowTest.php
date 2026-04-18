<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\DuskTestCase;
use Tests\Browser\Traits\AdminLoginTrait;

/**
 * Admin Billing Workflow Tests
 *
 * End-to-end tests for billing management:
 *   - Subscriptions list page loads
 *   - Subscription plans list and create form
 *   - Plan groups list and create form
 *   - Billing users list and search
 *   - Billing settings page loads with form
 *
 * Prerequisites:
 *   - A running dev server at http://127.0.0.1:8000
 *   - An admin user with email admin@admin.com / password123
 *   - Login captcha disabled
 *   - Billing module installed
 */
class AdminBillingWorkflowTest extends DuskTestCase
{
    use AdminLoginTrait;

    protected function assertPreConditions(): void
    {
        // Skip parent — we rely on the already-running server's database
    }

    #[Test]
    public function subscriptions_and_plans_list(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $checks = 0;
            $failed = [];

            // ── Check 1: Subscriptions list page loads ──
            try {
                $browser->visit('/admin/billing/subscriptions')->pause(5000);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'Subscriptions list should not return 500');

                $hasContent = $browser->script("
                    return document.querySelector('.fi-ta-table') !== null
                        || document.querySelector('table') !== null
                        || document.querySelector('.fi-empty-state') !== null
                        || document.querySelector('[wire\\\\:id]') !== null
                        || document.querySelectorAll('.fi-section').length > 0;
                ");

                $bodyText = $browser->script("return document.body.innerText.toLowerCase();");
                $text = $bodyText[0] ?? '';
                $hasSubContent = str_contains($text, 'subscription')
                    || str_contains($text, 'plan')
                    || str_contains($text, 'billing')
                    || str_contains($text, 'no records')
                    || str_contains($text, 'create');
                $this->assertTrue(
                    ($hasContent[0] ?? false) || $hasSubContent,
                    'Subscriptions page should have table/content or subscription-related text'
                );
                $checks++;
            } catch (\Exception $e) {
                $failed['subscriptions_list'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 2: Subscription plans list and create form ──
            try {
                $browser->visit('/admin/billing/subscription-plans')->pause(5000);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'Subscription plans list should not return 500');

                $hasContent = $browser->script("
                    return document.querySelector('.fi-ta-table') !== null
                        || document.querySelector('table') !== null
                        || document.querySelector('.fi-empty-state') !== null
                        || document.querySelector('[wire\\\\:id]') !== null
                        || document.querySelectorAll('.fi-section').length > 0;
                ");

                $bodyText = $browser->script("return document.body.innerText.toLowerCase();");
                $text = $bodyText[0] ?? '';
                $hasPlanContent = str_contains($text, 'plan')
                    || str_contains($text, 'subscription')
                    || str_contains($text, 'price')
                    || str_contains($text, 'no records')
                    || str_contains($text, 'create');
                $this->assertTrue(
                    ($hasContent[0] ?? false) || $hasPlanContent,
                    'Subscription plans page should have table/content or plan-related text'
                );
                $checks++;

                // Try create form
                $browser->visit('/admin/billing/subscription-plans/create')->pause(5000);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'Subscription plan create page should not return 500');

                $formInfo = $browser->script("
                    var inputs = document.querySelectorAll('input, select, textarea, .fi-toggle');
                    return { count: inputs.length };
                ");
                $this->assertGreaterThan(0, $formInfo[0]['count'] ?? 0,
                    'Subscription plan create page should have form fields');
            } catch (\Exception $e) {
                $failed['plans_list'] = substr($e->getMessage(), 0, 200);
            }

            if (!empty($failed)) {
                $report = "Failed " . count($failed) . "/" . ($checks + count($failed)) . " billing subscription/plan checks:\n";
                foreach ($failed as $name => $error) {
                    $report .= "  - {$name}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->assertTrue($checks >= 2, "All 2 billing subscription/plan checks passed (got {$checks})");
        });
    }

    #[Test]
    public function plan_groups_and_billing_users(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $checks = 0;
            $failed = [];

            // ── Check 1: Plan groups list and create form ──
            try {
                $browser->visit('/admin/billing/subscription-plan-groups')->pause(5000);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'Plan groups list should not return 500');

                $hasContent = $browser->script("
                    return document.querySelector('.fi-ta-table') !== null
                        || document.querySelector('table') !== null
                        || document.querySelector('.fi-empty-state') !== null
                        || document.querySelector('[wire\\\\:id]') !== null
                        || document.querySelectorAll('.fi-section').length > 0;
                ");

                $bodyText = $browser->script("return document.body.innerText.toLowerCase();");
                $text = $bodyText[0] ?? '';
                $hasGroupContent = str_contains($text, 'group')
                    || str_contains($text, 'plan')
                    || str_contains($text, 'no records')
                    || str_contains($text, 'create');
                $this->assertTrue(
                    ($hasContent[0] ?? false) || $hasGroupContent,
                    'Plan groups page should have table/content or group-related text'
                );
                $checks++;

                // Try create form
                $browser->visit('/admin/billing/subscription-plan-groups/create')->pause(5000);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'Plan group create page should not return 500');

                $formInfo = $browser->script("
                    var inputs = document.querySelectorAll('input, select, textarea, .fi-toggle');
                    return { count: inputs.length };
                ");
                $this->assertGreaterThan(0, $formInfo[0]['count'] ?? 0,
                    'Plan group create page should have form fields');
            } catch (\Exception $e) {
                $failed['plan_groups'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 2: Billing users list ──
            try {
                $browser->visit('/admin/billing/billing-users')->pause(5000);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'Billing users list should not return 500');

                $hasContent = $browser->script("
                    return document.querySelector('.fi-ta-table') !== null
                        || document.querySelector('table') !== null
                        || document.querySelector('.fi-empty-state') !== null
                        || document.querySelector('[wire\\\\:id]') !== null
                        || document.querySelectorAll('.fi-section').length > 0;
                ");

                $bodyText = $browser->script("return document.body.innerText.toLowerCase();");
                $text = $bodyText[0] ?? '';
                $hasUserContent = str_contains($text, 'user')
                    || str_contains($text, 'billing')
                    || str_contains($text, 'email')
                    || str_contains($text, 'no records')
                    || str_contains($text, 'create');
                $this->assertTrue(
                    ($hasContent[0] ?? false) || $hasUserContent,
                    'Billing users page should have table/content or user-related text'
                );
                $checks++;
            } catch (\Exception $e) {
                $failed['billing_users'] = substr($e->getMessage(), 0, 200);
            }

            if (!empty($failed)) {
                $report = "Failed " . count($failed) . "/" . ($checks + count($failed)) . " billing groups/users checks:\n";
                foreach ($failed as $name => $error) {
                    $report .= "  - {$name}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->assertTrue($checks >= 2, "All 2 billing groups/users checks passed (got {$checks})");
        });
    }

    #[Test]
    public function billing_settings_page_loads(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $checks = 0;
            $failed = [];

            // ── Check 1: Billing settings page loads with form ──
            try {
                $browser->visit('/admin/billing/settings')->pause(5000);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'Billing settings page should not return 500');

                $hasContent = $browser->script("
                    var inputs = document.querySelectorAll('input, select, textarea, .fi-toggle');
                    return { count: inputs.length };
                ");

                $bodyText = $browser->script("return document.body.innerText.toLowerCase();");
                $text = $bodyText[0] ?? '';
                $hasSettingsContent = str_contains($text, 'billing')
                    || str_contains($text, 'settings')
                    || str_contains($text, 'payment')
                    || str_contains($text, 'currency')
                    || str_contains($text, 'save')
                    || str_contains($text, 'subscription');
                $this->assertTrue(
                    ($hasContent[0]['count'] ?? 0) > 0 || $hasSettingsContent,
                    'Billing settings page should have form fields or settings-related text'
                );
                $checks++;
            } catch (\Exception $e) {
                $failed['billing_settings'] = substr($e->getMessage(), 0, 200);
            }

            if (!empty($failed)) {
                $report = "Failed " . count($failed) . "/" . ($checks + count($failed)) . " billing settings checks:\n";
                foreach ($failed as $name => $error) {
                    $report .= "  - {$name}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->assertTrue($checks >= 1, "Billing settings check passed (got {$checks})");
        });
    }
}
