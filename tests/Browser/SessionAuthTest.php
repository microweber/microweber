<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class SessionAuthTest extends DuskTestCase
{
    public function testAdminSessionAuthentication()
    {
        $this->browse(function (Browser $browser) {
            // Verify manual login endpoint sets cookies
            $browser->visit('/test-manual-login')
                   ->waitUntil('document.cookie.includes("laravel_session")', 10)
                   ->assertScript('document.cookie.includes("XSRF-TOKEN")');

            // Verify Filament admin access
            $browser->visit('/admin')
                   ->assertPathIs('/admin')
                   ->assertSee('Dashboard');
        });
    }
}