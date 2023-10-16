<?php

namespace Tests\Browser\SlowTests;


use Illuminate\Support\Facades\Route;
use Laravel\Dusk\Browser;
use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Content\Models\Content;
use MicroweberPackages\Customer\Models\Customer;
use MicroweberPackages\Order\Models\Order;
use MicroweberPackages\Page\Models\Page;
use MicroweberPackages\Post\Models\Post;
use MicroweberPackages\Product\Models\Product;
use Tests\Browser\Components\AdminLogin;
use Tests\Browser\Components\ChekForJavascriptErrors;
use Tests\Browser\Components\InputFieldsXssType;
use Tests\DuskTestCase;


class AdminXssTest extends DuskTestCase
{
    public function testPagesXss()
    {
        \MicroweberPackages\Multilanguage\MultilanguageHelpers::setMultilanguageEnabled(false);

        $this->browse(function (Browser $browser) {

            $browser->within(new AdminLogin(), function ($browser) {
                $browser->fillForm();
            });


            $routesForTest = [];
            $routesForTest[] = route('admin.page.create');
            $routesForTest[] = route('admin.post.create');
            $routesForTest[] = route('admin.product.create');

            foreach ($routesForTest as $route) {

                // Test xss create product page
                $browser->visit($route);
                //  $browser->script('$( "[data-toggle=\'collapse\']").each(function() { if ($(this).hasClass(\'active\') == false) {$(this).click()} });');
                $browser->within(new InputFieldsXssType(), function ($browser) {
                    $browser->fill();
                });
                $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                    $browser->validate();
                });
                $browser->pause(4000);
            }

            // Check routers for errors
            $routeCollection = Route::getRoutes();
            foreach ($routeCollection as $value) {

                if ($value->methods()[0] !== 'GET') {
                    continue;
                }

                if ($value->getName() == 'api.') {
                    continue;
                }
                if (str_contains($value->getName(), 'filament.')) {
                    continue;
                }
                if (str_contains($value->getName(), 'admin.live-edit.')) {
                    continue;
                }
                if (str_contains($value->getName(), 'debugbar.')) {
                    continue;
                }

                if (str_contains($value->uri(), 'live-edit')) {
                    continue;
                }

                if (strpos($value->uri(), 'admin') !== false) {

                    $visitPage = false;

                    if (strpos($value->uri(), '{page}') !== false) {
                        $findRoute = Page::first();
                        if ($findRoute == null) {
                            continue;
                        }
                        $visitPage = route($value->getName(), $findRoute->id);
                    }

                    if (strpos($value->uri(), '{post}') !== false) {
                        $findRoute = Post::first();
                        if ($findRoute == null) {
                            continue;
                        }
                        $visitPage = route($value->getName(), $findRoute->id);
                    }

                    if (strpos($value->uri(), '{product}') !== false) {
                        $findRoute = Product::first();
                        if ($findRoute == null) {
                            continue;
                        }
                        $visitPage = route($value->getName(), $findRoute->id);
                    }

                    if (strpos($value->uri(), '{customer}') !== false) {
                        $findRoute = Customer::first();
                        if ($findRoute == null) {
                            continue;
                        }
                        $visitPage = route($value->getName(), $findRoute->id);
                    }

                    if (strpos($value->uri(), '{category}') !== false) {
                        $findRoute = Category::first();
                        if ($findRoute == null) {
                            continue;
                        }
                        $visitPage = route($value->getName(), $findRoute->id);
                    }

                    if (strpos($value->uri(), '{content}') !== false) {
                        $findRoute = Content::first();
                        if ($findRoute == null) {
                            continue;
                        }
                        $visitPage = route($value->getName(), $findRoute->id);
                    }

                    if (strpos($value->uri(), '{order}') !== false) {
                        $findRoute = Order::first();
                        if ($findRoute == null) {
                            continue;
                        }
                        $visitPage = route($value->getName(), $findRoute->id);
                    }

                    if (strpos($value->uri(), '{') !== false) {
                        continue;
                    }

                    if (!$visitPage) {
                        $visitPage = route($value->getName());
                    }

                    echo 'about to visit page url: ' . $visitPage . PHP_EOL;
                    $browser->visit($visitPage);

                    $browser->within(new InputFieldsXssType(), function ($browser) {
                        $browser->fill();
                    });

                    $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                        $browser->validate();
                    });

                    echo 'page url: ' . $browser->driver->getCurrentURL() . PHP_EOL;
                    $browser->assertDontSee('There is some error');

                    $browser->pause(700);
                }
            }


        });

    }
}
