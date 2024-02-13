<?php

namespace Tests\Browser\LiveEdit;

use Illuminate\Support\Facades\Auth;
use Laravel\Dusk\Browser;
use MicroweberPackages\Content\tests\TestHelpers;
use MicroweberPackages\Form\Models\FormData;
use MicroweberPackages\Form\Models\FormDataValue;
use MicroweberPackages\Page\Models\Page;
use MicroweberPackages\User\Models\User;
use Tests\Browser\Components\ChekForJavascriptErrors;
use Tests\DuskTestCase;

class PostsModuleTest extends DuskTestCase
{
    public $siteUrl = 'http://127.0.0.1:8000/';

    use TestHelpers;

    public function testPostsModuleIsDidplayingLatestsPosts()
    {
        $siteUrl = $this->siteUrl;


        $pageId = $this->_generatePage('my-page-for-posts-module-test', 'My page for posts module test');
        $posts = [];
        $posts[] = $this->_generatePost('my-first-post', 'My first post', $pageId);
        $posts[] = $this->_generatePost('my-second-post', 'My second post', $pageId);
        $posts[] = $this->_generatePost('my-third-post', 'My third post', $pageId);


        $moduleIdRand = 'testpostsmodule' . time() . uniqid();

        $title = 'My page for posts module test ' . time();
        $params = array(
            'id' => $pageId,
            'title' => $title,
            'content_type' => 'page',
            'subtype' => 'static',
            'content' => '<div class="container">
<h1>Posts for test</h1>
<module type="posts" id="' . $moduleIdRand . '" template="default"  /></div>',

            'is_active' => 1,);


        $saved_id = save_content($params);

        $siteUrl = content_link($saved_id);

        $this->browse(function (Browser $browser) use ($siteUrl, $posts, $moduleIdRand) {
            $browser->visit($siteUrl)
                ->assertSee('Posts for test')
                ->assertSee('My first post')
                ->assertSee('My second post')
                ->assertSee('My third post')
                ->assertVisible('#' . $moduleIdRand);

            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });

        });


        $date_formats = app()->format->get_supported_date_formats();

        foreach ($date_formats as $date_format) {
            $setDateFormat = save_option('date_format', $date_format, 'website');
            $getDateFormat = get_option('date_format', 'website');
            $this->assertEquals($date_format, $getDateFormat);


            $this->browse(function (Browser $browser) use ($siteUrl, $posts, $moduleIdRand, $title) {

                $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                    $browser->validate();
                });

                $browser->visit($siteUrl)
                    ->assertSee('My first post')
                    ->assertSee('My second post')
                    ->assertSee('My third post')
                    ->assertVisible('#' . $moduleIdRand);
            });
        }


    }
}
