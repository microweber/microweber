<?php

namespace Tests\Browser\Multilanguage;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use MicroweberPackages\Multilanguage\MultilanguageApi;
use MicroweberPackages\Page\Models\Page;
use MicroweberPackages\Post\Models\Post;
use Tests\Browser\Components\AdminContentCategorySelect;
use Tests\Browser\Components\AdminContentCustomFieldAdd;
use Tests\Browser\Components\AdminContentImageAdd;
use Tests\Browser\Components\AdminContentMultilanguage;
use Tests\Browser\Components\AdminContentTagAdd;
use Tests\Browser\Components\AdminLogin;
use Tests\Browser\Components\ChekForJavascriptErrors;
use Tests\DuskTestCase;
use Tests\DuskTestCaseMultilanguage;
/**
 * @runTestsInSeparateProcesses
 */
class AdminMultilanguageAddPageTest extends DuskTestCaseMultilanguage
{
    /**
     * @runInSeparateProcess
     */
    public function testAddPost()
    {
        $this->browse(function (Browser $browser) {

          $browser->within(new AdminLogin, function ($browser) {
                $browser->fillForm();
            });

            $browser->within(new AdminContentMultilanguage, function ($browser) {
                $browser->addLanguage('bg_BG');
                $browser->addLanguage('en_US');
                $browser->addLanguage('ar_SA');
            });

            $browser->visit(route('admin.page.create'));

            $browser->pause(3000);
            $browser->click('.create-page-clean');
            $browser->waitUntilMissing('.mw-create-page-templates-select-window',60);

            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });

            $enTitle = 'English title'.time();
            $bgTitle = 'Bulgarian title'.time();
            $arTitle = 'Arabic title'.time();

            $browser->within(new AdminContentMultilanguage, function ($browser) use ($bgTitle, $enTitle, $arTitle) {
                $browser->fillTitle($bgTitle, 'bg_BG');
                $browser->fillTitle($enTitle, 'en_US');
                $browser->fillTitle($arTitle, 'ar_SA');
            });

            // add images to gallery
            $browser->within(new AdminContentImageAdd, function ($browser) {
                $browser->addImage(userfiles_path() . '/templates/default/img/patterns/img1.jpg');
                $browser->addImage(userfiles_path() . '/templates/default/img/patterns/img2.jpg');
                $browser->addImage(userfiles_path() . '/templates/default/img/patterns/img3.jpg');
            });


            $browser->within(new AdminContentCustomFieldAdd, function ($browser) {
                $browser->addCustomField('dropdown','Dropdown');
                $browser->addCustomField('text','Text Field');
            });

            $browser->pause(1000);
            $browser->click('#js-admin-save-content-main-btn');
            $browser->pause(3000);


            $findPage = Page::where('title', $enTitle)->first();
            $this->assertNotEmpty($findPage);
        $browser->visit(route('admin.page.edit', $findPage->id));
            $browser->waitForLocation(route('admin.page.edit', $findPage->id));
            $browser->pause(1000);
            $browser->waitForText($enTitle);


            $this->assertEquals($findPage->content_type, 'page');
            $this->assertEquals($findPage->subtype, 'static');

            $findedCustomFields = [];
            $customFields = content_custom_fields($findPage->id);
            foreach ($customFields as $customField) {
                $findedCustomFields[] = $customField['name'];
            }
            $this->assertTrue(in_array('Dropdown',$findedCustomFields));
            $this->assertTrue(in_array('Text Field',$findedCustomFields));

            $getPictures = get_pictures($findPage->id);
            $this->assertEquals(3, count($getPictures));

        });

    }
}
