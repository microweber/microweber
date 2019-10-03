<?php
/*
type: layout
name: Default
description: Default
*/
?>


<?php echo $code; ?>

<?php if($lazyload) { ?>
    <script>
        $(document).ready(function() {
            $('.js-mw-embed-iframe-<?php echo $params['id']; ?>').attr('src', $('.js-mw-embed-iframe-<?php echo $params['id']; ?>').attr('data-src'));
        });
    </script>
<?php } ?>

<?php
return;

$embed_data_tag = 'src="' . $upload . '"';
if ($lazyload) {
    $embed_data_tag = 'data-src="' . $upload . '"';
}

if ($prior != '2' or $prior == false) {
    if ($code != '') {
        $code = trim($code);

		if (stristr($code, '<iframe') !== false) {
			$code = preg_replace('#\<iframe(.*?)\ssrc\=\"(.*?)\"(.*?)\>#i', '<iframe$1 src="$2?wmode=transparent"$3>', $code);
		}

		if (video_module_is_embed($code) == true) {
			$code = '<div class="mwembed">' . $code . '</div>';
		} else {
			$code = video_module_url2embed($code, $w, $h, $autoplay, $thumb, $lazyload, $params['id']);
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

        $code = '<div class="mwembed"><video class="js-embed-'.$params['id'].'" controls width="' . $w . '" height="' . $h . '" ' . $autoplay . ' '.$embed_data_tag.' poster="'. $thumb .'"></video></div>';
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
}
?>

