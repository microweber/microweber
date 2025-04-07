<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ManualAuthTest extends DuskTestCase
{
    public function testAdminLoginViaSession()
    {
        $this->browse(function (Browser $browser) {
            // 1. First authenticate via our manual endpoint
            $browser->visit('/test-manual-login')
                   ->assertSee('"success":true');

            // 2. Then verify Filament dashboard access
            $browser->visit('/admin')
                   ->assertPathIs('/admin')
                   ->dump();
            
            // 3. Verify admin UI elements are present
            $browser->assertSee('Dashboard');
        });
    }
}