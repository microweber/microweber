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
    $productQuery->disableCache(true);
    $productQuery->filter([
       // 'sortOrders'=>'asc'
       // 'orderBy'=>'price,asc',
     //   'orderBy'=>'sales,desc',
       // 'orderBy'=>'sales,asc',
     //   'orderBy'=>'price,desc',
     //   'sales'=>5,
     //   'sales'=>5,
      //  'inStock'=>false,
      'keyword'=>'amazon'

    ]);
  //  $productQuery =$productQuery->whereHas('productOrders');
//    $productQuery =$productQuery->whereHas('customFieldsValues', function ($query) {
//       return $query->where('custom_fields.type', '=', 'price');
//    });

    //  $productQuery =$productQuery->whereHas('productOrders');
 //  $productQuery =$productQuery->whereHas('customFieldsPrices');
//    $productQuery = $productQuery->whereHas('customField')->joinRelationship('customFieldsPrices', function ($join) {
//        $join->where('custom_fields.type', '=', 'price');
//    });

    $query = $productQuery;

    $sqlQuery = \Illuminate\Support\Str::replaceArray(
        '?',
        collect($query->getBindings())
            ->map(function ($i) {
                if (is_object($i)) {
                    $i = (string)$i;
                }
                return (is_string($i)) ? "'$i'" : $i;
            })->all(),
        $query->toSql());

    dump($sqlQuery);



    dump($productQuery->toSql());
    $products = $productQuery->get();
    dump($products->first()->toArray());
    dump($products->toArray());
    return;

    $productQuery = \MicroweberPackages\Product\Models\Product::query();
    $productQuery->filter([
       // 'sortOrders'=>'asc'
        'sales'=>2

    ]);
    $query = $productQuery;
    dump($productQuery->toSql());

    $sqlQuery = \Illuminate\Support\Str::replaceArray(
        '?',
        collect($query->getBindings())
            ->map(function ($i) {
                if (is_object($i)) {
                    $i = (string)$i;
                }
                return (is_string($i)) ? "'$i'" : $i;
            })->all(),
        $query->toSql());

    dump($sqlQuery);

    $products = $productQuery->get();

    foreach ($products as $product) {
        var_dump($product->sales);
    }

    dump($products);

});
