<?php

namespace Tests\Browser\Components;

use Facebook\WebDriver\WebDriverBy;
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
        return '';
    }

    /**
     * Assert that the browser page contains the component.
     *
     * @param  Browser  $browser
     * @return void
     */
    public function assert(Browser $browser)
    {

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
        $browser->within(new AdminMakeInstall(), function ($browser) {
            $browser->makeInstallation();
        });

        $data = [];
        $data['option_value'] = 'n';
        $data['option_key'] = 'login_captcha_enabled';
        $data['option_group'] = 'users';
        save_option($data);

        $browser->visit(route('admin.login'));
        $browser->pause(1500);

        if (count($browser->driver->findElements(WebDriverBy::xpath('//*[@id="password"]'))) > 0) {

            $browser->waitForText('Username', 30);
            $browser->waitForText('Password', 30);
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


        $browser->within(new EnvCheck, function ($browser) {
            $browser->checkEnvName($browser);
        });
    }


}
