<?php

use  \Illuminate\Support\Facades\Route;
use Modules\Offer\Http\Controllers\Api\OfferApiResourceController;

Route::name('api.')
    ->prefix('api')
    ->middleware(['api', 'admin', 'xss'])
    ->group(function () {

        Route::post('offer_save', OfferApiResourceController::class . '@store')->name('offer.store');
        Route::post('offer_delete', OfferApiResourceController::class . '@destroy')->name('offer.delete');
        Route::any('offers_get_all', OfferApiResourceController::class . '@index')->name('offer.get_all');
        Route::any('offers_get_by_product_id', OfferApiResourceController::class . '@getByProductId')->name('offer.get_by_product_id');
        Route::get('offers_search_products', OfferApiResourceController::class . '@searchProducts')->name('offer.search_products');
    });



