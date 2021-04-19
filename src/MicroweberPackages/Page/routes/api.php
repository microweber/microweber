<?php

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
|
*/

Route::name('api.')
    ->prefix('api')
    ->middleware(['api'])
    ->namespace('\MicroweberPackages\Page\Http\Controllers\Api')
    ->group(function () {

        Route::apiResource('page', 'PageApiController');

    });


/*
Route::get('basi', function() {

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://packages-satis.microweberapi.com/packages.json",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_POSTFIELDS => "",
        CURLOPT_HTTPHEADER => [
            "Authorization: Basic W3siaWQiOjEsInVwZGF0ZWRfYXQiOiIyMDIxLTA0LTE5VDEwOjMzOjQ0LjAwMDAwMFoiLCJjcmVhdGVkX2F0IjoiMjAyMS0wNC0xNlQxMzo0NjoxMy4wMDAwMDBaIiwiY3JlYXRlZF9ieSI6MSwiZWRpdGVkX2J5IjoxLCJyZWxfdHlwZSI6IiIsInJlbF9pZCI6IiIsImxvY2FsX2tleSI6Ikhvc3RpbmdQcm83NGEwMGVkNjU1IiwibG9jYWxfa2V5X2hhc2giOiJlM2MzZmI3ODE4ODYyMWY3MDljOTVlMDg2NTBkZGRjNyIsInJlZ2lzdGVyZWRfbmFtZSI6ImFsZXgiLCJjb21wYW55X25hbWUiOiIiLCJkb21haW5zIjoiIiwic3RhdHVzIjoiYWN0aXZlIiwicHJvZHVjdF9pZCI6MzksInNlcnZpY2VfaWQiOjEyMDExLCJiaWxsaW5nX2N5Y2xlIjoiTW9udGhseSIsInJlZ19vbiI6IjIwMTktMDYtMDUgMDA6MDA6MDAiLCJkdWVfb24iOiIyMDIxLTA1LTA1IDAwOjAwOjAwIn1d"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
         $maiko = json_decode($response, true);
        dd($maiko['packages']['microweber/template-guesthouse']);
    }

});*/
