<?php
namespace MicroweberPackages\Shop\tests;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use MicroweberPackages\Content\Content;
use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\Page\Models\Page;
use MicroweberPackages\Product\Models\Product;
use MicroweberPackages\Shop\Http\Controllers\ShopController;

class ShopFilterTest extends TestCase
{
    public function testGetProductsInShop()
    {
        $newShopPage = new Page();
        $newShopPage->title = uniqid();
        $newShopPage->is_shop = 1;
        $newShopPage->content_type = 'page';
        $newShopPage->subtype = 'dynamic';
        $newShopPage->is_active = 1;
        $newShopPage->save();

        $shopPage = Page::where('id', $newShopPage->id)->first();

        $moduleId = 'shop--mw--'. uniqid();

        save_option('content_from_id', $shopPage->id, $moduleId);

        $products = [];

        for($i = 0; $i < 5; $i++) {
            $newProduct = new Product();
            $newProduct->price = rand(11,999);
            $newProduct->title = uniqid();
            $newProduct->parent = $shopPage->id;
            $newProduct->is_active = 1;
            $newProduct->save();

            $products[] = $newProduct;
        }

        $params = [];
        $params['id'] = $moduleId;

        $request = new \Illuminate\Http\Request();
        $request->merge($params);

        $controller = App::make(ShopController::class);
        $controller->setModuleParams($params);
        $controller->setModuleConfig([
            'module'=> 'shop'
        ]);
        $controller->registerModule();

        $html = $controller->index($request);
        $htmlString = $html->__toString();

        foreach ($products as $product) {

            $findProductTitle = (strpos($htmlString, $product->title) !== false);
            $this->assertTrue($findProductTitle);

            $findProductPrice = (strpos($htmlString, $product->price) !== false);
            $this->assertTrue($findProductPrice);

        }

        $findJs = (strpos($htmlString, 'filter.js') !== false);
        $this->assertTrue($findJs);

        $findCss = (strpos($htmlString, 'filter.css') !== false);
        $this->assertTrue($findCss);

    }
}
