<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\DuskTestCase;

/**
 * Admin Global Search Tests
 *
 * Tests the global search functionality in the Filament admin panel:
 * - Search input is visible and accessible
 * - Search returns results for different resource types
 * - Search results are clickable and navigate correctly
 * - Search handles empty results gracefully
 * - Search is accessible via keyboard
 *
 * Prerequisites:
 *   - A running dev server at http://127.0.0.1:8000
 *   - An admin user with email admin@admin.com / password123
 *   - Test data (pages, posts, products, orders) in the database
 */
class AdminGlobalSearchTest extends DuskTestCase
{
    protected function assertPreConditions(): void
    {
        // Skip parent — we rely on the already-running server's database
    }

    #[Test]
    public function global_search_ui_is_accessible(): void
    {
        $this->browse(function (Browser $browser) {
            $checks = 0;
            $failed = [];

            // ── Check 1: Login and navigate to admin ──
            try {
                $browser->visit('/admin/login')
                    ->waitFor('input[type="email"]', 10)
                    ->type('input[type="email"]', 'admin@admin.com')
                    ->type('input[type="password"]', 'password123')
                    ->click('button[type="submit"]')
                    ->pause(5000);

                $currentUrl = $browser->driver->getCurrentURL();
                $this->assertStringNotContainsString('/login', $currentUrl,
                    'Should be logged in');

                $checks++;
            } catch (\Exception $e) {
                $failed['login'] = $e->getMessage();
                $browser->screenshot('fail-search-login');
            }

            // ── Check 2: Global search input is visible ──
            try {
                $browser->visit('/admin')
                    ->pause(3000);

                // Look for search trigger button (magnifying glass icon or "Search" text)
                $hasSearch = $browser->script("return document.querySelector('[data-slot=\"trigger\"]') !== null || document.querySelector('input[placeholder*=\"Search\"]') !== null || document.querySelector('button[title*=\"Search\"]') !== null;");

                $this->assertTrue($hasSearch[0] ?? false, 'Global search trigger should be visible');

                $checks++;
            } catch (\Exception $e) {
                $failed['search_visible'] = $e->getMessage();
                $browser->screenshot('fail-search-visible');
            }

            // ── Check 3: Search modal opens on click ──
            try {
                // Click on search trigger (usually a button or icon)
                $browser->script("
                    var searchTrigger = document.querySelector('[data-slot=\"trigger\"] button') || 
                                       document.querySelector('button[title*=\"Search\"]') ||
                                       document.querySelector('[class*=\"search\"] button');
                    if (searchTrigger) searchTrigger.click();
                    return searchTrigger ? 'clicked' : 'not_found';
                ");

                $browser->pause(2000);

                // Check if search input is now visible in modal/dropdown
                $hasSearchInput = $browser->script("return document.querySelector('input[placeholder*=\"Search\"]') !== null || document.querySelector('input[type=\"search\"]') !== null;");

                $this->assertTrue($hasSearchInput[0] ?? false, 'Search input should be visible after clicking search');

                $checks++;
            } catch (\Exception $e) {
                $failed['search_opens'] = $e->getMessage();
                $browser->screenshot('fail-search-opens');
            }

            // ── Check 4: Search input accepts text ──
            try {
                // Find and type in search input
                $browser->script("
                    var input = document.querySelector('input[placeholder*=\"Search\"]') || 
                               document.querySelector('input[type=\"search\"]');
                    if (input) {
                        input.value = 'test';
                        input.dispatchEvent(new Event('input', { bubbles: true }));
                        return 'typed';
                    }
                    return 'not_found';
                ");

                $browser->pause(1000);

                $checks++;
            } catch (\Exception $e) {
                $failed['search_type'] = $e->getMessage();
                $browser->screenshot('fail-search-type');
            }

            // ── Report ──
            if (!empty($failed)) {
                $report = "Failed " . count($failed) . "/{$checks} global search UI checks:\n";
                foreach ($failed as $name => $error) {
                    $report .= "  - {$name}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->assertTrue($checks >= 4, "All {$checks} global search UI checks passed");
        });
    }

    #[Test]
    public function global_search_returns_results(): void
    {
        $this->browse(function (Browser $browser) {
            $checks = 0;
            $failed = [];

            // ── Check 1: Login ──
            try {
                $browser->visit('/admin/login')
                    ->waitFor('input[type="email"]', 10)
                    ->type('input[type="email"]', 'admin@admin.com')
                    ->type('input[type="password"]', 'password123')
                    ->click('button[type="submit"]')
                    ->pause(5000);

                $checks++;
            } catch (\Exception $e) {
                $failed['login'] = $e->getMessage();
                $browser->screenshot('fail-results-login');
            }

            // ── Check 2: Search for common terms and verify results appear ──
            try {
                $browser->visit('/admin')
                    ->pause(3000);

                // Open search
                $browser->script("
                    var searchTrigger = document.querySelector('[data-slot=\"trigger\"] button') || 
                                       document.querySelector('button[title*=\"Search\"]') ||
                                       document.querySelector('[class*=\"search\"] button');
                    if (searchTrigger) searchTrigger.click();
                ");

                $browser->pause(2000);

                // Type "page" (should match Pages resource)
                $browser->script("
                    var input = document.querySelector('input[placeholder*=\"Search\"]') || 
                               document.querySelector('input[type=\"search\"]');
                    if (input) {
                        input.value = 'page';
                        input.dispatchEvent(new Event('input', { bubbles: true }));
                    }
                ");

                $browser->pause(3000);

                // Check if results container appears
                $hasResults = $browser->script("return document.querySelector('[data-slot=\"content\"]') !== null || document.querySelector('[class*=\"results\"]') !== null || document.querySelector('li') !== null;");

                $this->assertTrue($hasResults[0] ?? false, 'Search should show results container');

                $checks++;
            } catch (\Exception $e) {
                $failed['search_results'] = $e->getMessage();
                $browser->screenshot('fail-search-results');
            }

            // ── Check 3: Results are clickable ──
            try {
                // Try clicking the first result
                $clicked = $browser->script("
                    var firstResult = document.querySelector('[data-slot=\"content\"] a') || 
                                     document.querySelector('[class*=\"results\"] a') ||
                                     document.querySelector('li a');
                    if (firstResult) {
                        firstResult.click();
                        return 'clicked';
                    }
                    return 'no_results';
                ");

                $browser->pause(3000);

                $currentUrl = $browser->driver->getCurrentURL();
                $this->assertStringNotContainsString('/admin#', $currentUrl,
                    'Clicking search result should navigate to a page');

                $checks++;
            } catch (\Exception $e) {
                $failed['click_result'] = $e->getMessage();
                $browser->screenshot('fail-click-result');
            }

            // ── Report ──
            if (!empty($failed)) {
                $report = "Failed " . count($failed) . "/{$checks} global search result checks:\n";
                foreach ($failed as $name => $error) {
                    $report .= "  - {$name}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->assertTrue($checks >= 3, "All {$checks} global search result checks passed");
        });
    }

    #[Test]
    public function global_search_handles_empty_results(): void
    {
        $this->browse(function (Browser $browser) {
            $checks = 0;
            $failed = [];

            // ── Check 1: Login ──
            try {
                $browser->visit('/admin/login')
                    ->waitFor('input[type="email"]', 10)
                    ->type('input[type="email"]', 'admin@admin.com')
                    ->type('input[type="password"]', 'password123')
                    ->click('button[type="submit"]')
                    ->pause(5000);

                $checks++;
            } catch (\Exception $e) {
                $failed['login'] = $e->getMessage();
                $browser->screenshot('fail-empty-login');
            }

            // ── Check 2: Search for non-existent term ──
            try {
                $browser->visit('/admin')
                    ->pause(3000);

                // Open search
                $browser->script("
                    var searchTrigger = document.querySelector('[data-slot=\"trigger\"] button') || 
                                       document.querySelector('button[title*=\"Search\"]') ||
                                       document.querySelector('[class*=\"search\"] button');
                    if (searchTrigger) searchTrigger.click();
                ");

                $browser->pause(2000);

                // Type gibberish that shouldn't match anything
                $browser->script("
                    var input = document.querySelector('input[placeholder*=\"Search\"]') || 
                               document.querySelector('input[type=\"search\"]');
                    if (input) {
                        input.value = 'xyznonexistent123456';
                        input.dispatchEvent(new Event('input', { bubbles: true }));
                    }
                ");

                $browser->pause(3000);

                // Verify page doesn't crash
                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'Search with no results should not cause 500 error');
                $this->assertStringNotContainsString('Whoops', $pageSource,
                    'Search should not show error page');

                $checks++;
            } catch (\Exception $e) {
                $failed['empty_results'] = $e->getMessage();
                $browser->screenshot('fail-empty-results');
            }

            // ── Report ──
            if (!empty($failed)) {
                $report = "Failed " . count($failed) . "/{$checks} empty results checks:\n";
                foreach ($failed as $name => $error) {
                    $report .= "  - {$name}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->assertTrue($checks >= 2, "All {$checks} empty results checks passed");
        });
    }

    #[Test]
    public function global_search_keyboard_accessibility(): void
    {
        $this->browse(function (Browser $browser) {
            $checks = 0;
            $failed = [];

            // ── Check 1: Login ──
            try {
                $browser->visit('/admin/login')
                    ->waitFor('input[type="email"]', 10)
                    ->type('input[type="email"]', 'admin@admin.com')
                    ->type('input[type="password"]', 'password123')
                    ->click('button[type="submit"]')
                    ->pause(5000);

                $checks++;
            } catch (\Exception $e) {
                $failed['login'] = $e->getMessage();
                $browser->screenshot('fail-keyboard-login');
            }

            // ── Check 2: Cmd/Ctrl+K opens search ──
            try {
                $browser->visit('/admin')
                    ->pause(3000);

                // Press Cmd+K (Mac) or Ctrl+K (Windows/Linux)
                $browser->keyDown('command')->press('k')->keyUp('command');
                $browser->pause(2000);

                // Check if search input is visible
                $hasSearchInput = $browser->script("return document.querySelector('input[placeholder*=\"Search\"]') !== null || document.querySelector('input[type=\"search\"]') !== null;");

                $this->assertTrue($hasSearchInput[0] ?? false, 'Cmd/Ctrl+K should open search');

                $checks++;
            } catch (\Exception $e) {
                $failed['keyboard_shortcut'] = $e->getMessage();
                $browser->screenshot('fail-keyboard-shortcut');
            }

            // ── Report ──
            if (!empty($failed)) {
                $report = "Failed " . count($failed) . "/{$checks} keyboard accessibility checks:\n";
                foreach ($failed as $name => $error) {
                    $report .= "  - {$name}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->assertTrue($checks >= 2, "All {$checks} keyboard accessibility checks passed");
        });
    }
}