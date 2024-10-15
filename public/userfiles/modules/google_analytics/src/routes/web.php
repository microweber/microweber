<?php
use Illuminate\Support\Facades\Route;

Route::name('google-analytics.')
    ->prefix('/google-analytics')
    ->middleware(['web'])
    ->namespace('MicroweberPackages\Modules\GoogleAnalytics\Http\Controllers')
    ->group(function () {


    });
