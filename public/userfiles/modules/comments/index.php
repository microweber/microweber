<?php

$allowComments = get_option('allow_comments','comments');
if ($allowComments == 'n') {
    return;
}

$controller = \Illuminate\Support\Facades\App::make(\MicroweberPackages\Modules\Comments\Http\Controllers\CommentsController::class);

$request = new \Illuminate\Http\Request();
$request->merge($params);
$request->merge($_REQUEST);

$controller->setModuleParams($params);
$controller->setModuleConfig($config);
$controller->registerModule();

echo $controller->index($request);
