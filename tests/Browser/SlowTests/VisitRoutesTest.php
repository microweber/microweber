<?php

namespace Tests\Browser\SlowTests;

use PHPUnit\Framework\Attributes\Test;

use Laravel\Dusk\Browser;
use Tests\Browser\Components\AdminLogin;
use Tests\Browser\Components\EnvCheck;
use Tests\DuskTestCase;

class VisitRoutesTest extends DuskTestCase
{
    public $siteUrl = 'http://127.0.0.1:8000/';


    /**
     * Check if the Browser environment matches with the
     * environment loaded by `php artisan dusk`
     * @return $this
     * @see https://medium.com/@deleugpn/testing-if-dusk-environment-matches-browser-environment-5edfe9d75ff6
     */
    #[Test]
    public function it_browser_environment(): void {


//        Route::getRoutes()->refreshNameLookups();
//        Route::getRoutes()->refreshActionLookups();


        $this->browse(function (Browser $browser) {

            $browser->within(new AdminLogin, function ($browser) {
                $browser->fillForm();
            });

             $browser->within(new EnvCheck, function ($browser) {
                $browser->checkEnvName($browser);
            });


        });


        return $this;
    }

   /* #[Test] public function it_content_links_and_routes_urls(): void {

        $newBlogPage = new Page();
        $newBlogPage->url = 'testme-testContentLinksAndRoutesUrls-'.uniqid();
        $newBlogPage->title = uniqid();
        $newBlogPage->content_type = 'page';
        $newBlogPage->subtype = 'dynamic';
        $newBlogPage->save();

        // TODO
      $this->assertEquals($this->siteUrl,site_url());
      $this->assertEquals($this->siteUrl.$newBlogPage->url,content_link($newBlogPage->id));


    }*/
}
