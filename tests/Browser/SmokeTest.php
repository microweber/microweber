<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\DuskTestCase;

/**
 * Simple smoke tests to verify pages load without JS errors
 * 
 * These tests are fast and check that critical pages render properly
 * without requiring complex interactions.
 */
class SmokeTest extends DuskTestCase
{
    /**
     * Test that the home page loads without JS errors
     */
    #[Test]
    public function it_home_page_loads_without_js_errors(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->waitFor('body', 10)
                ->assertSeeIn('body', '');
            
            // Check for JS errors in console
            $logs = $browser->driver->manage()->getLog('browser');
            $errors = array_filter($logs, fn($log) => $log['level'] === 'SEVERE');
            
            $this->assertEmpty($errors, 'JavaScript errors found on home page: ' . json_encode($errors));
        });
    }

    /**
     * Test that shop page loads without JS errors
     */
    #[Test]
    public function it_shop_page_loads_without_js_errors(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/shop')
                ->waitFor('body', 10);
            
            $logs = $browser->driver->manage()->getLog('browser');
            $errors = array_filter($logs, fn($log) => $log['level'] === 'SEVERE');
            
            $this->assertEmpty($errors, 'JavaScript errors found on shop page: ' . json_encode($errors));
        });
    }

    /**
     * Test that login page loads without JS errors
     */
    #[Test]
    public function it_login_page_loads_without_js_errors(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->waitFor('body', 10)
                ->assertSee('Login');
            
            $logs = $browser->driver->manage()->getLog('browser');
            $errors = array_filter($logs, fn($log) => $log['level'] === 'SEVERE');
            
            $this->assertEmpty($errors, 'JavaScript errors found on login page: ' . json_encode($errors));
        });
    }

    /**
     * Test that admin page loads without JS errors
     */
    #[Test]
    public function it_admin_page_loads_without_js_errors(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin')
                ->waitFor('body', 10);
            
            $logs = $browser->driver->manage()->getLog('browser');
            $errors = array_filter($logs, fn($log) => $log['level'] === 'SEVERE');
            
            $this->assertEmpty($errors, 'JavaScript errors found on admin page: ' . json_encode($errors));
        });
    }

    /**
     * Test that registration page loads without JS errors
     */
    #[Test]
    public function it_register_page_loads_without_js_errors(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                ->waitFor('body', 10);
            
            $logs = $browser->driver->manage()->getLog('browser');
            $errors = array_filter($logs, fn($log) => $log['level'] === 'SEVERE');
            
            $this->assertEmpty($errors, 'JavaScript errors found on register page: ' . json_encode($errors));
        });
    }

    /**
     * Test that checkout page loads without JS errors
     */
    #[Test]
    public function it_checkout_page_loads_without_js_errors(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/checkout')
                ->waitFor('body', 10);
            
            $logs = $browser->driver->manage()->getLog('browser');
            $errors = array_filter($logs, fn($log) => $log['level'] === 'SEVERE');
            
            $this->assertEmpty($errors, 'JavaScript errors found on checkout page: ' . json_encode($errors));
        });
    }

    /**
     * Test that cart page loads without JS errors
     */
    #[Test]
    public function it_cart_page_loads_without_js_errors(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/cart')
                ->waitFor('body', 10);
            
            $logs = $browser->driver->manage()->getLog('browser');
            $errors = array_filter($logs, fn($log) => $log['level'] === 'SEVERE');
            
            $this->assertEmpty($errors, 'JavaScript errors found on cart page: ' . json_encode($errors));
        });
    }

    /**
     * Test that search page loads without JS errors
     */
    #[Test]
    public function it_search_page_loads_without_js_errors(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/search')
                ->waitFor('body', 10);
            
            $logs = $browser->driver->manage()->getLog('browser');
            $errors = array_filter($logs, fn($log) => $log['level'] === 'SEVERE');
            
            $this->assertEmpty($errors, 'JavaScript errors found on search page: ' . json_encode($errors));
        });
    }

    /**
     * Test that profile page loads without JS errors
     */
    #[Test]
    public function it_profile_page_loads_without_js_errors(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/profile')
                ->waitFor('body', 10);
            
            $logs = $browser->driver->manage()->getLog('browser');
            $errors = array_filter($logs, fn($log) => $log['level'] === 'SEVERE');
            
            $this->assertEmpty($errors, 'JavaScript errors found on profile page: ' . json_encode($errors));
        });
    }

    /**
     * Test that forgot password page loads without JS errors
     */
    #[Test]
    public function it_forgot_password_page_loads_without_js_errors(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/forgot-password')
                ->waitFor('body', 10);
            
            $logs = $browser->driver->manage()->getLog('browser');
            $errors = array_filter($logs, fn($log) => $log['level'] === 'SEVERE');
            
            $this->assertEmpty($errors, 'JavaScript errors found on forgot password page: ' . json_encode($errors));
        });
    }
}
