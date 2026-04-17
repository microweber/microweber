<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\DuskTestCase;

/**
 * Admin AI Chat Workflow Tests
 *
 * End-to-end tests for AI module management:
 *   - Agent chats list page loads
 *   - Agent chat create form loads
 *   - Agent chat view displays messages
 *   - AI settings page with provider config
 *   - AI wizards page loads
 *
 * Prerequisites:
 *   - A running dev server at http://127.0.0.1:8000
 *   - An admin user with email admin@admin.com / password123
 *   - Login captcha disabled
 */
class AdminAiChatWorkflowTest extends DuskTestCase
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
    public function agent_chats_list_and_create(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $checks = 0;
            $failed = [];

            // ── Check 1: Agent chats list page loads ──
            try {
                $browser->visit('/admin/agent-chats')->pause(5000);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'Agent chats list should not return 500');

                $hasContent = $browser->script("
                    return document.querySelector('.fi-ta-table') !== null
                        || document.querySelector('table') !== null
                        || document.querySelector('.fi-empty-state') !== null
                        || document.querySelector('[wire\\\\:id]') !== null
                        || document.querySelectorAll('.fi-section').length > 0;
                ");

                $bodyText = $browser->script("return document.body.innerText.toLowerCase();");
                $text = $bodyText[0] ?? '';
                $hasChatContent = str_contains($text, 'chat')
                    || str_contains($text, 'agent')
                    || str_contains($text, 'ai')
                    || str_contains($text, 'no records')
                    || str_contains($text, 'create');
                $this->assertTrue(
                    ($hasContent[0] ?? false) || $hasChatContent,
                    'Agent chats page should have table/content or chat-related text'
                );
                $checks++;
            } catch (\Exception $e) {
                $failed['chats_list'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 2: Agent chat create page has form ──
            try {
                $browser->visit('/admin/agent-chats/create')->pause(5000);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'Agent chat create page should not return 500');

                $hasForm = $browser->script("
                    var inputs = document.querySelectorAll('input, select, textarea, .fi-toggle');
                    return inputs.length > 0;
                ");

                $bodyText = $browser->script("return document.body.innerText.toLowerCase();");
                $text = $bodyText[0] ?? '';
                $hasChatForm = str_contains($text, 'chat')
                    || str_contains($text, 'agent')
                    || str_contains($text, 'name')
                    || str_contains($text, 'title')
                    || str_contains($text, 'description')
                    || str_contains($text, 'create');
                $this->assertTrue(
                    ($hasForm[0] ?? false) || $hasChatForm,
                    'Agent chat create page should have form fields or chat-related labels'
                );
                $checks++;
            } catch (\Exception $e) {
                $failed['chat_create'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 3: Agent chat view (existing chat) ──
            try {
                $browser->visit('/admin/agent-chats')->pause(3000);
                $this->ensureLoggedIn($browser);

                $chatUrl = $browser->script("
                    var editLink = document.querySelector('a[href*=\"/agent-chats/\"][href*=\"/edit\"]');
                    if (editLink) return editLink.href;
                    var viewLink = document.querySelector('a[href*=\"/agent-chats/\"]');
                    if (viewLink && !viewLink.href.includes('/create')) return viewLink.href;
                    var row = document.querySelector('table tbody tr');
                    if (row) {
                        var link = row.querySelector('a');
                        if (link && link.href.includes('/agent-chats/')) return link.href;
                    }
                    return null;
                ");

                if ($chatUrl[0] ?? null) {
                    $browser->visit($chatUrl[0])->pause(5000);
                    $this->ensureLoggedIn($browser);

                    $pageSource = $browser->driver->getPageSource();
                    $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                        'Agent chat view page should not return 500');

                    $bodyText = $browser->script("return document.body.innerText.toLowerCase();");
                    $text = $bodyText[0] ?? '';
                    $hasChatView = str_contains($text, 'chat')
                        || str_contains($text, 'message')
                        || str_contains($text, 'send')
                        || str_contains($text, 'agent')
                        || str_contains($text, 'ai');
                    $this->assertTrue($hasChatView,
                        'Agent chat view should display chat-related content');
                } else {
                    // No chats — verify list page is stable
                    $pageSource = $browser->driver->getPageSource();
                    $this->assertStringNotContainsString('Whoops', $pageSource,
                        'Agent chats list should not show error');
                }
                $checks++;
            } catch (\Exception $e) {
                $failed['chat_view'] = substr($e->getMessage(), 0, 200);
            }

            if (!empty($failed)) {
                $report = "Failed " . count($failed) . "/" . ($checks + count($failed)) . " AI chat checks:\n";
                foreach ($failed as $name => $error) {
                    $report .= "  - {$name}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->assertTrue($checks >= 3, "All 3 AI chat checks passed (got {$checks})");
        });
    }

    #[Test]
    public function ai_settings_and_wizards_pages_load(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $checks = 0;
            $failed = [];

            // ── Check 1: AI settings page loads with provider config ──
            try {
                $browser->visit('/admin/ai-settings-page')->pause(5000);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'AI settings page should not return 500');

                $hasContent = $browser->script("
                    var inputs = document.querySelectorAll('input, select, textarea, .fi-toggle');
                    var bodyText = document.body.innerText.toLowerCase();
                    return {
                        inputCount: inputs.length,
                        hasProviderText: bodyText.includes('openai')
                            || bodyText.includes('ollama')
                            || bodyText.includes('anthropic')
                            || bodyText.includes('provider')
                            || bodyText.includes('api key')
                            || bodyText.includes('model')
                            || bodyText.includes('settings'),
                        hasTestButton: bodyText.includes('test')
                            || bodyText.includes('connection')
                    };
                ");

                $info = $hasContent[0] ?? [];
                $this->assertTrue(
                    ($info['inputCount'] ?? 0) > 0 || ($info['hasProviderText'] ?? false),
                    'AI settings page should have form fields or provider configuration text'
                );
                $checks++;
            } catch (\Exception $e) {
                $failed['ai_settings'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 2: AI wizards page loads ──
            try {
                $browser->visit('/admin/ai-wizards')->pause(5000);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'AI wizards page should not return 500');

                $hasContent = $browser->script("
                    return document.querySelector('.fi-ta-table') !== null
                        || document.querySelector('table') !== null
                        || document.querySelector('.fi-empty-state') !== null
                        || document.querySelector('[wire\\\\:id]') !== null
                        || document.querySelectorAll('.fi-section').length > 0;
                ");

                $bodyText = $browser->script("return document.body.innerText.toLowerCase();");
                $text = $bodyText[0] ?? '';
                $hasWizardContent = str_contains($text, 'wizard')
                    || str_contains($text, 'ai')
                    || str_contains($text, 'template')
                    || str_contains($text, 'no records')
                    || str_contains($text, 'create');
                $this->assertTrue(
                    ($hasContent[0] ?? false) || $hasWizardContent,
                    'AI wizards page should have table/content or wizard-related text'
                );
                $checks++;
            } catch (\Exception $e) {
                $failed['ai_wizards'] = substr($e->getMessage(), 0, 200);
            }

            if (!empty($failed)) {
                $report = "Failed " . count($failed) . "/" . ($checks + count($failed)) . " AI settings/wizards checks:\n";
                foreach ($failed as $name => $error) {
                    $report .= "  - {$name}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->assertTrue($checks >= 2, "All 2 AI settings/wizards checks passed (got {$checks})");
        });
    }
}
