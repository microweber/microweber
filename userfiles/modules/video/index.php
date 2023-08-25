<?php
require_once __DIR__ . DS . 'vendor/autoload.php';

$upload = get_option('upload', $params['id']);
$upload = trim($upload);

$prior = get_option('prior', $params['id']);
$code = get_option('embed_url', $params['id']);

if ($code == false) {
    if ($upload == false && isset($params['url'])) {
        $code = $params['url'];
    }
}
$code = trim($code);

$enable_full_page_cache = get_option('enable_full_page_cache','website');

$lazyload = get_option('lazyload', $params['id']);

$lazyload = ((!empty($lazyload) && $lazyload == 'y')? true : false);

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
    $h = '350px';
}
if ($autoplay == '') {
    $autoplay = '0';
}
if($upload and !$code){
    $prior = 2;
}

$video = new \Microweber\Modules\Video\VideoEmbed();
$video->setId($params['id']);
$video->setLazyLoad($lazyload);
$video->setAutoplay($autoplay);
$video->setThumbnail($thumb);

if ($w !== '100%') {
    $video->setWidth($w . 'px');
}
if (strpos($h, 'px') !== false) {
    $video->setHeight($h);
} else {
    $video->setHeight($h . 'px');
}

$video->setUploadedVideoUrl($upload);
$video->setEmbedCode($code);
$video->setPlayEmbedVideo(true);
if ($upload && !$code) {
    $video->setPlayEmbedVideo(false);
    $video->setPlayUploadedVideo(true);
}



$code = $video->render();

$provider = $video->getProvider();

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
    echo '<div class="mw-prevent-interaction">';
    include($template_file);
    echo '</div>';
}

if(!$upload and !$code){
    print  lnotif('Click to edit video');
}
