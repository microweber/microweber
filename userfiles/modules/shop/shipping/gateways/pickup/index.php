<?php

$shipping_pickup_instructions =  get_option('shipping_pickup_instructions', 'shipping');

$module_template = get_option('template', $params['id']);


if ($module_template == false and isset($params['template'])) {
    $module_template = $params['template'];
} elseif ($module_template == false) {
    $module_template = 'default';
}


if ($module_template != false) {
    $template_file = module_templates($config['module'], $module_template);

} else {
    $template_file = module_templates($config['module'], 'default');

}


if (isset($template_file) and ($template_file) != false and is_file($template_file) != false) {
    include($template_file);
} else {
    $template_file = module_templates($config['module'], 'default');
    if (($template_file) != false and is_file($template_file) != false) {
        include($template_file);
    } else {
        $complete_fallback = dirname(__FILE__) . DS . 'templates' . DS . 'default.php';
        if (is_file($complete_fallback) != false) {
            include($complete_fallback);
        }

    }
    //print 'No default template for '.  $config['module'] .' is found';
}

