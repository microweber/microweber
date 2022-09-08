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

        $clean = \MicroweberPackages\Product\Models\Product::truncate();

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
                'value'=>'1000',
            ]
        );
        $newProduct2->save();

        $model = \MicroweberPackages\Product\Models\Product::query();

        $model->filter([
            'priceBetween' => 1 . ',' . 999,
        ]);
        $results = $model->get();
         $this->assertEquals(1, count($results));
        $this->assertEquals($newProduct->id, $results[0]->id);


        $model = \MicroweberPackages\Product\Models\Product::query();

        $model->filter([
            'priceBetween' => 1000
        ]);
        $results = $model->get();
        $this->assertEquals(1, count($results));
        $this->assertEquals($newProduct2->id, $results[0]->id);



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





        $newProduct3 = new Product();
        $newProduct3->title = 'my-second-new-product-zero-for-filter-test-' . uniqid();
        $newProduct3->url = 'my-second-product-zero-for-filter-test';
        $newProduct3->content_type = 'product';
        $newProduct3->subtype = 'product';
        $newProduct3->parent = $newShopPage->id;
        $newProduct3->setCustomField(
            [
                'type'=>'price',
                'name'=>'price',
                'value'=>'0',
            ]
        );
        $newProduct3->save();



        $model = \MicroweberPackages\Product\Models\Product::query();

        $model->filter([
            'priceBetween' => 0 . ',' . 1
        ]);
        $results = $model->get();

        $this->assertEquals(2, count($results));
        $this->assertEquals($newProduct->id, $results[0]->id);
        $this->assertEquals($newProduct3->id, $results[1]->id);




        $model = \MicroweberPackages\Product\Models\Product::query();

        $model->filter([
            'price' => 1000
        ]);
        $results = $model->get();

        $this->assertEquals($newProduct2->id, $results[0]->id);



        $model = \MicroweberPackages\Product\Models\Product::query();

        $model->filter([
            'price' => 0
        ]);
        $results = $model->get();

        $this->assertEquals($newProduct3->id, $results[0]->id);



        $model = \MicroweberPackages\Product\Models\Product::query();

        $model->filter([
            'title' => 'zero'
        ]);
        $results = $model->get();

        $this->assertEquals($newProduct3->id, $results[0]->id);
    }

    public function testProductFilterBySalesCount()
    {
        $clean = \MicroweberPackages\Cart\Models\Cart::truncate();
        $clean = \MicroweberPackages\Order\Models\Order::truncate();



        // add product and order it

        $productPrice = rand(1, 9999);
        $title = 'test Sales filter prod ordered once' . $productPrice;
        $title2 = 'test Sales filter prod ordered many times' . $productPrice;

        $params = array(

            'content_type' => 'product',
            'subtype' => 'product',
            'custom_fields_advanced' => array(
                array('type' => 'price', 'name' => 'Price', 'value' => '9.99'),

            ),
            'is_active' => 1
        );

        $params['title'] = $title;

        $saved_id = save_content($params);

        $params['title'] = $title2;
        $saved_id2 = save_content($params);

        $get = get_content_by_id($saved_id);

        $add_to_cart = array(
            'content_id' => $saved_id,
        );
        $cart_add = update_cart($add_to_cart);


        $add_to_cart = array(
            'content_id' => $saved_id2,
        );
        $cart_add = update_cart($add_to_cart);




        $checkoutDetails = array();
        $checkoutDetails['email'] = 'test@microweber.com';
        $checkoutDetails['first_name'] = 'Client';
        $checkoutDetails['last_name'] = 'Microweber';
        $checkoutDetails['phone'] = '08812345678';
        $checkoutDetails['address'] = 'Business Park, Mladost 4';
        $checkoutDetails['city'] = 'Sofia';
        $checkoutDetails['state'] = 'Sofia City';
        $checkoutDetails['country'] = 'Bulgaria';
        $checkoutDetails['zip'] = '1000';
        $checkoutDetails['is_paid'] = 1;
        $checkoutDetails['order_completed'] = 1;


        $checkoutStatus = app()->order_manager->place_order($checkoutDetails);

        // order again to test
        $cart_add = update_cart($add_to_cart);
        $checkoutStatus = app()->order_manager->place_order($checkoutDetails);

        $count_sales = 2;

        $productQuery = \MicroweberPackages\Product\Models\Product::query();
        $productQuery->filter([
            'sales'=>$count_sales
        ]);
        $products = $productQuery->get();

        foreach ($products as $product) {
          $this->assertEquals($product->product_orders_count, $count_sales);
        }





        $productQuery = \MicroweberPackages\Product\Models\Product::query();
        $productQuery->filter([
            'sortSales'=>'asc'
        ]);
        $products = $productQuery->get();
        $i = 1;
        foreach ($products as $product) {
            $this->assertEquals($i, $product->sales);
            $i++;
        }


        $productQuery = \MicroweberPackages\Product\Models\Product::query();
        $productQuery->filter([
            'sortSales'=>'desc'
        ]);
        $products = $productQuery->get();
        $i = 2;
        foreach ($products as $product) {
            $this->assertEquals($i, $product->sales);
            $i--;
        }



    }


}
