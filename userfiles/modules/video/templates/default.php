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
        ?>
        <style>
            .mwembed {

            }

            .mwembed .overlay-holder {
                position: absolute;
                width: 100%;
                text-align: center;
                top: 0;
                bottom: 0;
                left: 0;
                z-index: 9;
            }

            .mwembed .overlay-holder .overlay {
                width: 100%;
                background: #fff;
                opacity: 0.5;
                display: table;
                height: 100%;
            }

            .mwembed .overlay-holder .button-holder {
                position: absolute;
                display: table;
                height: 100%;
                width: 100%;
                z-index: 10;
            }

            .mwembed .overlay-holder .button-holder .button {
                display: table-cell;
                vertical-align: middle;
                text-align: center;
            }

            .mwembed .overlay-holder .button-holder .button button {
                width: 50px;
                height: 50px;
                font-size: 25px;
                border: 1px solid #000;
                -webkit-border-radius: 50px;
                -moz-border-radius: 50px;
                border-radius: 50px;
                background: #fff;
                text-align: center;
            }

            .mwembed .overlay-holder .button-holder .button button i {
                padding-left: 5px;
            }
        </style>
        <div class="mwembed">
            <div class="overlay-holder">
                <script>
                    $(document).ready(function () {
                        $('.play-video', '#<?php print $params['id']; ?>').on('click', function () {
                            $('video', '#<?php print $params['id']; ?>').get(0).play();
                            $('.overlay-holder', '#<?php print $params['id']; ?>').hide();
                        });
                    });
                </script>
                <div class="button-holder">
                    <div class="button">
                        <button class="play-video"><i class="fa fa-play"></i></button>
                    </div>
                </div>
                <div class="overlay"></div>

            </div>
            <video controls width="<?php print $w; ?>" height="<?php print $h; ?>" <?php print $autoplay; ?> src="<?php print $upload; ?>"></video>
        </div>
        <?php
    } else {
        print ("<div class='video-module-default-view mw-open-module-settings'><img src='" . $config['url_to_module'] . "video.png' /></div>");

    }
} else {
    print "<div class='video-module-default-view mw-open-module-settings'><img src='" . $config['url_to_module'] . "video.png' /></div>";
}
?>