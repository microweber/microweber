<?php

namespace Tests\Browser\Multilanguage;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use MicroweberPackages\Multilanguage\MultilanguageApi;
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

class AdminMultilanguageAddPostTest extends DuskTestCaseMultilanguage
{
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

            $browser->visit(route('admin.post.create'));

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

            $enDescription = 'English description'.time();
            $bgDescription = 'Bulgarian description'.time();
            $arDescription = 'Arabic description'.time();
            $browser->within(new AdminContentMultilanguage, function ($browser) use ($bgDescription, $enDescription, $arDescription) {
                $browser->fillContent($bgDescription, 'bg_BG');
                $browser->fillContent($enDescription, 'en_US');
                $browser->fillContent($arDescription, 'ar_SA');
            });

            $browser->pause(1000);

            $category4 = 'Shop';
            $category4_1 = 'Clothes';
            $category4_2 = 'T-shirts';
            $category4_3 = 'Decor';

            $browser->within(new AdminContentCategorySelect, function ($browser) use($category4,$category4_1,$category4_2,$category4_3) {
                $browser->selectCategory($category4);
                $browser->selectSubCategory($category4,$category4_1);
                $browser->selectSubCategory($category4,$category4_2);
                $browser->selectSubCategory($category4,$category4_3);
            });

            $tag1 = 'Tagdusk-'.time().rand(100,200);
            $tag2 = 'Tagdusk-'.time().rand(200,300);
            $tag3 = 'Tagdusk-'.time().rand(300,400);
            $tag4 = 'Tagdusk-'.time().rand(400,500);

            $browser->within(new AdminContentTagAdd, function ($browser) use($tag1, $tag2, $tag3, $tag4) {
                $browser->addTag($tag1);
                $browser->addTag($tag2);
                $browser->addTag($tag3);
                $browser->addTag($tag4);
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
            $browser->pause(5000);
            $browser->waitForText('Editing post');

            $findPost = Post::where('title', $enTitle)->first();
            $browser->waitForLocation(route('admin.post.edit', $findPost->id));

            $this->assertEquals($findPost->content_type, 'post');
            $this->assertEquals($findPost->subtype, 'post');

            $tags = content_tags($findPost->id);
            $this->assertTrue(in_array($tag1,$tags));
            $this->assertTrue(in_array($tag2,$tags));
            $this->assertTrue(in_array($tag3,$tags));
            $this->assertTrue(in_array($tag4,$tags));

            $findedCategories = [];
            $categories = content_categories($findPost->id);
            foreach ($categories as $category) {
                $findedCategories[] = $category['title'];
            }

            $this->assertTrue(in_array('Decor',$findedCategories));
            $this->assertTrue(in_array('Clothes',$findedCategories));
            $this->assertTrue(in_array('T-shirts',$findedCategories));

            $findedCustomFields = [];
            $customFields = content_custom_fields($findPost->id);
            foreach ($customFields as $customField) {
                $findedCustomFields[] = $customField['name'];
            }
            $this->assertTrue(in_array('Dropdown',$findedCustomFields));
            $this->assertTrue(in_array('Text Field',$findedCustomFields));

            $description = content_description($findPost->id);
            $this->assertEquals($description, $enDescription);

            $getPictures = get_pictures($findPost->id);
            $this->assertEquals(3, count($getPictures));


        });

    }
}
