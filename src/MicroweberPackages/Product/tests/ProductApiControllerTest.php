<?php


namespace MicroweberPackages\Product\tests;


use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\User\Models\User;
use Illuminate\Support\Facades\Auth;

class ProductApiControllerTest extends TestCase
{

    public function testAddProductFull()
    {
        $categoryIds = [];

        $user = User::where('is_admin','=', '1')->first();
        Auth::login($user);


        $category = new Category();
        $category->title = 'New cat for my custom model'. rand();
        $category->save();
        $categoryIds[] = $category->id;

        $category = new Category();
        $category->title = 'New cat for my custom model'. rand();
        $category->save();
        $categoryIds[] = $category->id;

        $title = 'Iphone and spire 4ever! - '. rand();
        $title2 = 'Iphone and spire 4ever! 2 - '. rand();
        $contentBody = 'This is my cool product descriotion.';

        $price = rand(111,999);
        $qty = rand();
        $sku = rand();
        $contentData = [
          'fanta'=>'cocacolla',
          'price'=>$price,
          'qty'=>$qty,
          'sku'=>$sku
        ];

        $customFields = [
          'price'=>$price
        ];

        $response = $this->call(
            'POST',
            route('api.product.store'),
            [
                'title' => $title,
                'category_ids'=>implode(',', $categoryIds),
                'content_body' => $contentBody,
                'content' => '',
                'custom_fields'=>$customFields,
                'content_data'=>$contentData
            ]
        );

        $productDataSaved = $response->getData()->data;
        $this->assertEquals($productDataSaved->title, $title);
        $this->assertEquals($productDataSaved->price, $price);
        $this->assertEquals($productDataSaved->qty, $qty);
        $this->assertEquals($productDataSaved->sku, $sku);



        $response = $this->call(
            'PUT',
            route('api.product.update', [
                'product' => $productDataSaved->id,
                'title' => $title2,
            ])

        );

        $this->assertEquals(200, $response->status());
        $productDataSaved = $response->getData()->data;

        $this->assertEquals($productDataSaved->title, $title2);
        $this->assertEquals($productDataSaved->price, $price);
        $this->assertEquals($productDataSaved->qty, $qty);
        $this->assertEquals($productDataSaved->sku, $sku);


        $response = $this->call(
            'PUT',
            route('api.product.update', [
                'product' => $productDataSaved->id,
                'title'=>'new-title',
                'price' => '',
            ])

        );
        $this->assertEquals(200, $response->status());

        $productDataSaved = $response->getData()->data;
        $this->assertEquals($productDataSaved->price, null);


        $response = $this->call(
            'PUT',
            route('api.product.update', [
                'product' => $productDataSaved->id,
                'title'=>'second new title',
                'price' => 0,
            ])

        );
        $this->assertEquals(200, $response->status());

        $productDataSaved = $response->getData()->data;
        $this->assertEquals($productDataSaved->price, 0);



        $response = $this->call(
            'PUT',
            route('api.product.update', [
                'product' => $productDataSaved->id,
                'title'=>'second new product title',
                'price' => $price,
            ])

        );
        $this->assertEquals(200, $response->status());

        $productDataSaved = $response->getData()->data;
        $this->assertEquals($productDataSaved->price, $price);
    }

    public function testSaveProductFromController()
    {
        $user = User::where('is_admin','=', '1')->first();
        Auth::login($user);

        $title = 'Test add product from api ' . rand();
        $title2 = 'Test update product from api ' . rand();

        $response = $this->call(
            'POST',
            route('api.product.store'),
            [
                'title' => $title,
                'content_body' => '<b>Bold text</b>',
                'content' => '<b onmouseover=alert(‘XSS testing!‘)>XSS</b>   <IMG SRC=j&#X41vascript:alert(\'test2\')>'
            ]
        );


        $this->assertEquals(201, $response->status());
        $product_data = $response->getData();
        $this->assertEquals($product_data->data->title, $title);

        $product_id = $product_data->data->id;


        $response = $this->call(
            'GET',
            route('api.product.show',
                [
                    'product' => $product_id,
                ])
        );

        $product_data = $response->getData();


        $this->assertEquals($product_data->data->title, $title);


        $response = $this->call(
            'PUT',
            route('api.product.update', [
                'product' => $product_id,
                'title' => $title2,
            ])

        );

        $this->assertEquals(200, $response->status());

        $response = $this->call(
            'GET',
            route('api.product.show',
                [
                    'product' => $product_id,
                ])
        );

        $product_data = $response->getData();

        $this->assertEquals($product_data->data->title, $title2);



        $response = $this->call(
            'GET',
            route('api.product.index',
                [
                ])
        );

        $product_data = $response->getData();
        $this->assertEquals(true,!empty($product_data->data));

        $response = $this->call(
            'GET',
            route('api.product.quick-view',
                [
                    'id' => $product_id,
                    'json'=>true
                ])
        );
        $response->getContent();


    }

    public function testDestroyContentFromController()
    {
        $user = User::where('is_admin', '=', '1')->first();
        Auth::login($user);

        $title = 'Test add content from api ' . rand();

        $response = $this->call(
            'POST',
            route('api.product.store'),
            [
                'title' => $title,
            ]
        );


        $response = $this->call(
            'DELETE',
            route('api.product.destroy', [
                'product' => $response->getData()->data->id,
            ])
        );

        $this->assertEquals(200, $response->status());
        $contentData = $response->getData()->data->id;

        $this->assertNotEmpty($contentData);
    }



    public function testProductDiscountLabelValues()
    {
        $user = User::where('is_admin', '=', '1')->first();
        Auth::login($user);

        $title = 'Product with labels';

        $contentData = [
            'title' => $title,
            'sku' => 'sku-test',
            'label' => 'new',
            'label-color' => 'green',
            'label-type' => 'text',
            'special_price' => 10,


        ];

        $response = $this->call(
            'POST',
            route('api.product.store'),
            [
                'title' => $title,
                'price' => 100,

                'content_data' => $contentData
            ]
        );
        $saved = $response->getContent();

        $contentData = content_data($response->getData()->data->id);

        $this->assertEquals($contentData['label'], 'new');
        $this->assertEquals($contentData['label-color'], 'green');
        $this->assertEquals($contentData['label-type'], 'text');
        $this->assertEquals($contentData['sku'], 'sku-test');


        $price = get_product_price($response->getData()->data->id);
        $this->assertEquals($price, 100);

        $discount = get_product_discount_price($response->getData()->data->id);
        $this->assertEquals($discount, 10);

        $discountPercent = get_product_discount_percent($response->getData()->data->id);
        $this->assertEquals($discountPercent, 90);

    }
}
