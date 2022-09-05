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

Route::get('a', function () {

    $productQuery = \MicroweberPackages\Product\Models\Product::query();
    $productQuery->whereHas('cart', function ($query) {
        $query->whereHas('order');
        $query->with('order');
    });
    $productQuery->with('cart');

    $products = $productQuery->first();


    dump($products);

});
