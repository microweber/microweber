<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\DuskTestCase;
use Tests\Browser\Traits\AdminLoginTrait;

/**
 * Admin Comments Workflow Tests
 *
 * End-to-end tests for comments management:
 *   - Comments list page loads with table or empty state
 *   - Comments moderation actions (approve/spam) visible
 *   - Comment module settings page loads with form
 *
 * Prerequisites:
 *   - A running dev server at http://127.0.0.1:8000
 *   - An admin user with email admin@admin.com / password123
 *   - Login captcha disabled
 */
class AdminCommentsWorkflowTest extends DuskTestCase
{
    use AdminLoginTrait;

    protected function assertPreConditions(): void
    {
        // Skip parent — we rely on the already-running server's database
    }

    #[Test]
    public function comments_list_and_moderation_actions(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $checks = 0;
            $failed = [];

            // ── Check 1: Comments list page loads with table or empty state ──
            try {
                $browser->visit('/admin/comments')->pause(5000);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'Comments list should not return 500');

                $hasContent = $browser->script("
                    return document.querySelector('.fi-ta-table') !== null
                        || document.querySelector('table') !== null
                        || document.querySelector('.fi-empty-state') !== null
                        || document.querySelector('[wire\\\\:id]') !== null
                        || document.querySelectorAll('.fi-section').length > 0
                        || document.querySelector('.fi-resource-index') !== null;
                ");

                $bodyText = $browser->script("return document.body.innerText.toLowerCase();");
                $text = $bodyText[0] ?? '';
                $hasCommentContent = str_contains($text, 'comment')
                    || str_contains($text, 'author')
                    || str_contains($text, 'approved')
                    || str_contains($text, 'spam')
                    || str_contains($text, 'no records')
                    || str_contains($text, 'no comments');
                $this->assertTrue(
                    ($hasContent[0] ?? false) || $hasCommentContent,
                    'Comments page should have table/content or comment-related text'
                );
                $checks++;
            } catch (\Exception $e) {
                $failed['comments_list'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 2: Comments moderation actions visible ──
            try {
                $browser->visit('/admin/comments')->pause(5000);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();

                $hasRows = $browser->script("
                    return document.querySelectorAll('table tbody tr').length > 0
                        || document.querySelector('.fi-ta-row') !== null;
                ");

                if ($hasRows[0] ?? false) {
                    // Table has rows — check for approve/spam action buttons
                    $hasActions = $browser->script("
                        var source = document.body.innerHTML.toLowerCase();
                        return source.includes('approve')
                            || source.includes('spam')
                            || source.includes('heroicon-o-check')
                            || source.includes('heroicon-o-exclamation')
                            || document.querySelectorAll('.fi-ta-actions button, .fi-ta-actions a').length > 0
                            || document.querySelectorAll('[wire\\\\:click]').length > 0;
                    ");
                    $this->assertTrue($hasActions[0] ?? false,
                        'Comments table rows should have moderation action buttons');
                } else {
                    // No comments — verify empty state is stable
                    $hasEmptyOrCreate = $browser->script("
                        return document.querySelector('.fi-empty-state') !== null
                            || document.body.innerText.toLowerCase().includes('no records')
                            || document.body.innerText.toLowerCase().includes('no comments')
                            || document.body.innerText.toLowerCase().includes('create');
                    ");
                    $this->assertTrue($hasEmptyOrCreate[0] ?? false,
                        'Comments page with no rows should show empty state');
                }
                $checks++;
            } catch (\Exception $e) {
                $failed['moderation_actions'] = substr($e->getMessage(), 0, 200);
            }

            if (!empty($failed)) {
                $report = "Failed " . count($failed) . "/" . ($checks + count($failed)) . " comment list/moderation checks:\n";
                foreach ($failed as $name => $error) {
                    $report .= "  - {$name}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->assertTrue($checks >= 2, "All 2 comment list/moderation checks passed (got {$checks})");
        });
    }

    #[Test]
    public function comment_module_settings_page_loads(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $checks = 0;
            $failed = [];

            // ── Check 1: Comment module settings page loads with form ──
            try {
                $browser->visit('/admin/comments-module-settings-admin')->pause(5000);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'Comment module settings page should not return 500');

                $formInfo = $browser->script("
                    var inputs = document.querySelectorAll('input, select, textarea, .fi-toggle');
                    return { count: inputs.length };
                ");
                $this->assertGreaterThan(0, $formInfo[0]['count'] ?? 0,
                    'Comment settings page should have form fields');

                $bodyText = $browser->script("return document.body.innerText.toLowerCase();");
                $text = $bodyText[0] ?? '';
                $hasSettingsContent = str_contains($text, 'comment')
                    || str_contains($text, 'settings')
                    || str_contains($text, 'moderation')
                    || str_contains($text, 'require')
                    || str_contains($text, 'enable')
                    || str_contains($text, 'approval')
                    || str_contains($text, 'save');
                $this->assertTrue($hasSettingsContent,
                    'Comment settings page should have comment/settings-related text');
                $checks++;
            } catch (\Exception $e) {
                $failed['settings_page'] = substr($e->getMessage(), 0, 200);
            }

            if (!empty($failed)) {
                $report = "Failed " . count($failed) . "/" . ($checks + count($failed)) . " comment settings checks:\n";
                foreach ($failed as $name => $error) {
                    $report .= "  - {$name}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->assertTrue($checks >= 1, "Comment settings check passed (got {$checks})");
        });
    }
}
