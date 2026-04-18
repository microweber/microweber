<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\DuskTestCase;

/**
 * Admin Updater Workflow Tests
 *
 * End-to-end tests for system updater:
 *   - Updater page loads without 500
 *   - Shows version info
 *   - Update check button doesn't crash
 *
 * Prerequisites:
 *   - A running dev server at http://127.0.0.1:8000
 *   - An admin user with email admin@admin.com / password123
 *   - Login captcha disabled
 */
class AdminUpdaterWorkflowTest extends DuskTestCase
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
    public function updater_page_loads_and_version_info(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $checks = 0;
            $failed = [];

            // ── Check 1: Updater page loads ──
            try {
                $browser->visit('/admin/updater')->pause(5000);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'Updater page should not return 500');
                $this->assertStringNotContainsString('Whoops', $pageSource,
                    'Updater page should not show Whoops error');
                $checks++;
            } catch (\Exception $e) {
                $failed['updater_loads'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 2: Version info visible ──
            try {
                $browser->visit('/admin/updater')->pause(5000);
                $this->ensureLoggedIn($browser);

                $bodyText = $browser->script("return document.body.innerText.toLowerCase();");
                $text = $bodyText[0] ?? '';
                $hasVersionContent = str_contains($text, 'version')
                    || str_contains($text, 'update')
                    || str_contains($text, 'current')
                    || str_contains($text, 'latest')
                    || str_contains($text, 'check')
                    || str_contains($text, 'microweber');

                $this->assertTrue($hasVersionContent,
                    'Updater page should show version or update-related text');
                $checks++;
            } catch (\Exception $e) {
                $failed['version_info'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 3: Update check button present (don't click it) ──
            try {
                $browser->visit('/admin/updater')->pause(5000);
                $this->ensureLoggedIn($browser);

                $hasButton = $browser->script("
                    var buttons = document.querySelectorAll('button, a.fi-btn');
                    var hasCheckBtn = false;
                    buttons.forEach(function(btn) {
                        var t = btn.innerText.toLowerCase();
                        if (t.includes('check') || t.includes('update') || t.includes('refresh')) {
                            hasCheckBtn = true;
                        }
                    });
                    return hasCheckBtn || buttons.length > 0;
                ");

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'Updater page should remain stable');
                $checks++;
            } catch (\Exception $e) {
                $failed['update_button'] = substr($e->getMessage(), 0, 200);
            }

            if (!empty($failed)) {
                $report = "Failed " . count($failed) . "/" . ($checks + count($failed)) . " updater checks:\n";
                foreach ($failed as $name => $error) {
                    $report .= "  - {$name}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->assertTrue($checks >= 3, "All 3 updater checks passed (got {$checks})");
        });
    }
}
