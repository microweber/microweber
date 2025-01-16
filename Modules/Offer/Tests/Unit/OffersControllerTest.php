<?php

namespace Modules\Offer\Tests\Unit;

use Illuminate\Support\Facades\Auth;
use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\User\Models\User;


class OffersControllerTest extends TestCase
{


    public function testSaveOfferFromController()
    {
        $this->loginAsAdmin();
        $categoryIds = [];


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
                'content_data' => [
                    'has_special_price' => 1
                ]
            ]
        );
        $productDataSaved = $response->getData()->data;


        $prices = get_product_prices($productDataSaved->id, true);
        $this->assertEquals(!empty($prices), true);
        $this->assertEquals($prices[0]['value'], $price);


        //add offer price
        $response = $this->call(
            'POST',
            route('api.offer.store'),
            [
                'product_id_with_price_id' => $productDataSaved->id . '|' . $prices[0]['id'],
                'offer_price' => $price_discounted,
                'is_active' => 1,

            ]
        );

        $offerDataSaved = $response->getData()->data;
        $this->assertEquals(isset($offerDataSaved->offer_id), true);
        $this->assertEquals(isset($offerDataSaved->success_edit), true);
        $this->assertDatabaseHas('offers', ['id' => $offerDataSaved->offer_id]);

        $offers = offers_get_by_product_id($productDataSaved->id);
        $this->assertEquals(!empty($offers), true);

        $offers_get_price = offers_get_price($productDataSaved->id, $prices[0]['id']);
        $prices = get_product_prices($productDataSaved->id, true);
        $this->assertEquals($offers_get_price['offer_price'], $price_discounted);
        //  $this->assertEquals(!empty($prices), true);
        // $this->assertEquals($prices[0]['value'], $price_discounted);
//


    }


}
