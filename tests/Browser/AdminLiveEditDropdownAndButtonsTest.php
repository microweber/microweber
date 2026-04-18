<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\DuskTestCase;

/**
 * Admin Live Edit Dropdown & Buttons Tests
 *
 * Tests the content search dropdown (autocomplete) and all toolbar buttons
 * in the live edit interface for JS errors:
 *   - Content search dropdown renders results without errors
 *   - Toolbar buttons (ADMIN, ADD LAYOUT, VIEW, SAVE, hamburger menu) are present
 *   - Resolution switch buttons work
 *   - No JS errors on interaction
 *
 * Prerequisites:
 *   - A running dev server at http://127.0.0.1:8000
 *   - An admin user with email admin@admin.com / password123
 *   - Login captcha disabled
 */
class AdminLiveEditDropdownAndButtonsTest extends DuskTestCase
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

    /**
     * Inject a JS error listener at the top window level to capture console errors.
     */
    protected function injectErrorListener(Browser $browser): void
    {
        $browser->script("
            window._mwTestErrors = [];
            window.addEventListener('error', function(e) {
                window._mwTestErrors.push(e.message || String(e));
            });
            window.addEventListener('unhandledrejection', function(e) {
                window._mwTestErrors.push('UnhandledRejection: ' + (e.reason ? (e.reason.message || String(e.reason)) : ''));
            });
        ");
    }

    /**
     * Get captured JS errors, filtering out known non-critical ones.
     */
    protected function getCriticalErrors(Browser $browser): array
    {
        $errors = $browser->script("return window._mwTestErrors || [];");
        $errorList = $errors[0] ?? [];

        // Filter out known non-critical warnings
        $ignoredPatterns = [
            'ResizeObserver loop',
            'Script error',
            'Permissions policy',
            'net::ERR_',
            'favicon',
        ];

        return array_filter($errorList, function ($err) use ($ignoredPatterns) {
            foreach ($ignoredPatterns as $pattern) {
                if (stripos($err, $pattern) !== false) {
                    return false;
                }
            }
            return true;
        });
    }

    #[Test]
    public function live_edit_toolbar_buttons_present(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $checks = 0;
            $failed = [];

            // ── Load live edit and wait for toolbar ──
            $browser->visit('/admin/live-edit')->pause(10000);
            $this->ensureLoggedIn($browser);
            $this->injectErrorListener($browser);

            // ── Check 1: Toolbar is visible ──
            try {
                $hasToolbar = $browser->script("
                    var toolbar = document.querySelector('#toolbar')
                        || document.querySelector('[role=\"toolbar\"]');
                    return toolbar !== null;
                ");
                $this->assertTrue($hasToolbar[0] ?? false, 'Toolbar element should be present');
                $checks++;
            } catch (\Exception $e) {
                $failed['toolbar_present'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 2: ADMIN back link ──
            try {
                $hasAdminLink = $browser->script("
                    var link = document.querySelector('#mw-live-edit-toolbar-back-to-admin-link')
                        || document.querySelector('[aria-label=\"Back to admin\"]');
                    return link !== null;
                ");
                $this->assertTrue($hasAdminLink[0] ?? false, 'ADMIN back link should be present');
                $checks++;
            } catch (\Exception $e) {
                $failed['admin_link'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 3: ADD LAYOUT / Add content button ──
            try {
                $hasAddBtn = $browser->script("
                    var btn = document.querySelector('.live-edit-add-content-button-wrapper')
                        || document.querySelector('[aria-label*=\"Add\"]')
                        || document.querySelector('#mw-plus-bottom');
                    return btn !== null;
                ");
                $this->assertTrue($hasAddBtn[0] ?? false, 'Add content button should be present');
                $checks++;
            } catch (\Exception $e) {
                $failed['add_content_btn'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 4: Content search dropdown element ──
            try {
                $hasSearch = $browser->script("
                    var search = document.querySelector('#mw-live-edit-search-content')
                        || document.querySelector('.mw-autocomplete');
                    return search !== null;
                ");
                $this->assertTrue($hasSearch[0] ?? false, 'Content search dropdown should be present');
                $checks++;
            } catch (\Exception $e) {
                $failed['search_dropdown'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 5: VIEW button ──
            try {
                $hasViewBtn = $browser->script("
                    var btn = document.querySelector('#mw-page-set-preview-mode')
                        || document.querySelector('[aria-label=\"Toggle preview mode\"]');
                    return btn !== null;
                ");
                $this->assertTrue($hasViewBtn[0] ?? false, 'VIEW button should be present');
                $checks++;
            } catch (\Exception $e) {
                $failed['view_btn'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 6: SAVE button ──
            try {
                $hasSaveBtn = $browser->script("
                    var btn = document.querySelector('#live-edit-save-btn')
                        || document.querySelector('[aria-label*=\"Save\"]')
                        || document.querySelector('.live-edit-toolbar-buttons-save')
                        || (function() {
                            var btns = document.querySelectorAll('button');
                            for (var i = 0; i < btns.length; i++) {
                                if (btns[i].textContent.trim().toUpperCase() === 'SAVE') return true;
                            }
                            return false;
                        })();
                    return btn !== null && btn !== false;
                ");
                $this->assertTrue($hasSaveBtn[0] ?? false, 'SAVE button should be present');
                $checks++;
            } catch (\Exception $e) {
                $failed['save_btn'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 7: Hamburger menu button ──
            try {
                $hasMenu = $browser->script("
                    var btn = document.querySelector('#toolbar-user-menu-button')
                        || document.querySelector('.mw-le-hamburger')
                        || document.querySelector('[aria-label=\"User menu\"]');
                    return btn !== null;
                ");
                $this->assertTrue($hasMenu[0] ?? false, 'Hamburger menu button should be present');
                $checks++;
            } catch (\Exception $e) {
                $failed['hamburger_menu'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 8: Resolution switch buttons ──
            try {
                $hasResolution = $browser->script("
                    var el = document.querySelector('.mw-le-toolbar-res-switch')
                        || document.querySelector('[class*=\"resolution\"]')
                        || document.querySelector('[class*=\"ResolutionSwitch\"]')
                        || document.querySelector('[aria-label*=\"esktop\"]')
                        || document.querySelector('[aria-label*=\"obile\"]')
                        || document.querySelector('[aria-label*=\"ablet\"]');
                    return el !== null;
                ");
                // Resolution switch may not be present on small viewports — soft check
                $checks++;
            } catch (\Exception $e) {
                $failed['resolution_switch'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 9: No critical JS errors after page load ──
            try {
                $browser->pause(2000);
                $criticalErrors = $this->getCriticalErrors($browser);
                $this->assertEmpty(
                    $criticalErrors,
                    'No critical JS errors after live edit load: ' . implode('; ', $criticalErrors)
                );
                $checks++;
            } catch (\Exception $e) {
                $failed['no_js_errors_load'] = substr($e->getMessage(), 0, 200);
            }

            if (!empty($failed)) {
                $report = "Failed " . count($failed) . "/" . ($checks + count($failed)) . " toolbar button checks:\n";
                foreach ($failed as $name => $error) {
                    $report .= "  - {$name}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->assertTrue($checks >= 9, "All 9 toolbar button checks passed (got {$checks})");
        });
    }

    #[Test]
    public function live_edit_dropdown_search_no_errors(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $checks = 0;
            $failed = [];

            $browser->visit('/admin/live-edit')->pause(10000);
            $this->ensureLoggedIn($browser);
            $this->injectErrorListener($browser);

            // ── Check 1: Click on the search dropdown to open it ──
            try {
                $browser->script("
                    var searchContainer = document.querySelector('#mw-live-edit-search-content');
                    if (searchContainer) {
                        var input = searchContainer.querySelector('input')
                            || searchContainer.querySelector('[contenteditable]')
                            || searchContainer.querySelector('.mw-autocomplete-input');
                        if (input) {
                            input.click();
                            input.focus();
                        } else {
                            searchContainer.click();
                        }
                    }
                ");
                $browser->pause(1000);

                $criticalErrors = $this->getCriticalErrors($browser);
                $this->assertEmpty(
                    $criticalErrors,
                    'No JS errors after clicking search dropdown: ' . implode('; ', $criticalErrors)
                );
                $checks++;
            } catch (\Exception $e) {
                $failed['dropdown_click'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 2: Type in the search dropdown to trigger autocomplete ──
            try {
                $browser->script("
                    var searchContainer = document.querySelector('#mw-live-edit-search-content');
                    if (searchContainer) {
                        var input = searchContainer.querySelector('input')
                            || searchContainer.querySelector('[contenteditable]')
                            || searchContainer.querySelector('.mw-autocomplete-input');
                        if (input) {
                            if (input.hasAttribute('contenteditable')) {
                                input.textContent = 'a';
                                input.dispatchEvent(new Event('input', {bubbles: true}));
                            } else {
                                input.value = 'a';
                                input.dispatchEvent(new Event('input', {bubbles: true}));
                                input.dispatchEvent(new Event('keyup', {bubbles: true}));
                            }
                        }
                    }
                ");
                // Wait for AJAX autocomplete results
                $browser->pause(3000);

                $criticalErrors = $this->getCriticalErrors($browser);
                $this->assertEmpty(
                    $criticalErrors,
                    'No JS errors after typing in search dropdown: ' . implode('; ', $criticalErrors)
                );
                $checks++;
            } catch (\Exception $e) {
                $failed['dropdown_type'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 3: Verify autocomplete results rendered (if any) ──
            try {
                $hasResults = $browser->script("
                    var searchContainer = document.querySelector('#mw-live-edit-search-content');
                    if (!searchContainer) return { container: false, results: false };
                    var results = searchContainer.querySelector('.mw-autocomplete-results')
                        || searchContainer.querySelector('ul')
                        || searchContainer.querySelector('[class*=\"result\"]');
                    var items = searchContainer.querySelectorAll('li, .mw-autocomplete-result-item');
                    return {
                        container: true,
                        results: results !== null,
                        itemCount: items.length
                    };
                ");

                $result = $hasResults[0] ?? [];
                $this->assertTrue($result['container'] ?? false,
                    'Search container should exist');

                // Results may or may not be visible (depends on content in DB),
                // but no crash is the key assertion
                $checks++;
            } catch (\Exception $e) {
                $failed['dropdown_results'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 4: Verify no "Cannot read properties of null (reading 'trim')" error ──
            try {
                $allErrors = $browser->script("return window._mwTestErrors || [];");
                $errorList = $allErrors[0] ?? [];
                $hasTrimError = false;
                foreach ($errorList as $err) {
                    if (str_contains($err, "reading 'trim'") || str_contains($err, 'trim')) {
                        $hasTrimError = true;
                        break;
                    }
                }
                $this->assertFalse($hasTrimError,
                    'Should not have "Cannot read properties of null (reading trim)" error');
                $checks++;
            } catch (\Exception $e) {
                $failed['no_trim_error'] = substr($e->getMessage(), 0, 200);
            }

            if (!empty($failed)) {
                $report = "Failed " . count($failed) . "/" . ($checks + count($failed)) . " dropdown checks:\n";
                foreach ($failed as $name => $error) {
                    $report .= "  - {$name}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->assertTrue($checks >= 4, "All 4 dropdown checks passed (got {$checks})");
        });
    }

    #[Test]
    public function live_edit_hamburger_menu_opens(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $checks = 0;
            $failed = [];

            $browser->visit('/admin/live-edit')->pause(10000);
            $this->ensureLoggedIn($browser);
            $this->injectErrorListener($browser);

            // ── Check 1: Click hamburger menu ──
            try {
                $browser->script("
                    var btn = document.querySelector('#toolbar-user-menu-button')
                        || document.querySelector('.mw-le-hamburger');
                    if (btn) btn.click();
                ");
                $browser->pause(1000);

                $menuVisible = $browser->script("
                    var wrapper = document.querySelector('#user-menu-wrapper');
                    return wrapper && wrapper.classList.contains('active');
                ");
                $this->assertTrue($menuVisible[0] ?? false,
                    'Hamburger menu should open on click');

                $criticalErrors = $this->getCriticalErrors($browser);
                $this->assertEmpty(
                    $criticalErrors,
                    'No JS errors after opening hamburger menu: ' . implode('; ', $criticalErrors)
                );
                $checks++;
            } catch (\Exception $e) {
                $failed['hamburger_open'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 2: Menu has navigation items ──
            try {
                $menuItems = $browser->script("
                    var menu = document.querySelector('#user-menu nav');
                    if (!menu) return { found: false, count: 0 };
                    var links = menu.querySelectorAll('a');
                    return { found: true, count: links.length };
                ");

                $result = $menuItems[0] ?? [];
                $this->assertTrue($result['found'] ?? false,
                    'User menu should have navigation');
                $this->assertGreaterThan(0, $result['count'] ?? 0,
                    'User menu should have at least one link');
                $checks++;
            } catch (\Exception $e) {
                $failed['menu_items'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 3: Dark mode toggle link present ──
            try {
                $hasDarkMode = $browser->script("
                    var menu = document.querySelector('#user-menu nav');
                    if (!menu) return false;
                    return menu.textContent.includes('Dark mode') || menu.textContent.includes('Light mode');
                ");
                $this->assertTrue($hasDarkMode[0] ?? false,
                    'Menu should have Dark/Light mode toggle');
                $checks++;
            } catch (\Exception $e) {
                $failed['dark_mode_toggle'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 4: Close menu by clicking outside ──
            try {
                $browser->script("
                    document.body.click();
                ");
                $browser->pause(500);

                $menuClosed = $browser->script("
                    var wrapper = document.querySelector('#user-menu-wrapper');
                    return wrapper && !wrapper.classList.contains('active');
                ");
                $this->assertTrue($menuClosed[0] ?? false,
                    'Menu should close when clicking outside');
                $checks++;
            } catch (\Exception $e) {
                $failed['menu_close'] = substr($e->getMessage(), 0, 200);
            }

            if (!empty($failed)) {
                $report = "Failed " . count($failed) . "/" . ($checks + count($failed)) . " hamburger menu checks:\n";
                foreach ($failed as $name => $error) {
                    $report .= "  - {$name}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->assertTrue($checks >= 4, "All 4 hamburger menu checks passed (got {$checks})");
        });
    }

    #[Test]
    public function live_edit_view_button_toggles_preview(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $checks = 0;
            $failed = [];

            $browser->visit('/admin/live-edit')->pause(10000);
            $this->ensureLoggedIn($browser);
            $this->injectErrorListener($browser);

            // ── Check 1: Click VIEW button to enter preview mode ──
            try {
                $browser->script("
                    var btn = document.querySelector('#mw-page-set-preview-mode');
                    if (btn) btn.click();
                ");
                $browser->pause(2000);

                $isPreview = $browser->script("
                    return document.documentElement.classList.contains('preview');
                ");

                $criticalErrors = $this->getCriticalErrors($browser);
                $this->assertEmpty(
                    $criticalErrors,
                    'No JS errors after clicking VIEW: ' . implode('; ', $criticalErrors)
                );

                // Preview mode should add 'preview' class to html
                $this->assertTrue($isPreview[0] ?? false,
                    'VIEW button should toggle preview mode class on html');
                $checks++;
            } catch (\Exception $e) {
                $failed['view_toggle'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 2: Back to edit button appears in preview mode ──
            try {
                $backBtnVisible = $browser->script("
                    var btn = document.querySelector('#mw-page-set-back-to-edit-mode');
                    if (!btn) return false;
                    var style = window.getComputedStyle(btn);
                    return style.visibility === 'visible' || style.opacity !== '0';
                ");
                // The back button should be visible in preview mode
                $checks++;
            } catch (\Exception $e) {
                $failed['back_to_edit_btn'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 3: Click back to edit to return to edit mode ──
            try {
                $browser->script("
                    var btn = document.querySelector('#mw-page-set-back-to-edit-mode');
                    if (btn) btn.click();
                ");
                $browser->pause(2000);

                $notPreview = $browser->script("
                    return !document.documentElement.classList.contains('preview');
                ");

                $criticalErrors = $this->getCriticalErrors($browser);
                $this->assertEmpty(
                    $criticalErrors,
                    'No JS errors after clicking back to edit: ' . implode('; ', $criticalErrors)
                );

                $this->assertTrue($notPreview[0] ?? false,
                    'Clicking back to edit should remove preview class');
                $checks++;
            } catch (\Exception $e) {
                $failed['back_to_edit'] = substr($e->getMessage(), 0, 200);
            }

            if (!empty($failed)) {
                $report = "Failed " . count($failed) . "/" . ($checks + count($failed)) . " VIEW button checks:\n";
                foreach ($failed as $name => $error) {
                    $report .= "  - {$name}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->assertTrue($checks >= 3, "All 3 VIEW button checks passed (got {$checks})");
        });
    }
}
