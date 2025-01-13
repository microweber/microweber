<?php


use  \Illuminate\Support\Facades\Route;


Route::group(
    [

    ], function () {

    Route::any('/', \MicroweberPackages\Frontend\Http\Controllers\FrontendController::class . '@index')
        ->middleware('web')
        ->name('home');


    Route::any('{any}', array('as' => 'all', 'uses' =>
        \MicroweberPackages\Frontend\Http\Controllers\FrontendController::class . '@index'))
        ->middleware('web')
        ->where('all', '.*');

    Route::fallback(function () {
        return response('Page not found', 404)->withHeaders([
            'Content-Type' => 'text/plain',
            'X-Fallback-Message' => 'true',
            'X-Powered-By' => 'Microweber'
        ]);
    });
});







