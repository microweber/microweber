<?php


namespace MicroweberPackages\Product\tests;


use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\Page\Models\Page;
use MicroweberPackages\Product\Models\Product;
use MicroweberPackages\User\Models\User;
use Illuminate\Support\Facades\Auth;

class ProductFilterTest extends TestCase
{

    public function testProductFilter()
    {


        $newShopPage = new Page();
        $newShopPage->title = 'my-new-shop-page-for-filter-test-' . uniqid();
        $newShopPage->is_shop = 1;
        $newShopPage->content_type = 'page';
        $newShopPage->url = 'my-new-shop-page-for-filter-test';
        $newShopPage->subtype = 'dynamic';
        $newShopPage->save();


        $newProduct = new Product();
        $newProduct->title = 'my-new-product-for-filter-test-' . uniqid();
        $newProduct->url = 'my-new-product-for-filter-test';
        $newProduct->content_type = 'product';
        $newProduct->subtype = 'product';
        $newProduct->parent = $newShopPage->id;
        $newProduct->setCustomField(
            [
                'type'=>'price',
                'name'=>'price',
                'value'=>'1',
            ]
        );
        $newProduct->save();


        $newProduct2 = new Product();
        $newProduct2->title = 'my-second-new-product-for-filter-test-' . uniqid();
        $newProduct2->url = 'my-second-product-for-filter-test';
        $newProduct2->content_type = 'product';
        $newProduct2->subtype = 'product';
        $newProduct2->parent = $newShopPage->id;
        $newProduct2->setCustomField(
            [
                'type'=>'price',
                'name'=>'price',
                'value'=>'1001',
            ]
        );
        $newProduct2->save();

        $model = \MicroweberPackages\Product\Models\Product::query();

        $model->filter([
            'priceBetween' => 1 . ',' . 1000
        ]);
        $results = $model->get();

        $this->assertEquals(1, count($results));
        $this->assertEquals($newProduct->id, $results[0]->id);



        $model = \MicroweberPackages\Product\Models\Product::query();

        $model->filter([
            'priceBetween' => 1000 . ',' . 1100
        ]);
        $results = $model->get();
        $this->assertEquals(1, count($results));
        $this->assertEquals($newProduct2->id, $results[0]->id);

        $model = \MicroweberPackages\Product\Models\Product::query();

        $model->filter([
            'priceBetween' => 2000 . ',' . 3000
        ]);
        $results = $model->get();
        $this->assertEquals(0, count($results));


    }


}
