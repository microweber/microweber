<?php
$controller = \Illuminate\Support\Facades\App::make(\MicroweberPackages\Shop\Http\Controllers\ShopController::class);

$request = new \Illuminate\Http\Request();
$request->merge($params);
$request->merge($_REQUEST);

echo $controller->index($request);
