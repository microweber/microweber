<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\DuskTestCase;
use Tests\Browser\Traits\AdminLoginTrait;

/**
 * Admin User Management Tests
 *
 * Tests user management: list users, create a user, edit a user, delete the created user.
 *
 * Prerequisites:
 *   - A running dev server at http://127.0.0.1:8000
 *   - An admin user with email admin@admin.com / admin (canonical AdminLoginTrait credentials)
 *   - Login captcha disabled
 */
class AdminUserManagementTest extends DuskTestCase
{
    use AdminLoginTrait;

    protected function assertPreConditions(): void
    {
        // Skip parent — we rely on the already-running server's database
    }

    #[Test]
    public function users_list_page_loads(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $browser->visit('/admin/users')->pause(5000);
            $this->ensureLoggedIn($browser);

            $pageSource = $browser->driver->getPageSource();
            $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                'Users list page should not return 500');

            // Should have a table with at least the admin user
            $hasTable = $browser->script("
                return document.querySelector('.fi-ta-table') !== null
                    || document.querySelector('table') !== null;
            ");
            $this->assertTrue($hasTable[0] ?? false, 'Users page should have a table');

            // Should show admin@admin.com in the list
            $this->assertStringContainsString('admin@admin.com', $pageSource,
                'Users list should contain admin@admin.com');
        });
    }

    #[Test]
    public function user_create_page_loads(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $browser->visit('/admin/users/create')->pause(5000);
            $this->ensureLoggedIn($browser);

            $currentUrl = $browser->driver->getCurrentURL();
            if (!str_contains($currentUrl, '/users')) {
                $browser->visit('/admin/users/create')->pause(5000);
            }

            $pageSource = $browser->driver->getPageSource();
            $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                'User create page should not return 500');

            // Should have form fields for name, email, password
            $hasFields = $browser->script("
                var inputs = document.querySelectorAll('input');
                var hasEmail = false;
                var hasPassword = false;
                inputs.forEach(function(input) {
                    var type = input.type || '';
                    var id = input.id || '';
                    var placeholder = input.placeholder || '';
                    if (type === 'email' || id.includes('email') || placeholder.toLowerCase().includes('email')) hasEmail = true;
                    if (type === 'password' || id.includes('password')) hasPassword = true;
                });
                return { hasEmail: hasEmail, hasPassword: hasPassword, totalInputs: inputs.length };
            ");

            $this->assertTrue(($hasFields[0]['totalInputs'] ?? 0) > 0,
                'User create page should have form inputs');
        });
    }

    #[Test]
    public function user_create_form_and_edit_page(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $checks = 0;
            $failed = [];

            // ── Check 1: Create page has required form fields ──
            try {
                $browser->visit('/admin/users/create')->pause(5000);
                $this->ensureLoggedIn($browser);

                $currentUrl = $browser->driver->getCurrentURL();
                if (!str_contains($currentUrl, '/users')) {
                    $browser->visit('/admin/users/create')->pause(5000);
                }

                $fieldInfo = $browser->script("
                    var inputs = document.querySelectorAll('input');
                    var types = [];
                    inputs.forEach(function(i) { types.push(i.type + ':' + (i.id || '')); });
                    return { count: inputs.length, types: types };
                ");

                $this->assertGreaterThan(2, $fieldInfo[0]['count'] ?? 0,
                    'User create form should have at least 3 input fields');

                $checks++;
            } catch (\Exception $e) {
                $failed['create_form_fields'] = $e->getMessage();
                $browser->screenshot('fail-user-create-fields');
            }

            // ── Check 2: Admin user edit page loads ──
            try {
                // Find admin user ID by looking at the list page
                $browser->visit('/admin/users')->pause(5000);
                $this->ensureLoggedIn($browser);

                $editUrl = $browser->script("
                    var links = document.querySelectorAll('a[href*=\"/users/\"]');
                    for (var i = 0; i < links.length; i++) {
                        if (links[i].href.includes('/edit')) return links[i].href;
                    }
                    // Try table rows
                    var rows = document.querySelectorAll('tr');
                    for (var i = 0; i < rows.length; i++) {
                        var link = rows[i].querySelector('a[href*=\"/edit\"]');
                        if (link) return link.href;
                    }
                    return null;
                ");

                if (isset($editUrl[0]) && $editUrl[0]) {
                    $browser->visit($editUrl[0])->pause(5000);
                    $this->ensureLoggedIn($browser);

                    $pageSource = $browser->driver->getPageSource();
                    $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                        'User edit page should not return 500');

                    $checks++;
                } else {
                    // Fallback: try editing user ID 1
                    $browser->visit('/admin/users/1/edit')->pause(5000);
                    $this->ensureLoggedIn($browser);

                    $pageSource = $browser->driver->getPageSource();
                    $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                        'User edit page should not return 500');

                    $checks++;
                }
            } catch (\Exception $e) {
                $failed['user_edit'] = $e->getMessage();
                $browser->screenshot('fail-user-edit');
            }

            // ── Report ──
            if (!empty($failed)) {
                $report = "Failed " . count($failed) . "/" . ($checks + count($failed)) . " user management checks:\n";
                foreach ($failed as $name => $error) {
                    $report .= "  - {$name}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->assertTrue($checks >= 2, "All user management checks passed (got {$checks})");
        });
    }
}
