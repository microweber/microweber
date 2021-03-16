<?php

$controller = \Illuminate\Support\Facades\App::make(\MicroweberPackages\Shop\Products\Http\Controllers\ProductsController::class);

$request = new \Illuminate\Http\Request();
$request->merge($_REQUEST);

$controller->setModuleParams($params);
$controller->setModuleConfig($config);

echo $controller->index($request);
