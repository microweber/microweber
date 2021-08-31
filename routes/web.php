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

    $menuModel = \MicroweberPackages\Menu\Menu::where('id', 16)->first();
    $formBuilder = App::make(\MicroweberPackages\Form\FormElementBuilder::class);

    echo $formBuilder->text('title')
        ->setModel($menuModel)
        ->placeholder(_e("Title", true))
        ->value('fwafwa')
        ->autofocus(true);


});
