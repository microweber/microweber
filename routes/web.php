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


    //dump(prev_content(1019)['id']);

    //$get2 = app()->content_repository->getByParams(['keyword'=>'Amazon $25']);



/*    $title = 'Search By title '. uniqid('kw');
    app()->database_manager->extended_save_set_permission(true);
    $params = array(
        'title' => $title,
        'content_type' => 'post',
        'is_active' => 1
    );

    $saved_id = save_content($params);

    $x =  get_content('id='. $saved_id);*/

    $get = \MicroweberPackages\Content\Content::filter(['tags'=>'skype'])->get();
    //$get = get_content('keyword=kw61151fb832261');

    dump($get->toArray());

});
