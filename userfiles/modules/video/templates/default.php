<?php

/*

type: layout

name: Default

description: Default

*/
?>

<?php

if ($prior != '2' or $prior == false) {
    if ($code != '') {
        $code = trim($code);

		if (stristr($code, '<iframe') !== false) {
			$code = preg_replace('#\<iframe(.*?)\ssrc\=\"(.*?)\"(.*?)\>#i', '<iframe$1 src="$2?wmode=transparent"$3>', $code);
		}

		if (video_module_is_embed($code) == true) {
			$code = '<div class="mwembed">' . $code . '</div>';
			$video_params['class'] = "mwembed";
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

        $code = '<div class="mwembed "><video controls width="' . $w . '" height="' . $h . '" ' . $autoplay . ' src="' . $upload . '" poster="'. $thumb .'"></video></div>';
    } else {
        $show_video_settings_btn = true;
    }
} else {
	$show_video_settings_btn = true;
}

if($show_video_settings_btn) {
    if(in_live_edit()){
        $code = "<div class='video-module-default-view mw-open-module-settings'><img src='" . $config['url_to_module'] . "video.svg' style='width: 65px; height: 65px;'/></div>";
    }

} else {

	if($lazyload && $use_thumbnail) {
		$unique_id = str_replace('-','',$params['id']);
		$css = '<style>.video-player{text-align:center;background: #000;}.video-player img:hover{cursor: pointer;}</style>' . "\n";

		if($enable_full_page_cache){

			// send video params via ajax to return html instead of using only js to avoid issues with full page cache and innerHTML value

            //get iframe params from code
			$video_params = array();
			$dom = new DomDocument;
			$dom->loadHTML( $code );
			$elems = $dom->getElementsByTagName('iframe');
			foreach ( $elems as $elm ) {
				if($elm->hasAttribute('src')) $video_params['src'] = $elm->getAttribute('src');
				if($elm->hasAttribute('width')) $video_params['width'] = $elm->getAttribute('width');
				if($elm->hasAttribute('height')) $video_params['height'] = $elm->getAttribute('height');
			}

            $script = '';
            if(isset($video_params['src']) && isset($video_params['width']) && isset($video_params['height'])){
				$script = '<script type="application/javascript">' .
				//'dataType: \'json\',' .
				'function replaceImg' . $unique_id . '(img){' . "\n" .
					'$.ajax({' . "\n" .
						'url: \'' . api_url('video_lazyload') .'\',' . "\n" .
						'data: {src: \'' . urlencode($video_params['src']) . '\', width: \'' . $video_params['width'] . '\', height: \'' . $video_params['height'] . '\'},' . "\n" .
						'type: \'POST\',' . "\n" .
						'success: function (response) {' . "\n" .
							'var div = document.createElement("div");' . "\n" .
							'div.innerHTML = response;' . "\n" .
							'img.parentNode.replaceChild(div, img);' . "\n" .
						'},' . "\n" .
						'error: function (XMLHttpRequest, textStatus, errorThrown) {' . "\n" .
							'console.log(XMLHttpRequest);' . "\n" .
							'console.log(textStatus);' . "\n" .
							'console.log(errorThrown);' . "\n" .
						'}' . "\n" .
					'});' . "\n" .
				'}</script>' . "\n";
            }

			$code = $css . $script . '<div class="video-player"><img onclick="javascript:replaceImg' . $unique_id . '(this);" src="' . $thumb . '"></div>';

		} else {

			$script = '<script>function replaceImg' . $unique_id . '(img){var div = document.createElement("div");div.innerHTML = \'' . $code . '\';img.parentNode.replaceChild(div, img);}</script>' . "\n";
			$code = $css . $script . '<div class="video-player"><img onclick="javascript:replaceImg' . $unique_id . '(this);" src="' . $thumb . '"></div>';
		}
	}
}


print $code;
?>