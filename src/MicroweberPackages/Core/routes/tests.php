<?php

use \Illuminate\Support\Facades\Route;


Route::get('/example-route-testRequestResponseCode', function () {

    abort(123);

});

Route::post('/example-route-testJsonPost', function () {

    return response()->json(request()->all());

});
Route::patch('/example-route-testJsonPatch', function () {

    return response()->json(request()->all());

});


Route::get('/example-route-test-throttle-middleware', function () {
    $resp = [];
    $resp['ok'] = 1;
    $resp['ip'] = request()->ip();
    $resp['data'] = request()->all();
    return response()->json($resp);

})->middleware('throttle:10,1');

