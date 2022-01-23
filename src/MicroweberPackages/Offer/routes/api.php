<?php


\Route::name('api.')

    ->prefix('api')
    ->middleware(['api', 'admin'])
    ->namespace('\MicroweberPackages\Offer\Http\Controllers\Api')
    ->group(function () {

        Route::post('offer_save', 'OfferApiResourceController@store')->name('offer.store');
        Route::post('offer_delete', 'OfferApiResourceController@destroy')->name('offer.delete');


        Route::any('offers_get_all', 'OfferApiController@index')->name('offer.get_all');
        Route::any('offers_get_by_product_id', 'OfferApiController@getByProductId');
    });



