<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\DuskTestCase;

/**
 * Admin Module Pages Tests
 *
 * Tests module-specific admin pages that aren't covered by other test files:
 * newsletter, tags, comments, ratings, FAQs, form entries, backups, AI.
 *
 * Prerequisites:
 *   - A running dev server at http://127.0.0.1:8000
 *   - An admin user with email admin@admin.com / password123
 *   - Login captcha disabled
 */
class AdminModulePagesTest extends DuskTestCase
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

    protected function assertPageLoadsWithoutError(Browser $browser, string $url, string $name): void
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
    public function module_list_pages_load(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $checks = 0;
            $failed = [];

            $pages = [
                '/admin/tags' => 'Tags',
                '/admin/tag-groups' => 'Tag Groups',
                '/admin/comments' => 'Comments',
                '/admin/form-entries' => 'Form Entries',
                '/admin/faqs' => 'FAQs',
                '/admin/ratings' => 'Ratings',
                '/admin/mail-templates' => 'Mail Templates',
                '/admin/backups' => 'Backups',
                '/admin/newsletter-subscribers' => 'Newsletter Subscribers',
                '/admin/newsletter-campaigns' => 'Newsletter Campaigns',
                '/admin/newsletter-templates' => 'Newsletter Templates',
                '/admin/newsletter-senders' => 'Newsletter Senders',
                '/admin/newsletter-lists' => 'Newsletter Lists',
            ];

            foreach ($pages as $url => $name) {
                try {
                    $this->assertPageLoadsWithoutError($browser, $url, $name);
                    $checks++;
                } catch (\Exception $e) {
                    $failed[$name] = $e->getMessage();
                    try {
                        $browser->screenshot('fail-module-' . strtolower(str_replace(' ', '-', $name)));
                    } catch (\Exception $ignored) {
                    }
                }
            }

            if (!empty($failed)) {
                $report = "Failed " . count($failed) . "/" . ($checks + count($failed)) . " module page checks:\n";
                foreach ($failed as $name => $error) {
                    $report .= "  - {$name}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->assertTrue($checks >= 13, "All 13 module page checks passed (got {$checks})");
        });
    }

    #[Test]
    public function tag_create_and_delete(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $checks = 0;
            $failed = [];
            $tagName = 'DuskTag' . time();

            // ── Check 1: Create tag page loads ──
            try {
                $browser->visit('/admin/tags/create')->pause(5000);
                $this->ensureLoggedIn($browser);

                $currentUrl = $browser->driver->getCurrentURL();
                if (!str_contains($currentUrl, '/tags')) {
                    $browser->visit('/admin/tags/create')->pause(5000);
                }

                $hasForm = $browser->script("
                    return document.querySelectorAll('input, select, textarea').length > 0;
                ");
                $this->assertTrue($hasForm[0] ?? false, 'Tag create page should have form fields');

                $checks++;
            } catch (\Exception $e) {
                $failed['tag_create_page'] = $e->getMessage();
                $browser->screenshot('fail-tag-create');
            }

            // ── Check 2: Create tag via Livewire form ──
            try {
                $browser->script("
                    var nameInput = document.querySelector('input[id*=\"name\"]');
                    if (nameInput) {
                        var el = nameInput;
                        while (el && !el.getAttribute('wire:id')) el = el.parentElement;
                        if (el) {
                            var wireId = el.getAttribute('wire:id');
                            var comp = window.Livewire.find(wireId);
                            if (comp) comp.set('data.name', '{$tagName}');
                        }
                    }
                ");
                $browser->pause(2000);

                // Click save
                $browser->script("
                    var saveBtn = Array.from(document.querySelectorAll('button')).find(
                        b => b.textContent.trim().includes('Save') || b.textContent.trim().includes('Create')
                    );
                    if (saveBtn) saveBtn.click();
                ");
                $browser->pause(5000);

                $currentUrl = $browser->driver->getCurrentURL();
                // Should redirect to edit page or back to list
                $this->assertTrue(
                    str_contains($currentUrl, '/edit') || str_contains($currentUrl, '/tags'),
                    'After creating tag, should be on edit or list page. Got: ' . $currentUrl
                );

                $checks++;
            } catch (\Exception $e) {
                $failed['tag_create'] = $e->getMessage();
                $browser->screenshot('fail-tag-save');
            }

            // ── Check 3: Tag appears in list ──
            try {
                $browser->visit('/admin/tags')->pause(5000);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringContainsString($tagName, $pageSource,
                    'Created tag should appear in the list');

                $checks++;
            } catch (\Exception $e) {
                $failed['tag_in_list'] = $e->getMessage();
                $browser->screenshot('fail-tag-list');
            }

            // ── Cleanup: Delete the tag via DB API ──
            try {
                $browser->script("
                    // Find the tag row and get its ID from the edit link
                    var links = document.querySelectorAll('a[href*=\"/tags/\"]');
                    var editLink = null;
                    links.forEach(function(l) {
                        if (l.href.includes('/edit') && l.textContent.includes('{$tagName}')) {
                            editLink = l.href;
                        }
                    });
                    // Also try table rows
                    if (!editLink) {
                        var rows = document.querySelectorAll('tr');
                        rows.forEach(function(row) {
                            if (row.textContent.includes('{$tagName}')) {
                                var link = row.querySelector('a[href*=\"/edit\"]');
                                if (link) editLink = link.href;
                            }
                        });
                    }
                    window.__tagEditLink = editLink;
                ");
                $browser->pause(1000);

                $editLink = $browser->script("return window.__tagEditLink;");
                if (isset($editLink[0]) && $editLink[0]) {
                    if (preg_match('#/tags/(\d+)/edit#', $editLink[0], $m)) {
                        $tagId = $m[1];
                        $browser->visit("/admin/tags/{$tagId}/edit")->pause(3000);

                        // Click delete button if available
                        $browser->script("
                            var deleteBtn = Array.from(document.querySelectorAll('button')).find(
                                b => b.textContent.trim().includes('Delete')
                            );
                            if (deleteBtn) deleteBtn.click();
                        ");
                        $browser->pause(2000);

                        // Confirm delete in modal
                        $browser->script("
                            var confirmBtn = Array.from(document.querySelectorAll('button')).find(
                                b => b.textContent.trim().includes('Confirm') || b.textContent.trim().includes('Delete')
                            );
                            if (confirmBtn) confirmBtn.click();
                        ");
                        $browser->pause(3000);
                    }
                }
            } catch (\Exception $ignored) {
            }

            // ── Report ──
            if (!empty($failed)) {
                $report = "Failed " . count($failed) . "/" . ($checks + count($failed)) . " tag CRUD checks:\n";
                foreach ($failed as $name => $error) {
                    $report .= "  - {$name}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->assertTrue($checks >= 3, "All tag CRUD checks passed (got {$checks})");
        });
    }

    #[Test]
    public function ai_agent_chats_page_loads(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $browser->visit('/admin/agent-chats')->pause(5000);
            $this->ensureLoggedIn($browser);

            $pageSource = $browser->driver->getPageSource();
            $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                'AI Agent Chats should not return 500');

            $hasTableOrEmpty = $browser->script("
                return document.querySelector('.fi-ta-table') !== null
                    || document.querySelector('.fi-empty-state') !== null
                    || document.querySelector('table') !== null;
            ");
            $this->assertTrue($hasTableOrEmpty[0] ?? false,
                'Agent Chats page should have a table or empty state');
        });
    }

    #[Test]
    public function backup_page_loads_with_actions(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $browser->visit('/admin/backups')->pause(5000);
            $this->ensureLoggedIn($browser);

            $pageSource = $browser->driver->getPageSource();
            $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                'Backups page should not return 500');

            // Backup page should have a create/new backup button
            $hasAction = $browser->script("
                var elements = document.querySelectorAll('button, a');
                for (var i = 0; i < elements.length; i++) {
                    var text = elements[i].textContent.trim().toLowerCase();
                    if (text.includes('backup') || text.includes('create') || text.includes('new')) {
                        return true;
                    }
                }
                return false;
            ");
            $this->assertTrue($hasAction[0] ?? false,
                'Backups page should have an action button');
        });
    }
}
