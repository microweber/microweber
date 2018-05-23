<?php

/*

type: layout

name: Default

description: Default

*/

?>

<br />

<div class=" box-shadow-wide">
    <?php
    if ($prior != '2' or $prior == false) {
        if ($code != '') {
            $code = trim($code);
            if (stristr($code, '<iframe') !== false) {
                $code = preg_replace('#\<iframe(.*?)\ssrc\=\"(.*?)\"(.*?)\>#i',
                    '<iframe$1 src="$2?wmode=transparent"$3>', $code);
                //print '<div class="mwembed">' . $code . '</div>';
            }


            if (video_module_is_embed($code) == true) {
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
            print ("<div class='video-module-default-view mw-open-module-settings'><img src='" . $config['url_to_module'] . "video.png' /></div>");

        }
    } else {
        print "<div class='video-module-default-view mw-open-module-settings'><img src='" . $config['url_to_module'] . "video.png' /></div>";
    }
    ?>
 </div>
