<?php

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
|
*/

Route::get('/page-test', function (){

/*
    $page = new \MicroweberPackages\Page\Page();
    $page->title = 'Simple Page';
    $page->save();


    $page = new \MicroweberPackages\Page\Page();
    $page->title = 'To stana be brat';
    $page->content_type = 'To stana be brat';
    $page->save();


   dd(\MicroweberPackages\Page\Page::all());*/


});

Route::name('admin.')->prefix('admin')->namespace('\MicroweberPackages\Page\Http\Controllers\Admin')->group(function () {

    Route::resource('pages', 'PagesController');

});