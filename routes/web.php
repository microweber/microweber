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

/*
    $mem1 = memory_get_usage();
    $content = \MicroweberPackages\Content\Content::all();
    $mem2 = memory_get_usage();

    dump(app()->format->human_filesize($mem2 - $mem1));
*/

/*    $mem3 = memory_get_usage();
    $content = DB::select("SELECT * FROM content");
    $mem4 = memory_get_usage();

    dump(app()->format->human_filesize($mem4 - $mem3));
    */


});
