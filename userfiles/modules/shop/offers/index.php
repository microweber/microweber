<?php


$data = false;
$offer_id = false;
if (isset($params['data-offer-id'])) {
    $offer_id = $params['data-offer-id'];
}

if (!$offer_id) {
    return;
}

//WAS $data = offer_get_by_id($offer_id);
$data = app()->offer_repository->getById($offer_id);

$params['data-in-stock'] = true;
//if (isset($params['data-parent-id']) && isset($params['data-in-stock']) && isset($params['data-count']) && isset($params['data-title']) && isset($params['data-price-name']) && isset($params['data-retail-price']) && isset($params['data-offer-price'])) {
if ($data) {

    $module_template = get_option('template', $params['id']);
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
    }
}
