<?php

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
|
*/

Route::name('admin.')
    ->prefix('admin')
    ->namespace('\MicroweberPackages\Product\Http\Controllers\Admin')
    ->middleware(['XSS'])
    ->group(function () {

        Route::resource('products', 'RoductsController');
        
});

Route::get('admin/product-x', function () {

  // $product = new \MicroweberPackages\Product\Product();
   $product = \MicroweberPackages\Product\Product::with('price','specialPrice')->where('id', 1)->first();


    /*$product = \MicroweberPackages\Product\Product::where('id', 1)->first();
    $product->price = 99.99;
    $product->special_price = 69.99;
    $product->qty = 4;
    $product->sku = 'gumeni33';
    $product->title = 'Гумени Глави - Квартал № 41 - 1994 (цял албум)';
    $product->url = 'gumeni-glavi-cql-album';
    $product->description = 'Gumeni glavi brat! Shamara beshe moi';
    $product->save();*/

    dd($product->price()->value);

});