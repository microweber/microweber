<?php

namespace Tests\Browser\FlakyTests;

use Facebook\WebDriver\WebDriverBy;
use Laravel\Dusk\Browser;
use MicroweberPackages\Multilanguage\Models\MultilanguageTranslations;
use Modules\Content\Models\Content;
use Tests\Browser\Components\AdminContentMultilanguage;
use Tests\Browser\Components\AdminLogin;
use Tests\Browser\Components\ChekForJavascriptErrors;
use Tests\Browser\Components\FrontendSwitchLanguage;
use Tests\Browser\Components\LiveEditSaveButton;
use Tests\Browser\Components\LiveEditSwitchLanguage;
use Tests\Browser\Components\LiveEditWaitUntilLoaded;
use Tests\DuskTestCaseMultilanguage;


class LiveEditMultilanguageTest extends DuskTestCaseMultilanguage
{
    public $template_name = 'big';

    public $tempContentId = 0;

    public function testLiveEditNewPageSaveMiltilanguage()
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
            $currentLocale = mw()->lang_helper->current_lang();

            run_translate_manager();
            // reload page
            $browser->script('window.location.reload();');

            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });

            $params = array(
                'title' => 'My new page ML ' . time(),
                'content_type' => 'page',
                'content' => '<h1 id="h1-test-element">My new page ML</h1>',
                'subtype' => 'static',
                'is_active' => 1,);


            $saved_id = save_content($params);

            $siteUrl = content_link($saved_id);


            $browser->visit($siteUrl . '?editmode=y');
            $browser->pause(4000);


            $browser->waitFor('#mw-live-edit-toolbar-back-to-admin-link',30  );
            $href = $browser->attribute('#mw-live-edit-toolbar-back-to-admin-link', 'href');
            $adminUrl = admin_url();
            $this->assertStringContainsString($adminUrl, $href);


            $browser->waitFor('#live-editor-frame', 30)
                ->withinFrame('#live-editor-frame', function ($browser) {
                    $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                        $browser->validate();
                    });
                    $browser->pause(1000);
                });







            $iframeElement = $browser->driver->findElement(WebDriverBy::id('live-editor-frame'));
            $browser->switchFrame($iframeElement);

            $browser->pause(1000);
            $browser->click('#h1-test-element'  );
            $browser->doubleClick('#h1-test-element'  );
            $browser->pause(1000);
            $browser->typeSlowly('#h1-test-element' , 'This is my text on english language');
            $browser->click('#mw-back-to-element-settings-editor-button'  );

            $browser->switchFrameDefault();

            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });
            $browser->within(new LiveEditWaitUntilLoaded(), function (Browser $browser) {
                $browser->waitUntilLoaded();
            });
            $browser->within(new LiveEditSaveButton(), function ($browser) {
                $browser->clickSaveButton($browser);
            });


            // Switch to Bulgarian
            $browser->pause(3000);

//            $iframeElement = $browser->driver->findElement(WebDriverBy::id('live-editor-frame'));
//            $browser->switchFrame($iframeElement);
//
//

//            $browser->within(new FrontendSwitchLanguage(), function ($browser) {
//                $browser->switchLanguage('bg_BG');
//            });

            $browser->within(new LiveEditSwitchLanguage(), function ($browser) {
                $browser->switchLanguage('bg_BG');
            });




            $browser->waitFor('#h1-test-element',30  );

            $browser->pause(3000);


            $browser->pause(1000);
            $browser->click('#h1-test-element'  );
            $browser->doubleClick('#h1-test-element'  );
            $browser->pause(1000);


            $browser->typeSlowly('#h1-test-element', 'Текст написан на български, това е българска страница');
            $browser->pause(200);
            $browser->click('#mw-back-to-element-settings-editor-button'  );
            $browser->pause(100);

            $browser->within(new LiveEditSaveButton(), function ($browser) {
                $browser->clickSaveButton($browser);
            });
            $browser->pause(100);

            $browser->switchFrameDefault();


            $iframeElement = $browser->driver->findElement(WebDriverBy::id('live-editor-frame'));
            $browser->switchFrame($iframeElement);
            // Switch to English

//            $browser->within(new FrontendSwitchLanguage(), function ($browser) {
//                $browser->switchLanguage('en_US');
//            });
            $browser->within(new LiveEditSwitchLanguage(), function ($browser) {
                $browser->switchLanguage('en_US');
            });



            $browser->pause(1000);
            $browser->assertSee('This is my text on english language');

            // Switch back to Bulgarian
            $browser->pause(1000);
//            $browser->within(new FrontendSwitchLanguage(), function ($browser) {
//                $browser->switchLanguage('en_US');
//            });
//            $browser->within(new LiveEditSwitchLanguage(), function ($browser) {
//                $browser->switchLanguage('bg_BG');
//            });
            $browser->within(new FrontendSwitchLanguage(), function ($browser) {
                $browser->switchLanguage('bg_BG');
            });


            $browser->pause(3000);
            $browser->assertSee('Текст написан на български, това е българска страница');



            // check the database
            $translationMustBeInOriginal = MultilanguageTranslations::where('rel_type', morph_name(\Modules\Content\Models\Content::class))
                ->where('rel_id', $saved_id)
                ->where('field_name', 'content')
                ->where('locale', 'en_US')
                ->first();

            $this->assertEmpty($translationMustBeInOriginal);
            $content = Content::find($saved_id);

            $this->assertEquals('<h1 id="h1-test-element" class="element">This is my text on english language</h1>', $content->content);

            $translationMustBeSaved = MultilanguageTranslations::where('rel_type', morph_name(\Modules\Content\Models\Content::class))
                ->where('rel_id', $saved_id)
                ->where('field_name', 'content')
                ->where('locale', 'bg_BG')
                ->first();

            $this->assertNotEmpty($translationMustBeSaved);

            $this->assertEquals($translationMustBeSaved->field_value, '<h1 id="h1-test-element" class="element">Текст написан на български, това е българска страница</h1>');

        });

    }

}
