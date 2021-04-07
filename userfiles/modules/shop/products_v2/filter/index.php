<?php

$controller = \Illuminate\Support\Facades\App::make(\MicroweberPackages\Shop\ProductsV2\Filter\Http\Controllers\ProductFilterController::class);

$request = new \Illuminate\Http\Request();
$request->merge($params);
$request->merge($_REQUEST);

echo $controller->index($request);
