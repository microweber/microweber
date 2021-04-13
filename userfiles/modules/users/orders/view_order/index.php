<?php

if (!isset($_GET['id'])) {
    return;
}

$user = get_user();
if (is_logged() == false) {
    return;
}

$order = [];
$orderId = (int) $_GET['id'];
$findOrder = \MicroweberPackages\Order\Models\Order::where('created_by', user_id())->where('id', $orderId)->first();

if ($findOrder !== null) {
    $order = $findOrder->toArray();
}

if (empty($order)) {
    return;
}

$module_template = get_option('data-template', $params['id']);
if ($module_template == false and isset($params['template'])) {
    $module_template = $params['template'];
}

if ($module_template != false) {
    $template_file = module_templates($config['module'], $module_template);
} else {
    $template_file = module_templates($config['module'], 'default');
}

if (isset($template_file) and is_file($template_file) != false) {
    include($template_file);
} else {
    $template_file = module_templates($config['module'], 'default');
    include($template_file);
}


