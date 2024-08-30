<?php

$facebook_enabled = get_option('facebook_enabled', $params['id']) == '1';
$twitter_enabled = get_option('twitter_enabled', $params['id']) == '1';
$pinterest_enabled = get_option('pinterest_enabled', $params['id']) == '1';
$viber_enabled = get_option('viber_enabled', $params['id']) == '1';
$whatsapp_enabled = get_option('whatsapp_enabled', $params['id']) == '1';
$linkedin_enabled = get_option('linkedin_enabled', $params['id']) == '1';
$googleplus_enabled = get_option('googleplus_enabled', $params['id']) == '1';

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
}

if ($facebook_enabled == '' and $twitter_enabled == '' and $pinterest_enabled == '' and $viber_enabled == '' and $whatsapp_enabled == '' and $linkedin_enabled == '') {
    print lnotif('Click here to edit Social Media Share');
}
