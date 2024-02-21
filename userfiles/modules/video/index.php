<?php
require_once __DIR__ . DS . 'vendor/autoload.php';

$upload = false;
$getUpload = get_option('upload', $params['id']);
$getUpload = trim($getUpload);
if (!empty($getUpload)) {
    $upload = $getUpload;
}

$prior = get_option('prior', $params['id']);

$code = false;
$getCode = get_option('embed_url', $params['id']);
$getCode = trim($getCode);
if (!empty($getCode)) {
    $code = $getCode;
}

if ($code == false) {
    if ($upload == false && isset($params['url'])) {
        $code = $params['url'];
    }
}


$lazyload = get_option('lazyload', $params['id']);
if ($lazyload == false) {
    if (isset($params['lazyload'])) {
        $lazyload = intval($params['lazyload']);
    }
}



$thumb = get_option('upload_thumb', $params['id']);

$use_thumbnail = (!empty(trim($thumb))? true : false);

$show_video_settings_btn = false;

$autoplay = get_option('autoplay', $params['id']);
if(!$autoplay){
    if (isset($params['autoplay'])) {
        $autoplay = $params['autoplay'];
    }
}

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
if ($w == '') {
    $w = '100%';
}
if ($h == '') {
    $h = '350px';
}
if($upload and !$code){
    $prior = 2;
}

$video = new \Microweber\Modules\Video\VideoEmbed();
$video->setId($params['id']);
$video->setAutoplay($autoplay);

$getLoop = get_option('loop', $params['id']);
if ($getLoop == 1) {
    $video->setLoop(true);
}
$getHideControls = get_option('hide_controls', $params['id']);
if ($getHideControls == 1) {
    $video->setHideControls(true);
}
$getMuted = get_option('muted', $params['id']);
if ($getMuted == 1) {
    $video->setMuted(true);
}

$thumbnailApplied = false;
if (!empty($thumb)) {
    $filesUtils = new \MicroweberPackages\Utils\System\Files();
    if ($filesUtils->is_allowed_file($thumb)) {
        $video->setThumbnail($thumb);
        $thumbnailApplied = true;
    }
}

if ($lazyload) {
    $video->setLazyLoad(true);
}

if ($w !== '100%') {
    $video->setWidth($w . 'px');
}
if (strpos($h, 'px') !== false) {
    $video->setHeight($h);
} else {
    $video->setHeight($h . 'px');
}

if($code){
    if(\Illuminate\Support\Str::startsWith($code, 'http')){
        $upload = $code;
        $code = false;
    }
}

if ($upload) {
    $video->setUploadedVideoUrl($upload);
}

if ($code) {

    $video->setEmbedCode($code);
}

$video->setPlayEmbedVideo(true);
if ($upload && !$code) {
    $video->setPlayEmbedVideo(false);
    $video->setPlayUploadedVideo(true);
}

$code = $video->render();
$provider = $video->getProvider();

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
