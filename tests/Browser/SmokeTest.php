<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\Http;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\DuskTestCase;

/**
 * Simple smoke tests to verify pages load correctly.
 *
 * Each page is exercised on three axes:
 *   - HTTP status: a plain GET must not return 5xx (5xx indicates a server
 *     error; 4xx is allowed for pages that require auth or don't exist in
 *     every site config).
 *   - Error-page markers: the rendered HTML must not contain known Laravel
 *     error-page strings (Whoops, stack trace headers, "Internal Server
 *     Error", etc.).
 *   - Browser console: no SEVERE JS errors while the page renders.
 */
class SmokeTest extends DuskTestCase
{
    /**
     * Error-page strings that indicate a server-side failure even when the
     * HTTP status is 200 (Laravel sometimes renders error pages inline).
     */
    private const ERROR_PAGE_MARKERS = [
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
     * Smoke-check a single URL: HTTP status not 5xx, no error-page markers
     * in the HTML, no SEVERE JS console errors.
     */
    private function assertPageSmokeOk(Browser $browser, string $path, string $label): void
    {
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

        $pageSource = $browser->driver->getPageSource();
        foreach (self::ERROR_PAGE_MARKERS as $marker) {
            $this->assertStringNotContainsString(
                $marker,
                $pageSource,
                "{$label} at {$path} contains error-page marker: {$marker}"
            );
        }

        $logs = $browser->driver->manage()->getLog('browser');
        $errors = array_filter($logs, fn ($log) => $log['level'] === 'SEVERE');
        $this->assertEmpty(
            $errors,
            "JavaScript errors on {$label}: " . json_encode(array_values($errors))
        );
    }

    #[Test]
    public function it_home_page_loads_without_js_errors(): void
    {
        $this->browse(fn (Browser $browser) => $this->assertPageSmokeOk($browser, '/', 'home page'));
    }

    #[Test]
    public function it_shop_page_loads_without_js_errors(): void
    {
        $this->browse(fn (Browser $browser) => $this->assertPageSmokeOk($browser, '/shop', 'shop page'));
    }

    #[Test]
    public function it_login_page_loads_without_js_errors(): void
    {
        $this->browse(fn (Browser $browser) => $this->assertPageSmokeOk($browser, '/login', 'login page'));
    }

    #[Test]
    public function it_admin_page_loads_without_js_errors(): void
    {
        $this->browse(fn (Browser $browser) => $this->assertPageSmokeOk($browser, '/admin', 'admin page'));
    }

    #[Test]
    public function it_register_page_loads_without_js_errors(): void
    {
        $this->browse(fn (Browser $browser) => $this->assertPageSmokeOk($browser, '/register', 'register page'));
    }

    #[Test]
    public function it_checkout_page_loads_without_js_errors(): void
    {
        $this->browse(fn (Browser $browser) => $this->assertPageSmokeOk($browser, '/checkout', 'checkout page'));
    }

    #[Test]
    public function it_cart_page_loads_without_js_errors(): void
    {
        $this->browse(fn (Browser $browser) => $this->assertPageSmokeOk($browser, '/cart', 'cart page'));
    }

    #[Test]
    public function it_search_page_loads_without_js_errors(): void
    {
        $this->browse(fn (Browser $browser) => $this->assertPageSmokeOk($browser, '/search', 'search page'));
    }

    #[Test]
    public function it_profile_page_loads_without_js_errors(): void
    {
        $this->browse(fn (Browser $browser) => $this->assertPageSmokeOk($browser, '/profile', 'profile page'));
    }

    #[Test]
    public function it_forgot_password_page_loads_without_js_errors(): void
    {
        $this->browse(fn (Browser $browser) => $this->assertPageSmokeOk($browser, '/forgot-password', 'forgot password page'));
    }
}
