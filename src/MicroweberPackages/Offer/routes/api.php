<?php
Route::name('api.')
    ->prefix('api')
    ->middleware(['api', 'admin'])
    ->namespace('\MicroweberPackages\Offer\Http\Controllers\Api')
    ->group(function () {
        Route::any('offer_save', 'OfferApiController@store');
        Route::any('offers_get_all', 'OfferApiController@index');
        Route::any('offers_get_by_product_id', 'OfferApiController@getByProductId');
        Route::any('offer_delete', 'OfferApiController@destroy')->name('offer.delete');
    });



