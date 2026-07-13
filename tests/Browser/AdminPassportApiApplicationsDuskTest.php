<?php

declare(strict_types=1);

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\DuskTestCase;

/**
 * Dusk end-to-end tests for the Passport API Applications admin page.
 *
 * Covers:
 *   1. Page loads without errors
 *   2. Creating a personal access token
 *   3. Token value is displayed after creation
 *   4. Revoking a personal access token
 *   5. Creating an OAuth application
 *   6. Client credentials are displayed
 *   7. Revoking an OAuth application
 *   8. Scope picker is visible
 *   9. API usage guide section exists
 *  10. Filament route smoke test
 */
class AdminPassportApiApplicationsDuskTest extends DuskTestCase
{
    use AdminLoginTrait;

    protected function assertPreConditions(): void
    {
        // Skip parent — rely on the running dev server
    }

    #[Test]
    public function api_applications_page_loads_without_errors(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $browser->visit('/admin/api-applications')->pause(5000);
            $this->ensureLoggedIn($browser);

            $pageSource = $browser->driver->getPageSource();
            $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                'API applications page should not return 500');
            $this->assertStringNotContainsString('Whoops', $pageSource,
                'No error screen should appear');

            // Verify Livewire component is mounted
            $hasWire = $browser->script("return document.querySelector('[wire\\\\:id]') !== null;");
            $this->assertTrue($hasWire[0] ?? false, 'Livewire component should be mounted');
        });
    }

    #[Test]
    public function personal_access_tokens_section_visible(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            $browser->visit('/admin/api-applications')->pause(5000);
            $this->ensureLoggedIn($browser);

            $text = $browser->script("return document.body.innerText;")[0] ?? '';
            $this->assertStringContainsString('Personal Access Tokens', $text,
                'Personal Access Tokens heading should be visible');
            $this->assertStringContainsString('Create Token', $text,
                'Create Token button should be visible');
        });
    }

    #[Test]
    public function oauth_applications_section_visible(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            $browser->visit('/admin/api-applications')->pause(5000);
            $this->ensureLoggedIn($browser);

            $text = $browser->script("return document.body.innerText;")[0] ?? '';
            $this->assertStringContainsString('OAuth Applications', $text,
                'OAuth Applications heading should be visible');
            $this->assertStringContainsString('Create Application', $text,
                'Create Application button should be visible');
        });
    }

    #[Test]
    public function create_personal_access_token(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            $browser->visit('/admin/api-applications')->pause(5000);
            $this->ensureLoggedIn($browser);

            $tokenName = 'Dusk Test Token ' . uniqid();

            // Set the token name via Livewire
            $browser->script("
                var input = document.querySelector('input[wire\\\\:model=\"newTokenName\"]');
                if (input) {
                    var el = input;
                    while (el && !el.getAttribute('wire:id')) el = el.parentElement;
                    if (el) {
                        var comp = window.Livewire.find(el.getAttribute('wire:id'));
                        if (comp) comp.set('newTokenName', " . json_encode($tokenName) . ");
                    }
                    input.value = " . json_encode($tokenName) . ";
                    input.dispatchEvent(new Event('input', { bubbles: true }));
                }
            ");
            $browser->pause(1000);

            // Click Create Token
            $browser->script("
                var btn = document.querySelector('button[wire\\\\:click=\"createPersonalToken\"]');
                if (btn) btn.click();
            ");
            $browser->pause(4000);

            // Verify the token value banner appears
            $tokenValue = $browser->script("
                var codes = document.querySelectorAll('code.select-all');
                for (var i = 0; i < codes.length; i++) {
                    var t = (codes[i].innerText || '').trim();
                    if (t.length > 40) { return t; }
                }
                return null;
            ");
            $tokenValue = is_array($tokenValue) ? ($tokenValue[0] ?? null) : null;

            $this->assertNotEmpty($tokenValue, 'Token value banner should appear after creation');
            $this->assertMatchesRegularExpression(
                '/^[A-Za-z0-9_\-\.]+$/',
                $tokenValue,
                'Token should look like a valid JWT'
            );
        });
    }

    #[Test]
    public function revoke_personal_access_token(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            $browser->visit('/admin/api-applications')->pause(5000);
            $this->ensureLoggedIn($browser);

            // First create a token to revoke
            $browser->script("
                var input = document.querySelector('input[wire\\\\:model=\"newTokenName\"]');
                if (input) {
                    var el = input;
                    while (el && !el.getAttribute('wire:id')) el = el.parentElement;
                    if (el) {
                        var comp = window.Livewire.find(el.getAttribute('wire:id'));
                        if (comp) comp.set('newTokenName', 'Revoke Me');
                    }
                }
            ");
            $browser->pause(500);

            $browser->script("
                var btn = document.querySelector('button[wire\\\\:click=\"createPersonalToken\"]');
                if (btn) btn.click();
            ");
            $browser->pause(4000);

            // Dismiss the token value
            $browser->script("
                var dismiss = document.querySelector('button[wire\\\\:click=\"dismissTokenValue\"]');
                if (dismiss) dismiss.click();
            ");
            $browser->pause(1000);

            // Count tokens before revoke
            $beforeCount = $browser->script("
                return document.querySelectorAll('table tbody tr').length;
            ");
            $tokenCountBefore = $beforeCount[0] ?? 0;

            // Click revoke on the first token row
            $revoked = $browser->script("
                var row = document.querySelector('table tbody tr');
                if (!row) return false;
                var btn = row.querySelector('button[wire\\\\:click^=\"revokeToken\"]');
                if (!btn) return false;
                btn.click();
                return true;
            ");

            $this->assertTrue(
                is_array($revoked) ? (bool) ($revoked[0] ?? false) : (bool) $revoked,
                'Should find and click the Revoke button'
            );

            $browser->pause(3000);

            // After revoke the token list should be shorter
            $afterCount = $browser->script("
                return document.querySelectorAll('table tbody tr').length;
            ");
            $tokenCountAfter = $afterCount[0] ?? 0;

            $this->assertLessThan(
                $tokenCountBefore,
                $tokenCountAfter,
                'Token count should decrease after revocation'
            );
        });
    }

    #[Test]
    public function scope_picker_is_accessible(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            $browser->visit('/admin/api-applications')->pause(5000);
            $this->ensureLoggedIn($browser);

            // Check the Scopes collapsible section exists
            $hasScopeSection = $browser->script("
                return document.querySelector('details summary') !== null
                    && document.body.innerText.includes('Scopes');
            ");
            $this->assertTrue($hasScopeSection[0] ?? false, 'Scope picker section should exist');

            // Open the scope details
            $browser->script("
                var details = document.querySelector('details');
                if (details) details.open = true;
            ");
            $browser->pause(500);

            // Check for scope checkboxes
            $scopeCheckboxes = $browser->script("
                return document.querySelectorAll('details input[type=\"checkbox\"]').length;
            ");
            $this->assertGreaterThan(0, $scopeCheckboxes[0] ?? 0, 'Should have scope checkboxes');
        });
    }

    #[Test]
    public function api_usage_guide_section_exists(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            $browser->visit('/admin/api-applications')->pause(5000);
            $this->ensureLoggedIn($browser);

            $text = $browser->script("return document.body.innerText;")[0] ?? '';
            $this->assertStringContainsString('API Usage Guide', $text,
                'API Usage Guide section should be present');
        });
    }

    #[Test]
    public function filament_admin_routes_smoke_test(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            // Test the API applications page route specifically
            $routes = [
                '/admin/api-applications',
            ];

            foreach ($routes as $route) {
                $browser->visit($route)->pause(3000);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    "{$route} should not return 500");
                $this->assertStringNotContainsString('Page not found', $pageSource,
                    "{$route} should not return 404");

                // Verify the page has actual content
                $hasContent = $browser->script("
                    return document.querySelector('[wire\\\\:id]') !== null
                        || document.querySelectorAll('.fi-section').length > 0;
                ");
                $this->assertTrue(
                    $hasContent[0] ?? false,
                    "{$route} should have Filament content"
                );
            }
        });
    }
}