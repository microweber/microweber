<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Components\AdminLogin;
use Tests\DuskTestCase;

class AdminEditProfileTest extends DuskTestCase
{
    public function testEditProfile()
    {

        $this->browse(function (Browser $browser) {

            $browser->within(new AdminLogin, function ($browser) {
                $browser->fillForm();
            });

            $browser->waitForText('Users');
            $browser->clickLink('Users');

            $browser->pause(2000);
            $browser->waitForText('Edit profile');
            $browser->clickLink('Edit profile');

            $browser->pause(3000);
            $browser->waitForText('First Name');
            $browser->pause(3000);
            $browser->scrollTo('#advanced-settings');

            $browser->type('first_name', 'Visual');
            $browser->type('last_name', 'Test');
            $browser->type('phone', '08812345678');
            $browser->type('email', 'visualtest@microweber.com');

            $browser->press('Save');
            $browser->pause(3000);

            $findUser = User::where('email', 'visualtest@microweber.com')->first();

            $this->assertEquals('Visual', $findUser->first_name);
            $this->assertEquals('Test', $findUser->last_name);
            $this->assertEquals('08812345678', $findUser->phone);

        });
    }
}
