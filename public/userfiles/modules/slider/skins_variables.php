<?php
$defaults = array(
    'images' => '',
    'primaryText' => 'My Slider',
    'secondaryText' => 'Your slogan here',
    'seemoreText' => 'See more',
    'url' => '',
    'urlText' => '',
    'skin' => 'default'
);

$settings = get_module_option('settings', $params['id']);
$json = json_decode($settings, true);
if (isset($json) == false or count($json) == 0) {
    $json = array(0 => $defaults);
}

$module_template = get_module_option('data-template', $params['id']);
if (!$module_template OR $module_template == 'default') {
    $module_template = 'bxslider-skin-1';
}
$module_template_clean = str_replace('.php', '', $module_template);

$default_skins_path = $config['path_to_module'] . 'templates/' . $module_template_clean . '/skins';
$template_skins_path = template_dir() . 'modules/slider/templates/' . $module_template_clean . '/skins';
$skins = array();

if (is_dir($template_skins_path)) {
    $skins = scandir($template_skins_path);
}

if (empty($skins) and is_dir($default_skins_path)) {
    $skins = scandir($default_skins_path);
}

$count = 0;

$module_template_check = get_module_option('data-template', $params['id']);
if ($module_template_check == false AND isset($params['template'])) {
    $module_template = $params['template'];
}
