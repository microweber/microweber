<?php

$moduleTemplate = get_option('data-template', $params['id']);
if ($moduleTemplate == false and isset($params['template'])) {
    $moduleTemplate = $params['template'];
}
if ($moduleTemplate != false) {
    $templateFile = module_templates($config['module'], $moduleTemplate);
} else {
    $templateFile = module_templates($config['module'], 'default');
}

if ($templateFile) {
    $templateDir = dirname($templateFile);
    if (is_dir($templateDir)) {
        $defaultDir = dirname($templateDir) . DS . 'default';
        if (is_dir($defaultDir)) {
            View::prependNamespace('blog', $defaultDir);
        }

        View::prependNamespace('blog', $templateDir);
    }
}

$controller = \Illuminate\Support\Facades\App::make(\MicroweberPackages\Blog\Http\Controllers\BlogController::class);

$request = new \Illuminate\Http\Request();
$request->merge($params);
$request->merge($_REQUEST);

$controller->setModuleParams($params);
$controller->setModuleConfig($config);

echo $controller->index($request);

