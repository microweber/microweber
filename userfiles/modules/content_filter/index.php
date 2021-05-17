<?php

$controller = \Illuminate\Support\Facades\App::make(\MicroweberPackages\ContentFilter\Http\Controllers\ContentFilterController::class);

$request = new \Illuminate\Http\Request();
$request->merge($params);
$request->merge($_REQUEST);

echo $controller->index($request);
