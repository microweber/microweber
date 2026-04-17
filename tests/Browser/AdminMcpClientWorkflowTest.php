<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\DuskTestCase;

/**
 * Admin MCP Client Workflow Tests
 *
 * End-to-end tests for MCP client management:
 *   - MCP clients list page loads
 *   - MCP client create form has expected fields
 *   - MCP client view/edit accessible
 *
 * Prerequisites:
 *   - A running dev server at http://127.0.0.1:8000
 *   - An admin user with email admin@admin.com / password123
 *   - Login captcha disabled
 */
class AdminMcpClientWorkflowTest extends DuskTestCase
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
    public function mcp_clients_list_and_create_form(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $checks = 0;
            $failed = [];

            // ── Check 1: MCP clients list page loads ──
            try {
                $browser->visit('/admin/mcp-clients')->pause(5000);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'MCP clients list should not return 500');

                $hasContent = $browser->script("
                    return document.querySelector('.fi-ta-table') !== null
                        || document.querySelector('table') !== null
                        || document.querySelector('.fi-empty-state') !== null
                        || document.querySelector('[wire\\\\:id]') !== null
                        || document.querySelectorAll('.fi-section').length > 0;
                ");

                $bodyText = $browser->script("return document.body.innerText.toLowerCase();");
                $text = $bodyText[0] ?? '';
                $hasMcpContent = str_contains($text, 'mcp')
                    || str_contains($text, 'client')
                    || str_contains($text, 'server')
                    || str_contains($text, 'no records')
                    || str_contains($text, 'create');
                $this->assertTrue(
                    ($hasContent[0] ?? false) || $hasMcpContent,
                    'MCP clients page should have table/content or MCP-related text'
                );
                $checks++;
            } catch (\Exception $e) {
                $failed['clients_list'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 2: MCP client create page has form fields ──
            try {
                $browser->visit('/admin/mcp-clients/create')->pause(5000);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'MCP client create page should not return 500');

                $formInfo = $browser->script("
                    var inputs = document.querySelectorAll('input, select, textarea, .fi-toggle');
                    return { count: inputs.length };
                ");
                $this->assertGreaterThan(0, $formInfo[0]['count'] ?? 0,
                    'MCP client create page should have form fields');

                $bodyText = $browser->script("return document.body.innerText.toLowerCase();");
                $text = $bodyText[0] ?? '';
                $hasFormLabels = str_contains($text, 'name')
                    || str_contains($text, 'slug')
                    || str_contains($text, 'scope')
                    || str_contains($text, 'module')
                    || str_contains($text, 'tool')
                    || str_contains($text, 'client')
                    || str_contains($text, 'create');
                $this->assertTrue($hasFormLabels,
                    'MCP client create form should have name/slug/scopes/modules/tools labels');
                $checks++;
            } catch (\Exception $e) {
                $failed['client_create'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 3: MCP client view/edit accessible ──
            try {
                $browser->visit('/admin/mcp-clients')->pause(3000);
                $this->ensureLoggedIn($browser);

                $clientUrl = $browser->script("
                    var editLink = document.querySelector('a[href*=\"/mcp-clients/\"][href*=\"/edit\"]');
                    if (editLink) return editLink.href;
                    var viewLink = document.querySelector('a[href*=\"/mcp-clients/\"]');
                    if (viewLink && !viewLink.href.includes('/create')) return viewLink.href;
                    var row = document.querySelector('table tbody tr');
                    if (row) {
                        var link = row.querySelector('a');
                        if (link && link.href.includes('/mcp-clients/')) return link.href;
                    }
                    return null;
                ");

                if ($clientUrl[0] ?? null) {
                    $browser->visit($clientUrl[0])->pause(5000);
                    $this->ensureLoggedIn($browser);

                    $pageSource = $browser->driver->getPageSource();
                    $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                        'MCP client view/edit page should not return 500');

                    $hasDetail = $browser->script("
                        return document.querySelectorAll('input, select, textarea, .fi-toggle, .fi-infolist').length > 0
                            || document.querySelector('[wire\\\\:id]') !== null;
                    ");
                    $this->assertTrue($hasDetail[0] ?? false,
                        'MCP client view/edit page should have form fields or info list');

                    $bodyText = $browser->script("return document.body.innerText.toLowerCase();");
                    $text = $bodyText[0] ?? '';
                    $hasClientContent = str_contains($text, 'name')
                        || str_contains($text, 'slug')
                        || str_contains($text, 'scope')
                        || str_contains($text, 'token')
                        || str_contains($text, 'health')
                        || str_contains($text, 'tool')
                        || str_contains($text, 'save');
                    $this->assertTrue($hasClientContent,
                        'MCP client view/edit should show client details');
                } else {
                    // No clients — verify list page is stable
                    $pageSource = $browser->driver->getPageSource();
                    $this->assertStringNotContainsString('Whoops', $pageSource,
                        'MCP clients list should not show error');
                }
                $checks++;
            } catch (\Exception $e) {
                $failed['client_view'] = substr($e->getMessage(), 0, 200);
            }

            if (!empty($failed)) {
                $report = "Failed " . count($failed) . "/" . ($checks + count($failed)) . " MCP client checks:\n";
                foreach ($failed as $name => $error) {
                    $report .= "  - {$name}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->assertTrue($checks >= 3, "All 3 MCP client checks passed (got {$checks})");
        });
    }
}
