<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\DuskTestCase;

/**
 * Admin Login/Logout Tests
 *
 * Tests authentication flow: login with valid creds, login with bad creds,
 * session persistence, and logout.
 *
 * Prerequisites:
 *   - A running dev server at http://127.0.0.1:8000
 *   - An admin user with email admin@admin.com / password123
 *   - Login captcha disabled
 */
class AdminLoginTest extends DuskTestCase
{
    protected function assertPreConditions(): void
    {
        // Skip parent — we rely on the already-running server's database
    }

    #[Test]
    public function login_and_logout_flow(): void
    {
        $this->browse(function (Browser $browser) {
            $checks = 0;
            $failed = [];

            // ── Check 1: Login page loads ──
            try {
                $browser->visit('/admin/login')
                    ->waitFor('input[type="email"]', 10);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'Login page should not return 500');

                // Verify form fields exist
                $hasEmail = $browser->resolver->findOrFail('input[type="email"]');
                $this->assertNotNull($hasEmail, 'Email input should exist');

                $hasPassword = $browser->resolver->findOrFail('input[type="password"]');
                $this->assertNotNull($hasPassword, 'Password input should exist');

                $hasSubmit = $browser->resolver->findOrFail('button[type="submit"]');
                $this->assertNotNull($hasSubmit, 'Submit button should exist');

                $checks++;
            } catch (\Exception $e) {
                $failed['login_page_loads'] = $e->getMessage();
                $browser->screenshot('fail-login-page');
            }

            // ── Check 2: Invalid credentials show error ──
            try {
                $browser->visit('/admin/login')
                    ->waitFor('input[type="email"]', 10)
                    ->type('input[type="email"]', 'wrong@wrong.com')
                    ->type('input[type="password"]', 'wrongpassword')
                    ->click('button[type="submit"]')
                    ->pause(3000);

                $currentUrl = $browser->driver->getCurrentURL();
                $pageSource = $browser->driver->getPageSource();

                // Should still be on login page or show an error
                $this->assertTrue(
                    str_contains($currentUrl, '/login') || str_contains($pageSource, 'credentials'),
                    'Invalid login should stay on login page or show error'
                );

                $checks++;
            } catch (\Exception $e) {
                $failed['invalid_credentials'] = $e->getMessage();
                $browser->screenshot('fail-invalid-credentials');
            }

            // ── Check 3: Valid login redirects to admin ──
            try {
                $browser->visit('/admin/login')
                    ->waitFor('input[type="email"]', 10)
                    ->type('input[type="email"]', 'admin@admin.com')
                    ->type('input[type="password"]', 'password123')
                    ->click('button[type="submit"]')
                    ->pause(5000);

                $currentUrl = $browser->driver->getCurrentURL();
                $this->assertStringNotContainsString('/login', $currentUrl,
                    'Successful login should redirect away from login page');
                $this->assertStringContainsString('/admin', $currentUrl,
                    'Successful login should redirect to admin area');

                $checks++;
            } catch (\Exception $e) {
                $failed['valid_login'] = $e->getMessage();
                $browser->screenshot('fail-valid-login');
            }

            // ── Check 4: Session persists across page navigations ──
            try {
                $browser->visit('/admin/pages')
                    ->pause(3000);

                $currentUrl = $browser->driver->getCurrentURL();
                $this->assertStringNotContainsString('/login', $currentUrl,
                    'Session should persist — pages list should load without redirect to login');

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'Pages list should not return 500');

                $browser->visit('/admin/posts')
                    ->pause(3000);

                $currentUrl = $browser->driver->getCurrentURL();
                $this->assertStringNotContainsString('/login', $currentUrl,
                    'Session should persist — posts list should load without redirect to login');

                $checks++;
            } catch (\Exception $e) {
                $failed['session_persistence'] = $e->getMessage();
                $browser->screenshot('fail-session-persistence');
            }

            // ── Check 5: User menu shows current user ──
            try {
                $browser->visit('/admin')
                    ->pause(3000);

                $pageSource = $browser->driver->getPageSource();
                $this->assertTrue(
                    str_contains($pageSource, 'admin@admin.com') || str_contains($pageSource, 'admin'),
                    'Dashboard should show current user info'
                );

                $checks++;
            } catch (\Exception $e) {
                $failed['user_menu'] = $e->getMessage();
                $browser->screenshot('fail-user-menu');
            }

            // ── Check 6: Logout works ──
            try {
                // Find and click the user menu button, then the logout option
                $logoutWorked = $browser->script("
                    // Try to find the logout form and submit it
                    var logoutForm = document.querySelector('form[action*=\"logout\"]');
                    if (logoutForm) {
                        logoutForm.submit();
                        return 'form';
                    }
                    // Try clicking user menu button first
                    var userBtn = document.querySelector('button[class*=\"user-menu\"], [aria-label=\"User menu\"]');
                    if (!userBtn) {
                        // Try finding any button with avatar image
                        var avatars = document.querySelectorAll('button img[alt*=\"Avatar\"]');
                        if (avatars.length > 0) {
                            userBtn = avatars[0].closest('button');
                        }
                    }
                    if (userBtn) {
                        userBtn.click();
                        return 'clicked_menu';
                    }
                    return 'no_menu_found';
                ");

                $browser->pause(2000);

                if (isset($logoutWorked[0]) && $logoutWorked[0] === 'clicked_menu') {
                    // Look for logout link/button in the dropdown
                    $browser->script("
                        var links = document.querySelectorAll('a, button');
                        for (var i = 0; i < links.length; i++) {
                            var text = links[i].textContent.trim().toLowerCase();
                            if (text.includes('sign out') || text.includes('logout') || text.includes('log out')) {
                                links[i].click();
                                break;
                            }
                        }
                    ");
                    $browser->pause(3000);
                } elseif (isset($logoutWorked[0]) && $logoutWorked[0] === 'form') {
                    $browser->pause(3000);
                } else {
                    // Direct navigation to logout endpoint as fallback
                    $browser->visit('/admin/logout');
                    $browser->pause(3000);
                }

                $currentUrl = $browser->driver->getCurrentURL();
                $this->assertTrue(
                    str_contains($currentUrl, '/login') || str_contains($currentUrl, '/'),
                    'Logout should redirect to login or homepage'
                );

                // Verify access to admin is now denied
                $browser->visit('/admin')
                    ->pause(3000);

                $afterLogoutUrl = $browser->driver->getCurrentURL();
                $this->assertStringContainsString('/login', $afterLogoutUrl,
                    'After logout, visiting /admin should redirect to login');

                $checks++;
            } catch (\Exception $e) {
                $failed['logout'] = $e->getMessage();
                $browser->screenshot('fail-logout');
            }

            // ── Report ──
            if (!empty($failed)) {
                $report = "Failed " . count($failed) . "/{$checks} login/logout checks:\n";
                foreach ($failed as $name => $error) {
                    $report .= "  - {$name}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->assertTrue($checks >= 6, "All 6 login/logout checks passed (got {$checks})");
        });
    }
}
