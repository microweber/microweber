<?php

if (!function_exists('video_module_url2embed')) {
    include_once(__DIR__ . DS . 'functions.php');
}

$prior = get_option('prior', $params['id']);

$code = get_option('embed_url', $params['id']);

$upload = get_option('upload', $params['id']);


$w = get_option('width', $params['id']);
$h = get_option('height', $params['id']);
$autoplay = get_option('autoplay', $params['id']) == 'y';


if ($w == '') {
    $w = '450';
}
if ($h == '') {
    $h = '350';
}
if ($autoplay == '') {
    $autoplay = '0';
}

 
if ($prior != '2') {
    if ($code != '') {
        $code = html_entity_decode($code);
        if (stristr($code, '<iframe') !== false) {
            $code = preg_replace('#\<iframe(.*?)\ssrc\=\"(.*?)\"(.*?)\>#i',
                '<iframe$1 src="$2?wmode=transparent"$3>', $code);
				 //print '<div class="mwembed">' . $code . '</div>';
        } 
		
		
		if(video_module_is_embed($code) == true) {
            print '<div class="mwembed">' . $code . '</div>';
        } else {
            print video_module_url2embed($code, $w, $h, $autoplay);
        }
    } else {
        print lnotif("<div class='video-module-default-view mw-open-module-settings'><img src='" . $config['url_to_module'] . "video.png' /></div>", true);
    }
} else if ($prior == '2') {
    if ($upload != '') {
        if ($autoplay == '0') {
            $autoplay = '';
        } else {
            $autoplay = 'autoplay';
        }
        print '<div class="mwembed "><video controls width="' . $w . '" height="' . $h . '" ' . $autoplay . ' src="' . $upload . '"></video></div>';
    } else {
     print lnotif("<div class='video-module-default-view mw-open-module-settings'><img src='" . $config['url_to_module'] . "video.png' /></div>", true);

    }
} else {
    print lnotif("<div class='video-module-default-view mw-open-module-settings'><img src='" . $config['url_to_module'] . "video.png' /></div>", true);
}
 