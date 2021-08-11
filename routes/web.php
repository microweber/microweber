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

    $get2 = app()->content_repository->getByParams(['categories'=>3]);


    dd($get2);

});
