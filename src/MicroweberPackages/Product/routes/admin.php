<?php

//@todo return here
//return;

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
|
*/

//Route::name('admin.')
//    ->prefix('admin')
//    ->namespace('\MicroweberPackages\Product\Http\Controllers\Admin')
//    ->middleware(['XSS'])
//    ->group(function () {
//
//        Route::resource('products', 'RoductsController');
//
//});

Route::get('admin/xxx-xxx', function () {

    $product = \MicroweberPackages\Product\Product::where('id', 10)->first();

    dd('aaaa',$product->data);
});

Route::get('admin/product-xx', function () {

    $product = \MicroweberPackages\Product\Product::where('id', 10)->first();



    $product->data->field_name = 'test1';
    $product->data->field_value = 1;
    $product->data->save();


    $product->data->field_name = 'dddd';
    $product->data->field_value = 'sdfsdfsdf';
    $product->data->save();


dump( $product->data->dddd);

     $product = \MicroweberPackages\Product\Product::where('id', 10)->first();
    $product->data->aa = 1;
    $product->data->save();

    print 23123123;
   // dump($product);
//dd('aaaa',$product->data);
});
Route::get('admin/product-x', function () {

  // $product = new \MicroweberPackages\Product\Product();
   $product = \MicroweberPackages\Product\Product::with('price','specialPrice')->where('id', 1)->first();


    $product = \MicroweberPackages\Product\Product::where('id', 10)->first();
//
//    $content_data = new \MicroweberPackages\ContentData\ContentData(
//        ['sku','skyy9458944']
//    );
//    $product->attach($content_data);



//    $product->setAttr(['label' => 'red', 'order_d' => 12]);
//    $product->setDataFields(['name' => 'Petko', 'order_d' => 12]);


    $product->price = 99.99;
    $product->special_price = 69.99;
    $product->qty = 4;
    $product->sku = 'gumeni33';
    $product->title = 'Гумени Глави - Квартал № 41 - 1994 (цял албум)';
    $product->url = 'gumeni-glavi-cql-album';
    $product->description = 'Gumeni glavi brat! Shamara beshe moi';
    $product->save();


    dd($product->qty());

});