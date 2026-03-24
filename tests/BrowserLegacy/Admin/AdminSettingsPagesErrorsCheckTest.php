<?php

namespace Tests\Browser\Admin;

use PHPUnit\Framework\Attributes\Test;

use Laravel\Dusk\Browser;
use Tests\Browser\Components\AdminLogin;
use Tests\Browser\Components\ChekForJavascriptErrors;
use Tests\DuskTestCase;

class AdminSettingsPagesErrorsCheckTest extends DuskTestCase
{
    #[Test]
    public function it_pages(): void {
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
                $browser->pause(1000);
                $browser->visit($link);
                $browser->pause(3000);

                $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                    $browser->validate();
                });
            }

        });
    }

}
