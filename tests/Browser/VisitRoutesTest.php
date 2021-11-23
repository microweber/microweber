<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\Browser\Components\AdminLogin;
use Tests\DuskTestCase;

class VisitRoutesTest extends DuskTestCase
{
    public $siteUrl = 'http://127.0.0.1:8000/';

    public function testAddPost()
    {
        $siteUrl = $this->siteUrl;

        $this->browse(function (Browser $browser) use($siteUrl) {

            $browser->within(new AdminLogin, function ($browser) {
                $browser->fillForm();
            });

            $browser->visit($siteUrl . '/?editmode=y');
            $browser->pause(4000);

            $browser->script("$('.mw-lsmodules-tab').trigger('mousedown').trigger('mouseup').click()");
            $browser->pause(500);
            $browser->script("mwSidebarSearchItems('Contact form', 'modules')");


            $browser->pause(14000);

        });
    }
}
