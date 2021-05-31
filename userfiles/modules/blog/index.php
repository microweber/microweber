<?php


$controller = \Illuminate\Support\Facades\App::make(\MicroweberPackages\Blog\Http\Controllers\BlogController::class);

$request = new \Illuminate\Http\Request();
$request->merge($params);
$request->merge($_REQUEST);

$controller->setModuleParams($params);
$controller->setModuleConfig($config);

echo $controller->index($request);

/*
$module_template = get_option('data-template', $params['id']);
if ($module_template == false and isset($params['template'])) {
    $module_template = $params['template'];
}
if ($module_template != false) {
    $template_file = module_templates($config['module'], $module_template);
} else {
    $template_file = module_templates($config['module'], 'default');
}


if (is_file($template_file) != false) {
    include($template_file);
} else {
    print lnotif("No template found. Please choose template.");
}*/
?>
