<?php
$controller = \Illuminate\Support\Facades\App::make(\MicroweberPackages\Shop\Http\Controllers\ShopController::class);

$request = new \Illuminate\Http\Request();
$request->merge($params);
$request->merge($_REQUEST);

$controller->setModuleParams($params);
$controller->setModuleConfig($config);
$controller->registerModule();

echo $controller->index($request);

// THE NEW SHOP
//echo view('microweber-module-shop::render-livewire-shop', [
//    'moduleId' => $params['id'],
//    'moduleType' => 'shop',
//]);


?>
