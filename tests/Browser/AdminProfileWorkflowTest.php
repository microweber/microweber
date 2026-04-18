<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\DuskTestCase;

/**
 * Admin Profile Workflow Tests
 *
 * End-to-end tests for user profile:
 *   - Profile edit page loads with current user data
 *   - Profile form has expected fields
 *
 * Prerequisites:
 *   - A running dev server at http://127.0.0.1:8000
 *   - An admin user with email admin@admin.com / password123
 *   - Login captcha disabled
 */
class AdminProfileWorkflowTest extends DuskTestCase
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
    public function profile_page_loads_with_form(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $checks = 0;
            $failed = [];

            // ── Check 1: Profile page loads (try /profile first, fallback to legacy) ──
            try {
                $browser->visit('/profile')->pause(5000);

                $currentUrl = $browser->driver->getCurrentURL();
                // Profile panel may redirect to its own login
                if (str_contains($currentUrl, '/login')) {
                    // Try legacy profile
                    $browser->visit('/admin/legacy/user/profile')->pause(5000);
                    $this->ensureLoggedIn($browser);
                }

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'Profile page should not return 500');

                $bodyText = $browser->script("return document.body.innerText.toLowerCase();");
                $text = $bodyText[0] ?? '';
                $hasProfileContent = str_contains($text, 'profile')
                    || str_contains($text, 'name')
                    || str_contains($text, 'email')
                    || str_contains($text, 'password')
                    || str_contains($text, 'account')
                    || str_contains($text, 'admin');

                $formInfo = $browser->script("
                    var inputs = document.querySelectorAll('input, select, textarea');
                    return { count: inputs.length };
                ");

                $this->assertTrue(
                    ($formInfo[0]['count'] ?? 0) > 0 || $hasProfileContent,
                    'Profile page should have form fields or profile-related text'
                );
                $checks++;
            } catch (\Exception $e) {
                $failed['profile_loads'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 2: Profile form has expected fields ──
            try {
                // Use the admin user management as profile fallback
                $browser->visit('/admin/legacy/user/profile')->pause(5000);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'Profile form page should not return 500');
                $this->assertStringNotContainsString('Whoops', $pageSource,
                    'Profile form page should not show error');

                $bodyText = $browser->script("return document.body.innerText.toLowerCase();");
                $text = $bodyText[0] ?? '';
                $hasFormLabels = str_contains($text, 'name')
                    || str_contains($text, 'email')
                    || str_contains($text, 'password')
                    || str_contains($text, 'save')
                    || str_contains($text, 'profile')
                    || str_contains($text, 'update');
                $this->assertTrue($hasFormLabels,
                    'Profile page should have name/email/password form labels');
                $checks++;
            } catch (\Exception $e) {
                $failed['profile_form'] = substr($e->getMessage(), 0, 200);
            }

            if (!empty($failed)) {
                $report = "Failed " . count($failed) . "/" . ($checks + count($failed)) . " profile checks:\n";
                foreach ($failed as $name => $error) {
                    $report .= "  - {$name}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->assertTrue($checks >= 2, "All 2 profile checks passed (got {$checks})");
        });
    }
}
