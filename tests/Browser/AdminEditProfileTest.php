<?php

namespace Tests\Browser;

use App\Models\User;
use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Components\AdminLogin;
use Tests\Browser\Components\ChekForJavascriptErrors;
use Tests\DuskTestCase;

class AdminEditProfileTest extends DuskTestCase
{
    public function testEditProfile()
    {

        $this->browse(function (Browser $browser) {

            $browser->within(new AdminLogin, function ($browser) {
                $browser->fillForm();
            });

            $browser->visit(admin_url());

            $browser->waitForText('Users');
            $browser->clickLink('Users');

            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });

            $browser->pause(2000);
            $browser->waitForText('Edit profile');
            $browser->clickLink('Edit profile');

            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });

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

    public function testAddNewAdminProfile()
    {
        $this->browse(function (Browser $browser) {


            $browser->within(new AdminLogin, function ($browser) {
                $browser->fillForm();
            });


            $browser->waitForText('Users');
            $browser->clickLink('Users');

            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });

            $browser->pause(2000);
            $browser->waitForText('Add new user');
            $browser->clickLink('Add new user');

            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });

            $browser->pause(3000);
            $browser->waitForText('First Name');
            $browser->pause(3000);
            $browser->scrollTo('#advanced-settings');

            $faker = Factory::create('en_US');

            $new_username = $faker->userName;
            $new_email = $faker->email;
            $new_password = $faker->password;
            $first_name = $faker->firstName;
            $last_name = $faker->lastName;
            $phone   = $faker->phoneNumber;


            $browser->type('username', $new_username);
            $browser->type('password', $new_password);
            $browser->type('first_name', $first_name);
             $browser->type('last_name', $last_name);
            $browser->type('phone', $phone);
            $browser->type('email', $new_email);
            $browser->select('is_admin', 1);
            $browser->click('label[for="is_active1"]');

         //   $browser->press('Save');
         //   $browser->scrollTo('#user-save-button');

            $browser->script("$('html, body').animate({ scrollTop: $('#user-save-button').offset().top - 30 }, 0);");

            $browser->click('button[id="user-save-button"]');

           // $browser->click('#user-save-button');

            $browser->pause(3000);

            $findUser = User::where('email', $new_email)->first();

            $this->assertEquals($new_username, $findUser->username);
            $this->assertEquals($new_email, $findUser->email);
            $this->assertEquals($first_name, $findUser->first_name);
            $this->assertEquals($last_name, $findUser->last_name);
            $this->assertEquals($phone, $findUser->phone);
            $this->assertEquals('1', $findUser->is_active);
            $this->assertEquals('1', $findUser->is_admin);


        });
    }
}
