<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// Route::get('favorite-drink', '\App\Http\Controllers\Controller@favoriteDrink');

Route::get('mw-test-api', function () {

    $get = MicroweberPackages\Content\Content::where('id', '17')->first();

    dd($get);

    /*$menuModel = \MicroweberPackages\Menu\Menu::where('id', 16)->first();
    $formBuilder = App::make(\MicroweberPackages\Form\FormElementBuilder::class);

    echo $formBuilder->text('title')
        ->setModel($menuModel)
        ->placeholder(_e("Title", true))
        ->value('fwafwa')
        ->autofocus(true);*/

   /* $newProduct3 = new MicroweberPackages\Product\Models\Product();
    $newProduct3->title = 'my-second-new-product-zero-for-filter-test-' . uniqid();
    $newProduct3->content_type = 'product';
    $newProduct3->subtype = 'product';
    $newProduct3->setCustomField(
        [
            'type'=>'price',
            'name'=>'price',
            'value'=>'0',
        ]
    );

    $newProduct3->setContentData(
        [
            'qty'=>'1',

        ]
    );
    $newProduct3->save();*/



});
