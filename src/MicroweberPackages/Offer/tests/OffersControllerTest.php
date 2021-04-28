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

        $user = User::where('is_admin','=', '1')->first();
        Auth::login($user);



        $title = 'Product with offer price test! - '. rand();
        $contentBody = 'Product with offer price.';

        $price = rand(111,999);

        $response = $this->call(
            'POST',
            route('api.product.store'),
            [
                'title' => $title,
                'content_body' => $contentBody,
                'content' => '',
                'price'=>$price,
            ]
        );

        var_dump($response);


//        $productDataSaved = $response->getData()->data;
//        $this->assertEquals($productDataSaved->title, $title);
//        $this->assertEquals($productDataSaved->price, $price);
//        $this->assertEquals($productDataSaved->qty, $qty);
//        $this->assertEquals($productDataSaved->sku, $sku);
//



    }


}