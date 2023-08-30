<?php




\Illuminate\Support\Facades\Route::get('/example-route-testRequestResponseCode', function () {

    abort(123);

});

\Illuminate\Support\Facades\Route::post('/example-route-testJsonPost', function () {

    return response()->json(request()->all());

});
\Illuminate\Support\Facades\Route::patch('/example-route-testJsonPatch', function () {

    return response()->json(request()->all());

});

