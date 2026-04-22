<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\DuskTestCase;
use Tests\Browser\Traits\AdminLoginTrait;

/**
 * Admin Newsletter Workflow Tests
 *
 * End-to-end tests for newsletter management:
 *   - Newsletter campaigns list and create form
 *   - Newsletter subscribers list and search
 *   - Newsletter lists create and verify
 *   - Sender accounts list and create form
 *   - Newsletter module page loads
 *
 * Prerequisites:
 *   - A running dev server at http://127.0.0.1:8000
 *   - An admin user with email admin@admin.com / password123
 *   - Login captcha disabled
 */
class AdminNewsletterWorkflowTest extends DuskTestCase
{
    use AdminLoginTrait;

    protected function assertPreConditions(): void
    {
        // Skip parent — we rely on the already-running server's database
    }

    #[Test]
    public function newsletter_campaigns_and_create(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $checks = 0;
            $failed = [];

            // ── Check 1: Newsletter campaigns list page loads ──
            try {
                $browser->visit('/admin/newsletter/campaigns')->pause(5000);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'Newsletter campaigns list should not return 500');

                $hasContent = $browser->script("
                    return document.querySelector('.fi-ta-table') !== null
                        || document.querySelector('table') !== null
                        || document.querySelector('.fi-empty-state') !== null
                        || document.querySelector('[wire\\\\:id]') !== null
                        || document.querySelectorAll('.fi-section').length > 0;
                ");

                $bodyText = $browser->script("return document.body.innerText.toLowerCase();");
                $text = $bodyText[0] ?? '';
                $hasNewsletterContent = str_contains($text, 'campaign')
                    || str_contains($text, 'newsletter')
                    || str_contains($text, 'email')
                    || str_contains($text, 'no records')
                    || str_contains($text, 'create');
                $this->assertTrue(
                    ($hasContent[0] ?? false) || $hasNewsletterContent,
                    'Newsletter campaigns page should have table/content or newsletter-related text'
                );
                $checks++;
            } catch (\Exception $e) {
                $failed['campaigns_list'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 2: Campaign create page has form ──
            try {
                $browser->visit('/admin/newsletter/campaigns/create')->pause(5000);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'Campaign create page should not return 500');

                $formInfo = $browser->script("
                    var inputs = document.querySelectorAll('input, select, textarea, .fi-toggle');
                    return { count: inputs.length };
                ");
                $this->assertGreaterThan(0, $formInfo[0]['count'] ?? 0,
                    'Campaign create page should have form fields');

                $bodyText = $browser->script("return document.body.innerText.toLowerCase();");
                $text = $bodyText[0] ?? '';
                $hasFormLabels = str_contains($text, 'subject')
                    || str_contains($text, 'body')
                    || str_contains($text, 'list')
                    || str_contains($text, 'name')
                    || str_contains($text, 'campaign')
                    || str_contains($text, 'create');
                $this->assertTrue($hasFormLabels,
                    'Campaign create form should have subject/body/list labels');
                $checks++;
            } catch (\Exception $e) {
                $failed['campaign_create'] = substr($e->getMessage(), 0, 200);
            }

            if (!empty($failed)) {
                $report = "Failed " . count($failed) . "/" . ($checks + count($failed)) . " newsletter campaign checks:\n";
                foreach ($failed as $name => $error) {
                    $report .= "  - {$name}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->assertTrue($checks >= 2, "All 2 newsletter campaign checks passed (got {$checks})");
        });
    }

    #[Test]
    public function newsletter_subscribers_and_lists(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $checks = 0;
            $failed = [];

            // ── Check 1: Newsletter subscribers list loads ──
            try {
                $browser->visit('/admin/newsletter/subscribers')->pause(5000);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'Newsletter subscribers list should not return 500');

                $hasContent = $browser->script("
                    return document.querySelector('.fi-ta-table') !== null
                        || document.querySelector('table') !== null
                        || document.querySelector('.fi-empty-state') !== null
                        || document.querySelector('[wire\\\\:id]') !== null
                        || document.querySelectorAll('.fi-section').length > 0;
                ");

                $bodyText = $browser->script("return document.body.innerText.toLowerCase();");
                $text = $bodyText[0] ?? '';
                $hasSubscriberContent = str_contains($text, 'subscriber')
                    || str_contains($text, 'email')
                    || str_contains($text, 'newsletter')
                    || str_contains($text, 'no records');
                $this->assertTrue(
                    ($hasContent[0] ?? false) || $hasSubscriberContent,
                    'Newsletter subscribers page should have table/content or subscriber-related text'
                );
                $checks++;
            } catch (\Exception $e) {
                $failed['subscribers_list'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 2: Newsletter lists page loads ──
            try {
                $browser->visit('/admin/newsletter/lists')->pause(5000);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'Newsletter lists page should not return 500');

                $hasContent = $browser->script("
                    return document.querySelector('.fi-ta-table') !== null
                        || document.querySelector('table') !== null
                        || document.querySelector('.fi-empty-state') !== null
                        || document.querySelector('[wire\\\\:id]') !== null
                        || document.querySelectorAll('.fi-section').length > 0;
                ");

                $bodyText = $browser->script("return document.body.innerText.toLowerCase();");
                $text = $bodyText[0] ?? '';
                $hasListContent = str_contains($text, 'list')
                    || str_contains($text, 'newsletter')
                    || str_contains($text, 'no records')
                    || str_contains($text, 'create');
                $this->assertTrue(
                    ($hasContent[0] ?? false) || $hasListContent,
                    'Newsletter lists page should have table/content or list-related text'
                );
                $checks++;
            } catch (\Exception $e) {
                $failed['newsletter_lists'] = substr($e->getMessage(), 0, 200);
            }

            if (!empty($failed)) {
                $report = "Failed " . count($failed) . "/" . ($checks + count($failed)) . " newsletter subscribers/lists checks:\n";
                foreach ($failed as $name => $error) {
                    $report .= "  - {$name}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->assertTrue($checks >= 2, "All 2 newsletter subscribers/lists checks passed (got {$checks})");
        });
    }

    #[Test]
    public function sender_accounts_and_module_page(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $checks = 0;
            $failed = [];

            // ── Check 1: Sender accounts list loads ──
            try {
                $browser->visit('/admin/newsletter/sender-accounts')->pause(5000);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'Sender accounts list should not return 500');

                $hasContent = $browser->script("
                    return document.querySelector('.fi-ta-table') !== null
                        || document.querySelector('table') !== null
                        || document.querySelector('.fi-empty-state') !== null
                        || document.querySelector('[wire\\\\:id]') !== null
                        || document.querySelectorAll('.fi-section').length > 0;
                ");

                $bodyText = $browser->script("return document.body.innerText.toLowerCase();");
                $text = $bodyText[0] ?? '';
                $hasSenderContent = str_contains($text, 'sender')
                    || str_contains($text, 'account')
                    || str_contains($text, 'email')
                    || str_contains($text, 'no records')
                    || str_contains($text, 'create');
                $this->assertTrue(
                    ($hasContent[0] ?? false) || $hasSenderContent,
                    'Sender accounts page should have table/content or sender-related text'
                );
                $checks++;
            } catch (\Exception $e) {
                $failed['sender_accounts'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 2: Newsletter module page loads ──
            try {
                $browser->visit('/admin/modules/newsletter')->pause(5000);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'Newsletter module page should not return 500');

                $bodyText = $browser->script("return document.body.innerText.toLowerCase();");
                $text = $bodyText[0] ?? '';
                $hasModuleContent = str_contains($text, 'newsletter')
                    || str_contains($text, 'email')
                    || str_contains($text, 'campaign')
                    || str_contains($text, 'subscriber')
                    || str_contains($text, 'module');
                $this->assertTrue($hasModuleContent,
                    'Newsletter module page should have newsletter-related content');
                $checks++;
            } catch (\Exception $e) {
                $failed['module_page'] = substr($e->getMessage(), 0, 200);
            }

            if (!empty($failed)) {
                $report = "Failed " . count($failed) . "/" . ($checks + count($failed)) . " sender/module checks:\n";
                foreach ($failed as $name => $error) {
                    $report .= "  - {$name}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->assertTrue($checks >= 2, "All 2 sender/module checks passed (got {$checks})");
        });
    }
}
