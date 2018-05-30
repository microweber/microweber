<?php
if ($prior != '2' or $prior == false) {
    if ($code != '') {
        $code = trim($code);

		if (stristr($code, '<iframe') !== false) {
			$code = preg_replace('#\<iframe(.*?)\ssrc\=\"(.*?)\"(.*?)\>#i', '<iframe$1 src="$2?wmode=transparent"$3>', $code);
		}

		if (video_module_is_embed($code) == true) {
			$code = '<div class="mwembed">' . $code . '</div>';
		} else {
			$code = video_module_url2embed($code, $w, $h, $autoplay);
		}

    } else {
    	$show_video_settings_btn = true;
    }
} else if ($prior == '2') {
    if ($upload != '') {
        if ($autoplay == '0') {
            $autoplay = '';
        } else {
            $autoplay = 'autoplay';
        }
        $code = '<div class="mwembed "><video controls width="' . $w . '" height="' . $h . '" ' . $autoplay . ' src="' . $upload . '"></video></div>';
    } else {
        $show_video_settings_btn = true;
    }
} else {
	$show_video_settings_btn = true;
}

if($show_video_settings_btn) {
	$code = "<div class='video-module-default-view mw-open-module-settings'><img src='" . $config['url_to_module'] . "video.png' /></div>";
} else {
	if($use_thumbnail) {
		$unique_id = str_replace('-','',$params['id']);
		$css = '<style>.video-player{background: #000;}.video-player img:hover{cursor: pointer;}</style>' . "\n";
		$script = '<script>function replaceImg' . $unique_id . '(img){var div = document.createElement("div");div.innerHTML = \'' . $code . '\';img.parentNode.replaceChild(div, img);}</script>' . "\n";
		$code = $css . $script . '<div class="video-player"><img onclick="javascript:replaceImg' . $unique_id . '(this);" src="' . $thumb . '"></div>';
	}
}
print $code;
?>