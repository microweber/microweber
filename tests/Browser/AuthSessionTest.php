<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AuthSessionTest extends DuskTestCase
{
    public function testLoginSessionPersistence()
    {
        $this->browse(function (Browser $browser) {
            // Initial visit and debug
            $browser->visit('/admin/login')
                   ->dump()
                   ->assertSee('Login');

            // Get CSRF token
            $token = $browser->script("return document.querySelector('meta[name=\"csrf-token\"]').content")[0];
            file_put_contents(storage_path('csrf.log'), "CSRF Token: $token");

            // Login attempt
            $browser->type('input[type="email"]', 'admin@example.com')
                   ->type('input[type="password"]', 'password')
                   ->click('button[type="submit"]')
                   ->pause(3000);

            // Debug session and cookies
            $browser->script("
                console.log('Post-Login Cookies:', document.cookie);
                fetch('/session-debug', {
                    method: 'GET',
                    credentials: 'same-origin'
                }).then(r => r.text()).then(t => console.log('Session:', t));
            ");

            // Verify admin dashboard
            $browser->assertPathIs('/admin')
                   ->dump();
        });
    }
}