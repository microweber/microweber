<?php

namespace Tests\Browser\Traits;

use Illuminate\Support\Facades\Http;
use Laravel\Dusk\Browser;

/**
 * Shared page smoke-check helpers for Dusk browser tests.
 *
 * Two entry points:
 *
 *  - {@see assertPageSmokeOk()} — full check for public pages: issues a
 *    plain HTTP GET (fails on 5xx), visits the page in the browser,
 *    scans the rendered HTML for known error-page markers, and verifies
 *    no SEVERE JS console errors fired.
 *
 *  - {@see assertPageHasNoErrorMarkers()} — lightweight check for pages
 *    that are already loaded in the browser (typically admin pages
 *    after login). Scans the page source for the same error-page
 *    marker list without re-visiting or hitting the HTTP layer.
 */
trait PageSmokeTrait
{
    /**
     * Error-page strings that indicate a server-side failure even when
     * the HTTP status is 200 (Laravel sometimes renders error pages
     * inline, and Filament can surface tracebacks on a rendered page).
     */
    protected static array $errorPageMarkers = [
        'Whoops, looks like something went wrong',
        'Internal Server Error',
        'Server Error (500)',
        'SQLSTATE[',
        'Stack trace:',
        'ParseError',
        'ErrorException',
        'Symfony\\Component\\ErrorHandler',
    ];

    /**
     * Full smoke check for a public page: HTTP status < 500, no
     * error-page markers in the HTML, no SEVERE JS console errors.
     */
    protected function assertPageSmokeOk(Browser $browser, string $path, ?string $label = null): void
    {
        $label ??= $path;
        $url = rtrim(config('app.url'), '/') . $path;

        $response = Http::withoutVerifying()
            ->withOptions(['http_errors' => false, 'allow_redirects' => true])
            ->timeout(15)
            ->get($url);

        $this->assertLessThan(
            500,
            $response->status(),
            "{$label} at {$path} returned HTTP {$response->status()} (server error)"
        );

        $browser->visit($path)->assertSourceHas('<body');

        $this->assertPageHasNoErrorMarkers($browser, $label);

        $logs = $browser->driver->manage()->getLog('browser');
        $errors = array_filter($logs, fn ($log) => ($log['level'] ?? null) === 'SEVERE');
        $this->assertEmpty(
            $errors,
            "JavaScript errors on {$label}: " . json_encode(array_values($errors))
        );
    }

    /**
     * Scan the currently-loaded page's source for known error-page markers.
     * Does not navigate, hit HTTP, or read console logs — safe to call on
     * admin pages that have their own login/load workflow.
     */
    protected function assertPageHasNoErrorMarkers(Browser $browser, string $label): void
    {
        $pageSource = $browser->driver->getPageSource();
        foreach (self::$errorPageMarkers as $marker) {
            $this->assertStringNotContainsString(
                $marker,
                $pageSource,
                "{$label} contains error-page marker: {$marker}"
            );
        }
    }
}
