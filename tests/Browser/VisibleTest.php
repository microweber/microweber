<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use MicroweberPackages\User\Models\User;

class VisibleTest extends DuskTestCase
{
    public function testVisibleBrowser()
    {
        $this->browse(function (Browser $browser) {
            // Configure visible browser
            $browser->driver->manage()->window()->maximize();
            
            // Manual login
            $user = User::where('email', 'admin@example.com')->first();
            $this->actingAs($user);

            // Test with visible browser
            $browser->visit('/admin')
                   ->pause(5000) // 5 second pause to observe
                   ->assertPathIs('/admin');
        });
    }
}