<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use MicroweberPackages\Post\Models\Post;
use Tests\Browser\Components\AdminContentCategorySelect;
use Tests\Browser\Components\AdminContentTagAdd;
use Tests\DuskTestCase;

class AddPostTest extends DuskTestCase
{
    public $siteUrl = 'http://127.0.0.1:8000/';

    public function testAddPost()
    {
        $siteUrl = $this->siteUrl;

        $this->browse(function (Browser $browser) use($siteUrl) {

            $postTitle = 'This is the post title'.time();
            $postDescription = 'This is the post description'.time();

            $browser->visit($siteUrl . 'admin/login')->assertSee('Login');

            $browser->waitFor('@login-button');

            // Login to admin panel
            $browser->type('username', '1');
            $browser->type('password', '1');

            $browser->click('@login-button');

            // Wait for redirect after login
            $browser->waitForLocation('/admin/', 120);

            $browser->pause(100);

            $browser->visit($siteUrl.'admin/post/create?dusk=1');

            $browser->pause(3000);
            $browser->value('#slug-field-holder input', $postTitle);

            $browser->pause(3000);
            $browser->keys('.mw-editor-area', $postDescription);

            $browser->pause(1000);

            $category1 = 'Blog';
            $category2 = 'Services';
            $category3 = 'About us';

            $category4 = 'Shop';
            $category4_1 = 'Clothes';
            $category4_2 = 'T-shirts';
            $category4_3 = 'Decor';

            $browser->within(new AdminContentCategorySelect, function ($browser) use($category1,$category2,$category3,$category4,$category4_1,$category4_2,$category4_3) {
                $browser->selectCategory($category1);
                $browser->selectCategory($category2);
                $browser->selectCategory($category3);
                $browser->selectCategory($category4);
                $browser->selectSubCategory($category4,$category4_1);
                $browser->selectSubCategory($category4,$category4_2);
                $browser->selectSubCategory($category4,$category4_3);
            });

            $tag1 = 'tag-dusk-'.uniqid();
            $tag2 = 'tag-dusk-'.uniqid();
            $tag3 = 'tag-dusk-'.uniqid();
            $tag4 = 'tag-dusk-'.uniqid();

            $browser->within(new AdminContentTagAdd, function ($browser) use($tag1, $tag2, $tag3, $tag4) {
                $browser->addTag($tag1);
                $browser->addTag($tag2);
                $browser->addTag($tag3);
                $browser->addTag($tag4);
            });

            $browser->scrollTo('@show-custom-fields');
            $browser->pause(1000);
            $browser->click('@show-custom-fields');

            // add custom field price
            $browser->waitForText('Add new field');
            $browser->click('@add-custom-field');
            $browser->pause(3000);
            $browser->waitForText('Price');
            $browser->click('@add-custom-field-price');

            // add custom field dropdown
            $browser->waitForText('Add new field');
            $browser->click('@add-custom-field');
            $browser->pause(3000);
            $browser->waitForText('Dropdown');
            $browser->click('@add-custom-field-dropdown');

            // add custom field text
            $browser->waitForText('Add new field');
            $browser->click('@add-custom-field');
            $browser->pause(3000);
            $browser->waitForText('Text');
            $browser->click('@add-custom-field-text');

            // add custom field email
            $browser->waitForText('Add new field');
            $browser->click('@add-custom-field');
            $browser->pause(3000);
            $browser->waitForText('E-mail');
            $browser->click('@add-custom-field-email');

            $browser->pause(5000);

            // add images to gallery
            $browser->scrollTo('.mw-uploader-input');
            $browser->attach('input.mw-uploader-input', userfiles_path() . '/templates/default/img/patterns/img1.jpg');
            $browser->pause(4000);
            $browser->attach('input.mw-uploader-input', userfiles_path() . '/templates/default/img/patterns/img2.jpg');
            $browser->pause(4000);
            $browser->attach('input.mw-uploader-input', userfiles_path() . '/templates/default/img/patterns/img3.jpg');

            $browser->pause(1000);
            $browser->click('#js-admin-save-content-main-btn');
            $browser->pause(10000);

            $findPost = Post::where('title', $postTitle)->first();

            $this->assertEquals($findPost->content, $postDescription);
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
            $this->assertTrue(in_array('Shop',$findedCategories));
            $this->assertTrue(in_array('Decor',$findedCategories));
            $this->assertTrue(in_array('Services',$findedCategories));
            $this->assertTrue(in_array('Clothes',$findedCategories));
            $this->assertTrue(in_array('T-shirts',$findedCategories));

            $findedCustomFields = [];
            $customFields = content_custom_fields($findPost->id);
            foreach ($customFields as $customField) {
                $findedCustomFields[] = $customField['name'];
            }
            $this->assertTrue(in_array('Price',$findedCustomFields));
            $this->assertTrue(in_array('Dropdown',$findedCustomFields));
            $this->assertTrue(in_array('Text Field',$findedCustomFields));
            $this->assertTrue(in_array('E-mail',$findedCustomFields));

            $description = content_description($findPost->id);
            $this->assertEquals($description, $postDescription);

            $getPictures = get_pictures($findPost->id);
            $this->assertEquals(3, count($getPictures));

            $browser->waitForLocation(route('admin.post.edit', $findPost->id));

        });

    }
}
