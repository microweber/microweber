<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Components\AdminLogin;
use Tests\Browser\Components\ChekForJavascriptErrors;
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

            $links = $browser->script('
                function getAllWebsiteSettingsLinks() {
                    var links = [];
                    $(".js-website-settings-link").each(function(e) {
                        links.push($(this).attr(\'href\'));
                    });
                   return links;
                }
                return getAllWebsiteSettingsLinks();
            ');

            foreach($links[0] as $link) {

                $browser->visit($link);

                $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                    $browser->validate();
                });
            }

        });
    }
}
