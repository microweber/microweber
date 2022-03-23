<?php

namespace Tests\Browser\Multilanguage;

use Laravel\Dusk\Browser;
use Tests\Browser\Components\AdminContentMultilanguage;
use Tests\Browser\Components\AdminLogin;
use Tests\Browser\Components\ChekForJavascriptErrors;
use Tests\Browser\Components\FrontendSwitchLanguage;
use Tests\Browser\Components\LiveEditModuleAdd;
use Tests\Browser\Components\LiveEditSwitchLanguage;
use Tests\DuskTestCase;

class LiveEditMultilanguageTest extends DuskTestCase
{

    public function testLiveEditNewPageSave()
    {
        $siteUrl = $this->siteUrl;

        $this->browse(function (Browser $browser) use ($siteUrl) {

            $browser->within(new AdminLogin, function ($browser) {
                $browser->fillForm();
            });

            $browser->within(new AdminContentMultilanguage(), function ($browser) {
                $browser->addLanguage('bg_BG');
                $browser->addLanguage('en_US');
            });

            $browser->visit($siteUrl . '?editmode=y');
            $browser->pause(4000);

            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });

            $pageUrl = 'rand-page-multilanguage-' . time();
            $browser->visit($siteUrl . $pageUrl);

            $browser->pause(5000);

            $randClassForDagAndDrop = 'rand-class-' . time();
            $browser->script("$('.edit[rel=\"content\"][field=\"content\"]').find('div').last().addClass('$randClassForDagAndDrop')");
            $browser->pause(1000);
            $browser->click('.' . $randClassForDagAndDrop);

            $browser->typeSlowly('.' . $randClassForDagAndDrop, 'This is my text on english language');

            $browser->click('#main-save-btn');
            $browser->pause(5000);

            // Switch to Bulgarian
            $browser->pause(1000);
            $browser->within(new LiveEditSwitchLanguage(), function ($browser) {
                $browser->switchLanguage('bg_BG');
            });

            $browser->pause(3000);

            $randClassForWrite = 'rand-class-' . time();
            $browser->script("$('.edit[rel=\"content\"][field=\"content\"]').find('div').last().addClass('$randClassForWrite')");
            $browser->pause(3000);


            $browser->click('.' . $randClassForWrite);

            $browser->pause(3000);
            $browser->typeSlowly('.' . $randClassForWrite, 'Текст написан на български, това е българска страница');
            $browser->click('#main-save-btn');
            $browser->pause(5000);

            // Switch to English
            $browser->pause(1000);
            $browser->within(new LiveEditSwitchLanguage(), function ($browser) {
                $browser->switchLanguage('en_US');
            });
            $browser->pause(1000);
            $browser->assertSee('This is my text on english language');

            // Switch back to Bulgarian
            $browser->pause(1000);
            $browser->within(new LiveEditSwitchLanguage(), function ($browser) {
                $browser->switchLanguage('bg_BG');
            });
            $browser->pause(1000);
            $browser->assertSee('Текст написан на български, това е българска страница');



        });

    }

}
