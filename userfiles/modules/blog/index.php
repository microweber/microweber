<?php

$controller = \Illuminate\Support\Facades\App::make(\MicroweberPackages\Blog\Http\Controllers\BlogController::class);

$request = new \Illuminate\Http\Request();
$request->merge($params);
$request->merge($_REQUEST);

$controller->setModuleParams($params);
$controller->setModuleConfig($config);
$controller->registerModule();

echo $controller->index($request);
