<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Components\AdminLogin;
use Tests\DuskTestCase;

class AdminSettingsPagesErrorsCheckTest extends DuskTestCase
{
    public function testPages()
    {
        $this->browse(function (Browser $browser) {

            $browser->within(new AdminLogin, function ($browser) {
                $browser->fillForm();
            });

            $browser->waitForText('Settings');
            $browser->clickLink('Settings');

            $browser->pause(3000);

            $items = $browser->elements('.js-website-settings-link');
            foreach ($items as $item) {
                dump($item);
            }


        });
    }
}
