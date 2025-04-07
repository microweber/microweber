<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AuthDebugTest extends DuskTestCase
{
    public function testLoginFlow()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                   ->dump() // Initial state
                   ->type('input[type="email"]', 'admin@example.com')
                   ->type('input[type="password"]', 'password')
                   ->click('button[type="submit"]')
                   ->pause(3000)
                   ->dump() // Post-login state
                   ->assertPathIs('/admin');
        });
    }
}