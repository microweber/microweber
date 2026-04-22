<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\PageSmokeTrait;
use Tests\DuskTestCase;

/**
 * Simple smoke tests to verify public pages load correctly.
 *
 * Each test delegates to {@see PageSmokeTrait::assertPageSmokeOk()} which
 * verifies:
 *   - HTTP status is not 5xx
 *   - No known error-page markers in the rendered HTML
 *   - No SEVERE JS console errors
 */
class SmokeTest extends DuskTestCase
{
    use PageSmokeTrait;

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
