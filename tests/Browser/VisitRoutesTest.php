<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use MicroweberPackages\Page\Models\Page;
use Tests\Browser\Components\AdminLogin;
use Tests\DuskTestCase;

class VisitRoutesTest extends DuskTestCase
{
    public $siteUrl = 'http://127.0.0.1:8000/';

    public function testAddPost()
    {
        $siteUrl = $this->siteUrl;

        $this->browse(function (Browser $browser) use($siteUrl) {

            $browser->within(new AdminLogin, function ($browser) {
                $browser->fillForm();
            });

            $browser->visit($siteUrl . '/?editmode=y');
            $browser->pause(4000);

            $browser->script("$('.mw-lsmodules-tab').trigger('mousedown').trigger('mouseup').click()");
            $browser->pause(500);
            $browser->script("mwSidebarSearchItems('Contact form', 'modules')");


            $browser->pause(14000);

        });
    }

    public function testContentLinksAndRoutesUrls()
    {

        $newBlogPage = new Page();
        $newBlogPage->url = 'testme-testContentLinksAndRoutesUrls-'.uniqid();
        $newBlogPage->title = uniqid();
        $newBlogPage->content_type = 'page';
        $newBlogPage->subtype = 'dynamic';
        $newBlogPage->save();

        // TODO
     //   $this->assertEquals($this->siteUrl,site_url());
       // $this->assertEquals($this->siteUrl.$newBlogPage->url,content_link($newBlogPage->id));


    }
}
