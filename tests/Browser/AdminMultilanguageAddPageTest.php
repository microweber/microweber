<?php

namespace Tests\Browser;

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
use Tests\DuskTestCase;

class AdminMultilanguageAddPageTest extends DuskTestCase
{
    public function testAddPost()
    {
        $this->browse(function (Browser $browser) {

            $browser->within(new AdminLogin, function ($browser) {
                $browser->fillForm();
            });

            $browser->within(new AdminContentMultilanguage, function ($browser) {
                $browser->addLanguage('Bulgarian');
                $browser->addLanguage('English');
                $browser->addLanguage('Arabic');
            });

            $browser->visit(route('admin.page.create'));

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
            $browser->pause(10000);
            return;

            $findPage = Page::where('title', $enTitle)->first();

            dd($findPage); 

            $this->assertEquals($findPage->content_type, 'post');
            $this->assertEquals($findPage->subtype, 'post');

            $findedCustomFields = [];
            $customFields = content_custom_fields($findPage->id);
            foreach ($customFields as $customField) {
                $findedCustomFields[] = $customField['name'];
            }
            $this->assertTrue(in_array('Dropdown',$findedCustomFields));
            $this->assertTrue(in_array('Text Field',$findedCustomFields));

            $description = content_description($findPage->id);
            $this->assertEquals($description, $enDescription);

            $getPictures = get_pictures($findPage->id);
            $this->assertEquals(3, count($getPictures));

            $browser->waitForLocation(route('admin.page.edit', $findPage->id));

        });

    }
}
