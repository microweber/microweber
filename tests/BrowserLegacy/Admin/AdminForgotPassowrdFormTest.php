<?php

namespace Tests\Browser\Admin;

use PHPUnit\Framework\Attributes\Test;

use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Laravel\Dusk\Browser;
use MicroweberPackages\User\Models\PasswordReset;
use MicroweberPackages\User\Models\User;
use Tests\Browser\Components\AdminLogin;
use Tests\Browser\Components\ChekForJavascriptErrors;
use Tests\DuskTestCase;

class AdminForgotPassowrdFormTest extends DuskTestCase
{

    #[Test]

    public function it_submit_email(): void {


        $data = [];
        $data['option_value'] = 1;
        $data['option_key'] = 'captcha_disabled';
        $data['option_group'] = 'users';
        save_option($data);

        $captcha_disabled = get_option('captcha_disabled', 'users') == 1;
        $this->assertTrue($captcha_disabled);

        $siteUrl = $this->siteUrl;

        $this->browse(function (Browser $browser) use($siteUrl) {

            $user = User::where('username', 1)->first();
            $user->email = 'bobi@microweber.com';
            $user->save();



            $browser->visit($siteUrl . 'admin/login');
            $browser->pause('2000');

            $data = [];
            $data['option_value'] = 1;
            $data['option_key'] = 'captcha_disabled';
            $data['option_group'] = 'users';
            save_option($data);


            $browser->click('@forgot-password-link');
            $browser->pause('3000');


            $browser->type('email', 'bobi@microweber.com');
            $browser->click('@reset-password-button');
            $browser->pause('4000');


            $browser->waitForText('We have emailed your password reset link',30);
            $browser->assertSee('We have emailed your password reset link');

            $sendTime = Carbon::now();

            $findPasswordReset = PasswordReset::where('email', 'bobi@microweber.com')->orderBy('created_at', 'DESC')->first();
            $this->assertNotEmpty($findPasswordReset);
            $this->assertTrue($sendTime > $findPasswordReset->created_at);

            $browser->visit($siteUrl . 'reset-password/'.$findPasswordReset->token.'?email=bobi@microweber.com');

            $browser->waitForText('Reset Password');
            $browser->assertSee('Reset Password');

            $browser->type('password', '1234');
            $browser->type('password_confirmation', '1234');

            $browser->click('.js-submit-change-password');

            $browser->pause('4000');

            $browser->visit(logout_url());
            $browser->pause('4000');

            $browser->visit($siteUrl . 'admin/login');

            $browser->within(new AdminLogin, function ($browser) {
                $browser->fillForm(1,1234);
            });

            $browser->visit(logout_url());
            $browser->pause('4000');

            // Reset to old password
            $user = User::where('username', 1)->first();
            $user->password = Hash::make(1);
            $user->save();

        });
    }

    #[Test]

    public function it_submit_wrong_email(): void {
        $siteUrl = $this->siteUrl;

        $this->browse(function (Browser $browser) use($siteUrl) {

            $data = [];
            $data['option_value'] = 1;
            $data['option_key'] = 'captcha_disabled';
            $data['option_group'] = 'users';
            save_option($data);

            $browser->visit($siteUrl . 'admin/login');
            $browser->pause('2000');

            $browser->click('@forgot-password-link');
            $browser->pause('3000');

            $browser->type('email', 'wrong-email@microweber.com');
            $browser->pause('3000');

            $browser->click('@reset-password-button');
            $browser->pause('6000');

            $browser->waitForText('We can\'t find a user with that email address');
            $browser->assertSee('We can\'t find a user with that email address');
        });
    }


    #[Test]


    public function it_captcha_validation(): void {

        $siteUrl = $this->siteUrl;

        $this->browse(function (Browser $browser) use($siteUrl) {

            $data = [];
            $data['option_value'] = 0;
            $data['option_key'] = 'captcha_disabled';
            $data['option_group'] = 'users';
            save_option($data);

            $browser->visit($siteUrl . 'admin/login');
            $browser->pause('2000');

            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });

            $browser->click('@forgot-password-link');
            $browser->pause('3000');

            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });

            $browser->type('email', 'bobi@microweber.com');
            $browser->type('[name="captcha"]', '1234');

             $browser->click('@reset-password-button');
            $browser->pause('4000');

            $browser->waitForText('Invalid captcha answer');
            $browser->assertSee('Invalid captcha answer');

        });
    }
}
