<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\DuskTestCase;

/**
 * Admin Mail Template Workflow Tests
 *
 * End-to-end tests for email template management:
 *   - Mail templates list page loads with table or empty state
 *   - Mail template edit form has expected fields
 *   - Template content renders
 *
 * Prerequisites:
 *   - A running dev server at http://127.0.0.1:8000
 *   - An admin user with email admin@admin.com / password123
 *   - Login captcha disabled
 */
class AdminMailTemplateWorkflowTest extends DuskTestCase
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
    public function mail_templates_list_and_edit(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $checks = 0;
            $failed = [];

            // ── Check 1: Mail templates list page loads ──
            try {
                $browser->visit('/admin/mail-templates')->pause(5000);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'Mail templates list should not return 500');

                $hasContent = $browser->script("
                    return document.querySelector('.fi-ta-table') !== null
                        || document.querySelector('table') !== null
                        || document.querySelector('.fi-empty-state') !== null
                        || document.querySelector('[wire\\\\:id]') !== null
                        || document.querySelectorAll('.fi-section').length > 0;
                ");

                $bodyText = $browser->script("return document.body.innerText.toLowerCase();");
                $text = $bodyText[0] ?? '';
                $hasMailContent = str_contains($text, 'mail')
                    || str_contains($text, 'template')
                    || str_contains($text, 'email')
                    || str_contains($text, 'subject')
                    || str_contains($text, 'no records')
                    || str_contains($text, 'create');
                $this->assertTrue(
                    ($hasContent[0] ?? false) || $hasMailContent,
                    'Mail templates page should have table/content or mail-related text'
                );
                $checks++;
            } catch (\Exception $e) {
                $failed['templates_list'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 2: Mail template edit form has fields ──
            try {
                $browser->visit('/admin/mail-templates')->pause(5000);
                $this->ensureLoggedIn($browser);

                // Find first edit link
                $editUrl = $browser->script("
                    var editLink = document.querySelector('a[href*=\"/mail-templates/\"][href*=\"/edit\"]');
                    if (editLink) return editLink.href;
                    var row = document.querySelector('table tbody tr');
                    if (row) {
                        var link = row.querySelector('a');
                        if (link && link.href.includes('/mail-templates/')) return link.href;
                    }
                    return null;
                ");

                if ($editUrl[0] ?? null) {
                    $browser->visit($editUrl[0])->pause(5000);
                    $this->ensureLoggedIn($browser);

                    $pageSource = $browser->driver->getPageSource();
                    $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                        'Mail template edit page should not return 500');

                    $formInfo = $browser->script("
                        var inputs = document.querySelectorAll('input, select, textarea, .fi-toggle');
                        return { count: inputs.length };
                    ");
                    $this->assertGreaterThan(0, $formInfo[0]['count'] ?? 0,
                        'Mail template edit page should have form fields');

                    $bodyText = $browser->script("return document.body.innerText.toLowerCase();");
                    $text = $bodyText[0] ?? '';
                    $hasFormLabels = str_contains($text, 'subject')
                        || str_contains($text, 'body')
                        || str_contains($text, 'type')
                        || str_contains($text, 'template')
                        || str_contains($text, 'content')
                        || str_contains($text, 'save');
                    $this->assertTrue($hasFormLabels,
                        'Mail template edit form should have subject/body/type labels');
                } else {
                    // No templates — verify page is stable
                    $pageSource = $browser->driver->getPageSource();
                    $this->assertStringNotContainsString('Whoops', $pageSource,
                        'Mail templates list should not show error');
                }
                $checks++;
            } catch (\Exception $e) {
                $failed['template_edit'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 3: Template content renders (page source check) ──
            try {
                $browser->visit('/admin/mail-templates')->pause(5000);
                $this->ensureLoggedIn($browser);

                $hasRows = $browser->script("
                    return document.querySelectorAll('table tbody tr').length > 0
                        || document.querySelector('.fi-ta-row') !== null;
                ");

                if ($hasRows[0] ?? false) {
                    // Table has content — verify it renders template data
                    $bodyText = $browser->script("return document.body.innerText.toLowerCase();");
                    $text = $bodyText[0] ?? '';
                    $hasTemplateData = str_contains($text, 'password')
                        || str_contains($text, 'registration')
                        || str_contains($text, 'order')
                        || str_contains($text, 'welcome')
                        || str_contains($text, 'notification')
                        || str_contains($text, 'reset')
                        || str_contains($text, 'template');
                    $this->assertTrue($hasTemplateData,
                        'Mail templates table should show template names/types');
                } else {
                    // No templates — page is stable
                    $pageSource = $browser->driver->getPageSource();
                    $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                        'Empty mail templates page should not return 500');
                }
                $checks++;
            } catch (\Exception $e) {
                $failed['template_content'] = substr($e->getMessage(), 0, 200);
            }

            if (!empty($failed)) {
                $report = "Failed " . count($failed) . "/" . ($checks + count($failed)) . " mail template checks:\n";
                foreach ($failed as $name => $error) {
                    $report .= "  - {$name}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->assertTrue($checks >= 3, "All 3 mail template checks passed (got {$checks})");
        });
    }
}
