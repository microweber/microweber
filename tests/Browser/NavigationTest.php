<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use MicroweberPackages\User\Models\User;

class NavigationTest extends DuskTestCase
{
    public function testAdminNavigationVisible()
    {
        $this->browse(function (Browser $browser) {
            // 1. Login and maximize window
            $user = User::where('email', 'admin@example.com')->first();
            $this->actingAs($user);
            
            $browser->driver->manage()->window()->maximize();

            // 2. Visit admin with debugging
            $browser->visit('/admin')
                   ->pause(3000)
                   ->screenshot('admin-page-loaded')
                   
                   // 3. Basic navigation checks
                   ->assertPresent('[role="navigation"]') // Filament 3.x sidebar
                   ->assertPresent('aside') // Alternative sidebar selector
                   ->screenshot('after-nav-check')
                   
                   // 4. Verify expected menu items
                   ->with('[role="navigation"]', function($nav) {
                       $nav->assertSee('Dashboard')
                           ->assertSee('Content');
                   });
        });
    }
}