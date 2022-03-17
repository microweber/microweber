<?php

namespace Tests\Browser\SlowTests;


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
use Tests\Browser\Components\InputFieldsXssTest;
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

            // Test xss create post page
            $browser->visit(route('admin.post.create'));

           // $browser->script('$( "[data-toggle=\'collapse\']").each(function() { if ($(this).hasClass(\'active\') == false) {$(this).click()} });');
            $browser->within(new InputFieldsXssTest(), function ($browser) {
                $browser->fill();
            });
            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });
            $browser->pause(4000);

            // Test xss create product page
            $browser->visit(route('admin.product.create'));
          //  $browser->script('$( "[data-toggle=\'collapse\']").each(function() { if ($(this).hasClass(\'active\') == false) {$(this).click()} });');
            $browser->within(new InputFieldsXssTest(), function ($browser) {
                $browser->fill();
            });
            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });
            $browser->pause(4000);

            // Test xss create page
            $browser->visit(route('admin.page.create'));
         //   $browser->script('$( "[data-toggle=\'collapse\']").each(function() { if ($(this).hasClass(\'active\') == false) {$(this).click()} });');
            $browser->within(new InputFieldsXssTest(), function ($browser) {
                $browser->fill();
            });
            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });
            $browser->pause(4000);

            // Check routers for errors
            $routeCollection = Route::getRoutes();
            foreach ($routeCollection as $value) {

                if($value->methods()[0] !== 'GET') {
                    continue;
                }

                if ($value->getName() == 'api.') {
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

                    if (strpos($value->uri(),'{') !== false) {
                        continue;
                    }

                    if (!$visitPage) {
                        $visitPage = route($value->getName());
                    }

                    $browser->visit($visitPage);

                    $browser->within(new InputFieldsXssTest(), function ($browser) {
                        $browser->fill();
                    });

                    $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                        $browser->validate();
                    });

                    echo  'page url: ' . $browser->driver->getCurrentURL() . PHP_EOL;
                    $browser->assertDontSee('There is some error');

                    $browser->pause(700);
                }
            }


        });

    }
}
