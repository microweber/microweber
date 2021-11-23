<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use MicroweberPackages\Post\Models\Post;
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

            /*
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
            */ 

            $postTitle = 'This is the post title1637657073';
            $postDescription = 'This is the post description1637657073';

            $findPost = Post::where('title', $postTitle)->first();

            $this->assertEquals($findPost->content, $postDescription);
            $this->assertEquals($findPost->content_type, 'post');
            $this->assertEquals($findPost->subtype, 'post');

            $tags = content_tags($findPost->id);
            $this->assertTrue(in_array('Iwatch',$tags));
            $this->assertTrue(in_array('Apple',$tags));
            $this->assertTrue(in_array('Jbl',$tags));

            $findedCategories = [];
            $categories = content_categories($findPost->id);
            foreach ($categories as $category) {
                $findedCategories[] = $category['title'];
            }
            $this->assertTrue(in_array('Accessoaries',$findedCategories));
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
