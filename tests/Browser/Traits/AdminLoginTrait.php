<?php

namespace Tests\Browser\Traits;

use Laravel\Dusk\Browser;

/**
 * Shared admin login helpers for Dusk browser tests.
 *
 * Provides loginAsAdmin(), ensureLoggedIn(), dismissAlerts(),
 * injectErrorListener(), and getCriticalErrors() so that every
 * test file doesn't need its own copy.
 */
trait AdminLoginTrait
{
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
                ->type('input[type="password"]', 'admin')
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
}
