<?php
require_once __DIR__ . DS . 'vendor/autoload.php';

$renderData = render_video_module($params);

$code = $renderData['code'];
$lazyload = $renderData['lazyload'];
$upload = $renderData['upload'];
$provider = $renderData['provider'];
$thumbnailApplied = $renderData['thumbnailApplied'];
$prior = $renderData['prior'];


$module_template = get_option('template', $params['id']);
if (empty($module_template)) {
    $module_template = get_option('data-template', $params['id']);
}

if ($module_template == false and isset($params['template'])) {
    $module_template = $params['template'];
}

if ($module_template != false) {
    $template_file = module_templates($config['module'], $module_template);
} else {
    $template_file = module_templates($config['module'], 'default');
}

if (is_file($template_file)) {
    echo '<div class="mw-prevent-interaction">';
    include($template_file);
    echo '</div>';
}

if(!$upload and !$code){
    print  lnotif('Click to edit video');
}
