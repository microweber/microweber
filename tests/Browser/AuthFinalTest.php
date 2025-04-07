<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AuthFinalTest extends DuskTestCase
{
    public function testAdminLogin()
    {
        $this->browse(function (Browser $browser) {
            // Verify login page loads
            $browser->visit('/admin/login')
                   ->assertPresent('input[type="email"]')
                   ->assertPresent('input[type="password"]')
                   ->assertPresent('button[type="submit"]');

            // Get and log CSRF token
            $token = $browser->script("return document.querySelector('meta[name=\"csrf-token\"]').content")[0];
            file_put_contents(storage_path('dusk_csrf.log'), "CSRF Token: $token");

            // Attempt login
            $browser->type('input[type="email"]', 'admin@example.com')
                   ->type('input[type="password"]', 'password')
                   ->click('button[type="submit"]')
                   ->pause(3000);

            // Debug session state
            $session = $browser->script("
                return {
                    cookies: document.cookie,
                    localStorage: JSON.stringify(localStorage),
                    sessionStorage: JSON.stringify(sessionStorage)
                }
            ")[0];
            file_put_contents(storage_path('dusk_session.log'), print_r($session, true));

            // Verify successful login
            $browser->assertPathIs('/admin');
        });
    }
}