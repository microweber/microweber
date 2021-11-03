<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
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

            $browser->visit($siteUrl.'admin/view:content#action=new:post?dusk=1');

            $browser->pause(3000);
            $browser->value('#slug-field-holder input', $postTitle);

            $browser->pause(3000);
            $browser->script('$(".mw-editor-area").html("'.$postDescription.'")');
            $browser->pause(1000);

            $browser->scrollTo('.mw-uploader-input');
            $browser->attach('input.mw-uploader-input', userfiles_path() . '/templates/default/img/patterns/img1.jpg');
            $browser->pause(4000);
            $browser->attach('input.mw-uploader-input', userfiles_path() . '/templates/default/img/patterns/img2.jpg');
            $browser->pause(4000);
            $browser->attach('input.mw-uploader-input', userfiles_path() . '/templates/default/img/patterns/img3.jpg');

            $browser->scrollTo('@show-custom-fields');
            $browser->pause(1000);
            $browser->click('@show-custom-fields');

            $browser->pause(1000);
            $browser->click('@add-custom-field');

            $fields = mw()->ui->custom_fields();
            foreach ($fields as $field => $value) {
                dump($field);
                sleep(3);
                $browser->pause(2000);
                $browser->click('@add-custom-field-' . $field);
            }

            $browser->pause(111500);

            $browser->pause(1000);
            $browser->click('#js-admin-save-content-main-btn');

            $browser->pause(1500);
            $browser->assertSee('Content saved');

            $browser->pause(4000);
            $browser->assertValue('#slug-field-holder input', $postTitle);

            $browser->pause(11500);
        });

    }
}
