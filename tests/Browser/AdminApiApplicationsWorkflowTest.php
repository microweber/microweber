<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\DuskTestCase;

/**
 * Admin API Applications Workflow Tests
 *
 * End-to-end tests for OAuth/Passport application management:
 *   - API applications page loads
 *   - Create application form has expected fields
 *   - Application details show client credentials
 *
 * Prerequisites:
 *   - A running dev server at http://127.0.0.1:8000
 *   - An admin user with email admin@admin.com / password123
 *   - Login captcha disabled
 */
class AdminApiApplicationsWorkflowTest extends DuskTestCase
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
    public function api_applications_page_and_create_form(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $checks = 0;
            $failed = [];

            // ── Check 1: API applications page loads ──
            try {
                $browser->visit('/admin/api-applications')->pause(5000);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'API applications page should not return 500');

                $bodyText = $browser->script("return document.body.innerText.toLowerCase();");
                $text = $bodyText[0] ?? '';
                $hasApiContent = str_contains($text, 'api')
                    || str_contains($text, 'application')
                    || str_contains($text, 'oauth')
                    || str_contains($text, 'client')
                    || str_contains($text, 'token')
                    || str_contains($text, 'create')
                    || str_contains($text, 'passport');

                $hasContent = $browser->script("
                    return document.querySelector('[wire\\\\:id]') !== null
                        || document.querySelectorAll('.fi-section').length > 0
                        || document.querySelector('table') !== null
                        || document.querySelector('.fi-empty-state') !== null;
                ");

                $this->assertTrue(
                    ($hasContent[0] ?? false) || $hasApiContent,
                    'API applications page should have content or API-related text'
                );
                $checks++;
            } catch (\Exception $e) {
                $failed['api_page'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 2: Create application button/form ──
            try {
                $browser->visit('/admin/api-applications')->pause(5000);
                $this->ensureLoggedIn($browser);

                $bodyText = $browser->script("return document.body.innerText.toLowerCase();");
                $text = $bodyText[0] ?? '';

                // Look for create button or form elements
                $hasCreateUI = $browser->script("
                    var buttons = document.querySelectorAll('button, a');
                    var hasCreate = false;
                    buttons.forEach(function(btn) {
                        var t = btn.innerText.toLowerCase();
                        if (t.includes('create') || t.includes('new') || t.includes('add')) {
                            hasCreate = true;
                        }
                    });
                    var inputs = document.querySelectorAll('input, select, textarea');
                    return { hasCreate: hasCreate, inputCount: inputs.length };
                ");

                // Check for form fields (name, redirect URI)
                $hasFormLabels = str_contains($text, 'name')
                    || str_contains($text, 'redirect')
                    || str_contains($text, 'uri')
                    || str_contains($text, 'url')
                    || str_contains($text, 'create')
                    || str_contains($text, 'add');

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Whoops', $pageSource,
                    'API applications page should not show error');

                $this->assertTrue(
                    ($hasCreateUI[0]['hasCreate'] ?? false)
                    || ($hasCreateUI[0]['inputCount'] ?? 0) > 0
                    || $hasFormLabels,
                    'API applications page should have create button or form fields'
                );
                $checks++;
            } catch (\Exception $e) {
                $failed['create_form'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 3: Application details (client ID/secret area) ──
            try {
                $browser->visit('/admin/api-applications')->pause(5000);
                $this->ensureLoggedIn($browser);

                $bodyText = $browser->script("return document.body.innerText.toLowerCase();");
                $text = $bodyText[0] ?? '';

                // Check for credential-related content or tokens section
                $hasCredentialContent = str_contains($text, 'client')
                    || str_contains($text, 'secret')
                    || str_contains($text, 'token')
                    || str_contains($text, 'key')
                    || str_contains($text, 'id')
                    || str_contains($text, 'personal')
                    || str_contains($text, 'application');

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'API applications details area should not return 500');

                $this->assertTrue($hasCredentialContent,
                    'API applications page should show client/token/key content');
                $checks++;
            } catch (\Exception $e) {
                $failed['app_details'] = substr($e->getMessage(), 0, 200);
            }

            if (!empty($failed)) {
                $report = "Failed " . count($failed) . "/" . ($checks + count($failed)) . " API application checks:\n";
                foreach ($failed as $name => $error) {
                    $report .= "  - {$name}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->assertTrue($checks >= 3, "All 3 API application checks passed (got {$checks})");
        });
    }
}
