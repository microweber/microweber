<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use MicroweberPackages\User\Models\User;

class SessionFixTest extends DuskTestCase
{
    public function testAdminSessionFix()
    {
        $this->browse(function (Browser $browser) {
            // Manually create and login user
            $user = User::where('email', 'admin@example.com')->first();
            $this->actingAs($user);

            // Directly visit admin dashboard
            $browser->visit('/admin')
                   ->assertPathIs('/admin')
                   ->assertSee('Dashboard');
        });
    }
}