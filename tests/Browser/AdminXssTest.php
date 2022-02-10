<?php

namespace Tests\Browser;


use Illuminate\Support\Facades\Route;
use Laravel\Dusk\Browser;
use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Content\Content;
use MicroweberPackages\Customer\Models\Customer;
use MicroweberPackages\Order\Models\Order;
use MicroweberPackages\Page\Models\Page;
use MicroweberPackages\Post\Models\Post;
use MicroweberPackages\Product\Models\Product;
use Tests\Browser\Components\AdminLogin;
use Tests\Browser\Components\ChekForJavascriptErrors;
use Tests\DuskTestCase;

class AdminXssTest extends DuskTestCase
{
    public function testPages()
    {
        \MicroweberPackages\Multilanguage\MultilanguageHelpers::setMultilanguageEnabled(false);

        $this->browse(function (Browser $browser) {

            $browser->within(new AdminLogin(), function ($browser) {
                $browser->fillForm();
            });

            $routeCollection = Route::getRoutes();
            foreach ($routeCollection as $value) {

                if($value->methods()[0] !== 'GET') {
                    continue;
                }

                if (strpos($value->uri(), 'admin') !== false) {

                    $visitPage = false;

                    if (strpos($value->uri(),'{page}') !== false) {
                        $findRoute = Page::first();
                        $visitPage = route($value->getName(), $findRoute->id);
                    }

                    if (strpos($value->uri(),'{post}') !== false) {
                        $findRoute = Post::first();
                        $visitPage = route($value->getName(), $findRoute->id);
                    }

                    if (strpos($value->uri(),'{product}') !== false) {
                        $findRoute = Product::first();
                        $visitPage = route($value->getName(), $findRoute->id);
                    }

                    if (strpos($value->uri(),'{customer}') !== false) {
                        $findRoute = Customer::first();
                        $visitPage = route($value->getName(), $findRoute->id);
                    }

                    if (strpos($value->uri(),'{category}') !== false) {
                        $findRoute = Category::first();
                        $visitPage = route($value->getName(), $findRoute->id);
                    }

                    if (strpos($value->uri(),'{content}') !== false) {
                        $findRoute = Content::first();
                        $visitPage = route($value->getName(), $findRoute->id);
                    }

                    if (strpos($value->uri(),'{order}') !== false) {
                        $findRoute = Order::first();
                        $visitPage = route($value->getName(), $findRoute->id);
                    }

                    if (!$visitPage) {
                        $visitPage = route($value->getName());
                    }

                    $browser->visit($visitPage);

                    $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                        $browser->validate();
                    });

                    $browser->pause(1000);
                }
            }


        });

    }
}
