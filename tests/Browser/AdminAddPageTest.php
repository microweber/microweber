<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use MicroweberPackages\Page\Models\Page;
use MicroweberPackages\Post\Models\Post;
use Tests\Browser\Components\AdminContentCategorySelect;
use Tests\Browser\Components\AdminContentCustomFieldAdd;
use Tests\Browser\Components\AdminContentImageAdd;
use Tests\Browser\Components\AdminContentTagAdd;
use Tests\Browser\Components\AdminLogin;
use Tests\DuskTestCase;

class AdminAddPageTest extends DuskTestCase
{
    public function testAddPage()
    {
        $this->browse(function (Browser $browser) {

            $browser->within(new AdminLogin, function ($browser) {
                $browser->fillForm();
            });

            $postTitle = 'This is the post title'.time();

            $browser->visit(route('admin.post.create'));

            $browser->pause(3000);
            $browser->value('#slug-field-holder input', $postTitle);


            $browser->pause(1000);

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

            $findPost = Page::where('title', $postTitle)->first();

            $this->assertEquals($findPost->content_type, 'page');
            $this->assertEquals($findPost->subtype, 'page');


            $findedCustomFields = [];
            $customFields = content_custom_fields($findPost->id);
            foreach ($customFields as $customField) {
                $findedCustomFields[] = $customField['name'];
            }
            $this->assertTrue(in_array('Dropdown',$findedCustomFields));
            $this->assertTrue(in_array('Text Field',$findedCustomFields));

            $getPictures = get_pictures($findPost->id);
            $this->assertEquals(3, count($getPictures));

            $browser->waitForLocation(route('admin.page.edit', $findPost->id));

        });

    }
}
