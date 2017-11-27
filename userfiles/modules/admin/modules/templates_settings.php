<?php

 if (!isset($params['parent-module']) and isset($params['root-module'])) {
    $params['parent-module'] = $params['root-module'];
}
if (!isset($params['parent-module-id']) and isset($params['root-module-id'])) {
    $params['parent-module-id'] = $params['root-module-id'];
}


if (!isset($params['parent-module']) and isset($params['data-prev-module'])) {
    $params['parent-module'] = $params['data-prev-module'];
}
if (!isset($params['parent-module-id']) and isset($params['data-prev-module-id'])) {
    $params['parent-module-id'] = $params['data-prev-module-id'];
}

if (!isset($params['parent-module-id'])) {
    return;

}


//d($params['parent-module']);

$params['id'] = $params['parent-module-id'];

if (isset($params['parent-template'])) {
    $module_template = $params['parent-template'];

} else {
    $module_template = get_option('data-template', $params['parent-module-id']);

}


if ($module_template == false) {
    $module_template = 'default';
}



if ($module_template != false) {
    $template_file = module_templates($params['parent-module'], $module_template, true);
} else {
    $template_file = module_templates($params['parent-module'], 'default', true);
}




if (isset($template_file) and $template_file != false and is_file($template_file)) {

    include($template_file);
}



