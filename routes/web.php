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



Route::get('favorite-drink', function(){

  //  $aa = \MicroweberPackages\Product\Models\Product::with('tags')->get();
    $aa = \MicroweberPackages\Post\Models\Post::with('media')->with('tagged')->get();

    dump($aa);

});
