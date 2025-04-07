<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use MicroweberPackages\User\Models\User;

class SidebarTest extends DuskTestCase
{
    public function testSidebarNavigationVisible()
    {
        $this->browse(function (Browser $browser) {
            // 1. Login and maximize window
            $user = User::where('email', 'admin@example.com')->first();
            $this->actingAs($user);
            
            $browser->driver->manage()->window()->maximize();

            // 2. Visit admin and verify sidebar
            $browser->visit('/admin')
                   ->pause(2000) // Wait for sidebar animation
                   
                   // 3. Check main sidebar elements
                   ->assertPresent('nav.filament-sidebar')
                   ->assertSeeIn('nav.filament-sidebar', 'Dashboard')
                   ->assertSeeIn('nav.filament-sidebar', 'Content')
                   ->assertSeeIn('nav.filament-sidebar', 'Settings')
                   
                   // 4. Verify sidebar is interactable
                   ->click('@filament.sidebar.group.toggle') // Toggle a group
                   ->pause(500) // Animation
                   ->assertVisible('@filament.sidebar.item'); // Verify items
        });
    }
}