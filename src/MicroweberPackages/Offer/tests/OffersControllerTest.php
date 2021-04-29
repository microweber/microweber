<?php

namespace MicroweberPackages\Category\tests;

use Illuminate\Support\Facades\Auth;
use MicroweberPackages\Content\Content;
use MicroweberPackages\Core\tests\TestCase;

use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\User\Models\User;


class OffersControllerTest extends TestCase
{


    public function testSaveOfferFromController()
    {
        $categoryIds = [];

        $user = User::where('is_admin', '=', '1')->first();
        Auth::login($user);


        $title = 'Product with offer price test! - ' . rand();
        $contentBody = 'Product with offer price.';

        $price = rand(111, 999);
        $price_discounted = rand(1, 10);

        $response = $this->call(
            'POST',
            route('api.product.store'),
            [
                'title' => $title,
                'content_body' => $contentBody,
                'content' => '',
                'price' => $price,
            ]
        );
        $productDataSaved = $response->getData()->data;

        $prod_id = $productDataSaved->id;

        $prices = get_product_prices($prod_id, true);
        $this->assertEquals(!empty($prices), true);
        $this->assertEquals($prices[0]['value'], $price);


        //add offer price
        $response = $this->call(
            'POST',
            route('api.offer.store'),
            [
                'product_id_with_price_id' => $productDataSaved->id.'|'.$prices[0]['id'],
                'offer_price' => $price_discounted,

            ]
        );



        $productDataSaved = $response->getData()->data;
        $this->assertEquals(isset($productDataSaved->offer_id), true);
        $this->assertEquals(isset($productDataSaved->success_edit), true);

        $prices = get_product_prices($prod_id, true);
        $this->assertEquals(!empty($prices), true);
        $this->assertEquals($prices[0]['value'], $price_discounted);

//


    }


}