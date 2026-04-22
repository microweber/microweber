<?php

namespace Tests\Browser\Traits;

use Laravel\Dusk\Browser;
use Tests\Browser\Traits\PageSmokeTrait;

/**
 * Shared admin login + settings-page helpers for Dusk browser tests.
 *
 * Login state is cached statically so the chrome instance reused across
 * test classes (see {@see \Tests\DuskTestCase}) doesn't perform a fresh
 * login on every test method. Settings-page helpers
 * ({@see visitAndVerifySettings()}, {@see getFormStats()},
 * {@see clickThroughTabs()}, {@see visitAndAssertNoErrors()}) are
 * deduplicated from the per-module use-case test files.
 */
trait AdminLoginTrait
{
    use PageSmokeTrait;

    protected function loginAsAdmin(Browser $browser): void
    {
        if (\Tests\DuskTestCase::$adminLoggedIn) {
            return;
        }

        $browser->visit('/admin/login')->pause(2000);

        $currentUrl = $browser->driver->getCurrentURL();
        if (!str_contains($currentUrl, '/login')) {
            \Tests\DuskTestCase::$adminLoggedIn = true;
            return;
        }

        for ($attempt = 1; $attempt <= 5; $attempt++) {
            $browser->waitFor('input[type="email"]', 10)
                ->clear('input[type="email"]')
                ->type('input[type="email"]', 'admin@admin.com')
                ->clear('input[type="password"]')
                ->type('input[type="password"]', 'admin')
                ->click('button[type="submit"]')
                ->pause(5000);

            $url = $browser->driver->getCurrentURL();
            if (!str_contains($url, '/login')) {
                \Tests\DuskTestCase::$adminLoggedIn = true;
                return;
            }

            $rateLimited = $browser->script("return document.body.innerText.includes('Too many') || document.body.innerText.includes('throttle');");
            if ($rateLimited[0] ?? false) {
                $browser->pause(10000);
                continue;
            }

            break;
        }

        $url = $browser->driver->getCurrentURL();
        $this->assertStringNotContainsString('/login', $url, 'Login failed — still on login page');
        \Tests\DuskTestCase::$adminLoggedIn = true;
    }

    protected function ensureLoggedIn(Browser $browser): void
    {
        $currentUrl = $browser->driver->getCurrentURL();
        if (str_contains($currentUrl, '/login') || !str_contains($currentUrl, '/admin')) {
            // Session expired or we were redirected — invalidate the cache and log back in.
            \Tests\DuskTestCase::$adminLoggedIn = false;
            $this->loginAsAdmin($browser);
        }
    }

    protected function dismissAlerts(Browser $browser): void
    {
        try {
            $browser->driver->switchTo()->alert()->accept();
        } catch (\Exception $e) {
            // No alert present
        }
    }

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

    protected function getCriticalErrors(Browser $browser): array
    {
        $errors = $browser->script("return window._mwTestErrors || [];");
        $errorList = $errors[0] ?? [];

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

    /**
     * Visit an admin settings page and assert no server-side errors are rendered.
     * Re-logs in if the session was lost between tests.
     */
    protected function visitAndAssertNoErrors(Browser $browser, string $slug): void
    {
        $browser->visit("/admin/{$slug}")->pause(3000);
        $this->ensureLoggedIn($browser);

        $this->assertPageHasNoErrorMarkers($browser, "Page /admin/{$slug}");
    }

    /**
     * Collect structural stats about the current settings page: input count,
     * tab count, table/media/repeater presence, and whether a Livewire
     * component is mounted. Returns the superset of fields used across
     * the per-module use-case tests.
     */
    protected function getFormStats(Browser $browser): array
    {
        $check = $browser->script("
            try {
                var inputs = document.querySelectorAll('input:not([type=\"hidden\"]), select, textarea, .fi-toggle, .fi-input');
                var tabs = document.querySelectorAll('[role=\"tab\"]');
                var selects = document.querySelectorAll('select, .fi-fo-select');
                var urlInputs = document.querySelectorAll('input[type=\"url\"], input[type=\"text\"]');
                var mediaInputs = document.querySelectorAll('[class*=\"media\"], [class*=\"image\"], [class*=\"file\"], [class*=\"upload\"]');
                var tables = document.querySelectorAll('.fi-ta, table');
                var addButtons = document.querySelectorAll('[class*=\"repeater\"] button, .fi-ac-action, button');
                var saveButtons = document.querySelectorAll('button[type=\"submit\"]');
                return {
                    inputCount: inputs.length,
                    tabCount: tabs.length,
                    selectCount: selects.length,
                    urlInputCount: urlInputs.length,
                    hasMediaPicker: mediaInputs.length > 0,
                    tableCount: tables.length,
                    addButtonCount: addButtons.length,
                    saveButtonCount: saveButtons.length,
                    hasWireId: document.querySelector('[wire\\\\:id]') !== null
                };
            } catch(e) {
                return {
                    inputCount: 0, tabCount: 0, selectCount: 0, urlInputCount: 0,
                    hasMediaPicker: false, tableCount: 0, addButtonCount: 0,
                    saveButtonCount: 0, hasWireId: false
                };
            }
        ");

        return $check[0] ?? [];
    }

    /**
     * Visit a settings page, assert no errors, and return the form stats.
     */
    protected function visitAndVerifySettings(Browser $browser, string $slug): array
    {
        $this->visitAndAssertNoErrors($browser, $slug);
        return $this->getFormStats($browser);
    }

    /**
     * Click through the first few tabs on a settings page, asserting each
     * tab doesn't surface a server-side error.
     */
    protected function clickThroughTabs(Browser $browser, int $tabCount, string $context): void
    {
        for ($i = 0; $i < min($tabCount, 6); $i++) {
            $browser->script("
                var tabs = document.querySelectorAll('[role=\"tab\"]');
                if (tabs[{$i}]) tabs[{$i}].click();
            ");
            $browser->pause(1500);

            $pageSource = $browser->driver->getPageSource();
            $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                "{$context} tab {$i} should not cause 500");
        }
    }
}
