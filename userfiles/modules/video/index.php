<?php

$prior = get_option('prior', $params['id']);

$code = get_option('embed_url', $params['id']);
if ($code == false) {
    if (isset($params['url'])) {
        $code = $params['url'];
    }
}

$upload = get_option('upload', $params['id']);

$thumb = get_option('upload_thumb', $params['id']);

$use_thumbnail = (!empty(trim($thumb))? true : false);

$show_video_settings_btn = false;

$autoplay = get_option('autoplay', $params['id']) == 'y';

$w = get_option('width', $params['id']);

$h = get_option('height', $params['id']);

if ($w == false) {
    if (isset($params['width'])) {
        $w = intval($params['width']);
    }
}

if ($h == false) {
    if (isset($params['height'])) {
        $h = intval($params['height']);
    }
}
if ($autoplay == false) {
    if (isset($params['autoplay'])) {
        $autoplay = intval($params['autoplay']);
    }
}
if ($w == '') {
    $w = '100%';
}
if ($h == '') {
    $h = '350';
}
if ($autoplay == '') {
    $autoplay = '0';
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
if (is_file($template_file)) {
    include($template_file);
}
