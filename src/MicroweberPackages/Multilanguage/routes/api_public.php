<?php

Route::name('api.multilanguage.')
    ->prefix('api/multilanguage')
    ->group(function () {

        \Route::any('geolocaiton_test', function () {
            $geo = get_geolocation_detailed();
            return json_encode($geo, JSON_PRETTY_PRINT);
        })->name('geolocaiton_test')->middleware(['api','admin']);


        \Route::any('change_language', function (\Illuminate\Http\Request $request) {

            $api = new \MicroweberPackages\Multilanguage\MultilanguageApi();
            $response = $api->changeLanguage($request->all());

            return $response;

        })->name('change_language');


    });
