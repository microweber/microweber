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


Route::get('aaa', function(){


   event($event = new \Illuminate\Auth\Events\Registered(\App\Models\User::where('id', 2)->first(), []));


  // event($event = new \MicroweberPackages\Order\Events\OrderWasCreated(\MicroweberPackages\Order\Models\Order::where('id', 129)->first(), []));
});

// Route::get('favorite-drink', '\App\Http\Controllers\Controller@favoriteDrink');