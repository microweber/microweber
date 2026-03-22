<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'api/seo', 'middleware' => ['api']], function () {
    // Route::get('metadata/{contentId}', 'Api\SeoController@getMetadata');
    // Route::post('metadata/{contentId}', 'Api\SeoController@updateMetadata');
});

// Admin routes
Route::group(['prefix' => 'admin/seo', 'middleware' => ['web', 'auth', 'admin']], function () {
    // Admin SEO settings
});
