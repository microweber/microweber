<?php

namespace Tests\Browser\Components;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Component as BaseComponent;

class AdminLogin extends BaseComponent
{
    /**
     * Get the root selector for the component.
     *
     * @return string
     */
    public function selector()
    {
        return '#mw-login';
    }

    /**
     * Assert that the browser page contains the component.
     *
     * @param  Browser  $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->assertVisible($this->selector());
    }

    /**
     * Get the element shortcuts for the component.
     *
     * @return array
     */
    public function elements()
    {
        return [];
    }

    public function fillForm(Browser $browser, $username = 1, $password = 1)
    {
        $data = [];
        $data['option_value'] = 'n';
        $data['option_key'] = 'login_captcha_enabled';
        $data['option_group'] = 'users';
        save_option($data);

        if (!is_logged()) {

            $browser->visit(route('admin.login'));

            $browser->waitForText('Login');
            $browser->waitFor('@login-button');

            // Login to admin panel
            $browser->type('username', $username);
            $browser->type('password', $password);

            $browser->pause(400);
            $browser->click('@login-button');

            // Wait for redirect after login
            $browser->waitForLocation('/admin/', 120);
            $browser->pause(100);
        }
    }
}
