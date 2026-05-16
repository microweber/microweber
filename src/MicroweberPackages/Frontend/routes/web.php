<?php


use  \Illuminate\Support\Facades\Route;


Route::group(
    [

    ], function () {

    Route::any('/', \MicroweberPackages\Frontend\Http\Controllers\FrontendController::class . '@index')
        ->middleware('web')
        ->name('home');

/*
    Route::any('{any}', array('as' => 'all', 'uses' =>
        \MicroweberPackages\Frontend\Http\Controllers\FrontendController::class . '@index'))
        ->middleware('web')
        ->where('all', '.*');
*/


/*    Route::any('{slug}', array('as' => 'slug', 'uses' =>
        \MicroweberPackages\Frontend\Http\Controllers\FrontendController::class . '@index'))
        ->middleware('web')
        ->where('slug', '.*')->name('website');
    */

    // task-2026-05-16-256d49 / AI-735 — added `admin` to the
    // excluded-prefix regex. Pre-fix, unmatched admin URLs
    // (e.g. /admin/media, /admin/custom-fields) fell through to
    // the frontend catch-all and rendered the "My title / My text
    // content" placeholder page. Adding `admin` lets unmatched
    // admin URLs propagate to Route::fallback() below which
    // returns a clean 404. Surfaces 1 (front-end 404 returning
    // 200) and 2 (search results template missing) remain — they
    // sit deeper in FrontendController + Search-module routing
    // and are tracked as AI-735a / AI-735b follow-ups.
    Route::any('{slug}', array('as' => 'slug', 'uses' =>
        \MicroweberPackages\Frontend\Http\Controllers\FrontendController::class . '@index'))
        ->middleware('web')
        ->where('slug', '^(?!vendor|packages|template|modules|css|storage|userfiles|js|admin).*')
        ->name('website');

    Route::fallback(function () {
        $url = url_string();
        return response('Page not found at url: ' . $url, 404)->withHeaders([
            'Content-Type' => 'text/plain',
            'X-Fallback-Message' => 'true',
            'X-Powered-By' => 'Microweber'
        ]);
    });
});







