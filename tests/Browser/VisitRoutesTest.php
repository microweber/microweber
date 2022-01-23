<?php

namespace Tests\Browser;

use Illuminate\Contracts\Console\Kernel;
use Laravel\Dusk\Browser;
use MicroweberPackages\Page\Models\Page;
use Tests\Browser\Components\AdminLogin;
use Tests\DuskTestCase;
use Illuminate\Support\Facades\Route;

class VisitRoutesTest extends DuskTestCase
{
    public $siteUrl = 'http://127.0.0.1:8000/';





    /**
     * Check if the Browser environment matches with the
     * environment loaded by `php artisan dusk`
     * @return $this
     * @see https://medium.com/@deleugpn/testing-if-dusk-environment-matches-browser-environment-5edfe9d75ff6
     */
    public function testBrowserEnvironment() {

 
//        Route::getRoutes()->refreshNameLookups();
//        Route::getRoutes()->refreshActionLookups();


        $this->browse(function (Browser $browser) {

            $browser->within(new AdminLogin, function ($browser) {
                $browser->fillForm();
            });



            $environment = app()->environment();
            $browserEnvironment = $browser->visit(route('l5-swagger.dusk.env'))
                ->element('')->getText();

            $this->assertEquals($environment, $browserEnvironment,
                "Browser environment [{$browserEnvironment}]
            diverge from the given environment [{$environment}]");

        });


        return $this;
    }

   /* public function testContentLinksAndRoutesUrls()
    {

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
