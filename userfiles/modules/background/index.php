<?php
$style_attributes = [];
$style_attributes_overlay = [];
$background_color = '';

if (isset($params['data-background-color'])) {
    $background_color = $params['data-background-color'];
}
$background_image = '';

$background_image_option = get_option('data-background-image', $params['id']);

if (isset($params['data-background-image'])) {
    $background_image = $params['data-background-image'];
}
if ($background_image_option) {
    $background_image = $background_image_option;
}

if($background_image == 'none'){
    $background_image = '';
}

$background_image_attr_style = '';
if ($background_image) {
    $style_attributes[] = 'background-image: url(' . $background_image . ')';
}

$style_attr = '';
$background_color_option = get_option('data-background-color', $params['id']);
if ($background_color_option) {
    $background_color = $background_color_option;
}

if ($background_color != '') {
    $style_attributes_overlay[] = 'background-color: ' . $background_color;
}
$video_url = '';
if (isset($params['data-background-video'])) {
    $video_url = $params['data-background-video'];
}
$background_video_option = get_option('data-background-video', $params['id']);
if ($background_video_option) {
    $video_url = $background_video_option;
}
if ($video_url == 'none') {
    $video_url = '';
}

$video_html = '';
$video_attr_parent = '';
if ($video_url) {
    $video_html = '<video src="' . $video_url . '" autoplay muted loop playsinline></video>';
    $video_attr_parent = ' data-mwvideo="' . $video_url . '" ';
}
if ($style_attributes) {
    $style_attr_items = implode('; ', $style_attributes);
    $style_attr = 'style="' . $style_attr_items . '"';
}

$style_attr_overlay = '';
if ($background_color != '' || $background_image != '') {
    //   $style_attr_overlay = 'style="background-color: rgba(0,0,0,0.5);"';

}

if ($style_attributes_overlay) {
    $style_attributes_overlay_items = implode('; ', $style_attributes_overlay);
    $style_attr_overlay = 'style="' . $style_attributes_overlay_items . '"';
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


if (is_file($template_file) != false) {
    include($template_file);
} else {
    print lnotif("No template found. Please choose template.");
}

?>
