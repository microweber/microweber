<?php

namespace Tests\Browser\Admin;

use Laravel\Dusk\Browser;
use Modules\Page\Models\Page;
use Tests\Browser\Components\AdminContentCustomFieldAdd;
use Tests\Browser\Components\AdminContentImageAdd;
use Tests\Browser\Components\AdminLogin;
use Tests\Browser\Components\ChekForJavascriptErrors;
use Tests\DuskTestCase;

class AdminAddPageTest extends DuskTestCase
{
    public function testAddPage()
    {
        $this->browse(function (Browser $browser) {

            $browser->within(new AdminLogin, function ($browser) {
                $browser->fillForm();
            });
//
//            CustomField::truncate();
//            CustomFieldValue::truncate();
//            Media::truncate();


            $pageTitle = 'This is the page title' . time();

            $browser->visit(route('admin.page.create'));
            $browser->waitFor('.create-page-clean',30);
            $browser->pause(3000);
            $browser->click('.create-page-clean');
            $browser->waitUntilMissing('.mw-create-page-templates-select-window',60);


            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });

            $browser->pause(3000);
            $browser->value('#slug-field-holder input', $pageTitle);


            $browser->pause(1000);

//            // add images to gallery
            $browser->within(new AdminContentImageAdd, function ($browser) {
                $browser->addImage(userfiles_path() . '/templates/default/img/patterns/img1.jpg');
                $browser->addImage(userfiles_path() . '/templates/default/img/patterns/img2.jpg');
                $browser->addImage(userfiles_path() . '/templates/default/img/patterns/img3.jpg');
            });


            $browser->within(new AdminContentCustomFieldAdd, function ($browser) {
                $browser->addCustomField('dropdown', 'Dropdown');
                $browser->addCustomField('text', 'Text Field');
              //  $browser->addCustomField('number', 'Number');
            });


            $browser->pause(1000);
            $browser->click('#js-admin-save-content-main-btn');
            $browser->pause(10000);

            $findPage = Page::where('title', $pageTitle)->first();

            $this->assertEquals('page', $findPage->content_type);
            $this->assertEquals('static', $findPage->subtype);


            $findedCustomFields = [];
            $customFields = content_custom_fields($findPage->id);
            foreach ($customFields as $customField) {
                $findedCustomFields[] = $customField['name'];
            }
            $this->assertTrue(in_array('Dropdown', $findedCustomFields));
            $this->assertTrue(in_array('Text Field', $findedCustomFields));

            $getPictures = get_pictures($findPage->id);
            $this->assertEquals(3, count($getPictures));

            $browser->waitForLocation(route('admin.page.edit', $findPage->id));

        });

    }
}
