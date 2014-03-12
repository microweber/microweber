<?php


function video_module_url2embed($u, $w, $h, $autoplay)
{


    $protocol = "http://";
    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'
        || $_SERVER['SERVER_PORT'] == 443
    ) {

        $secure_connection = true;
        $protocol = "https://";
    }


    if (stristr($u, 'youtube.com') !== false) {
        $p = parse_url($u);
        $id = explode('v=', $p['query']);
        if (!isset($id[1])) {
            if (isset($id[0])) {
                $id[1] = $id[0];
            } else {
                return false;
            }
        }
        return '<div class="mwembed"><iframe width="' . $w . '" height="' . $h . '" src="' . $protocol . 'www.youtube.com/embed/' . $id[1] . '?v=1&wmode=transparent&autoplay=' . $autoplay . '" frameborder="0" allowfullscreen></iframe></div>';
    } else if (stristr($u, 'youtu.be') !== false) {
        $url_parse = parse_url($u);
        $url_parse = ltrim($url_parse['path'], '/');
        return '<div class="mwembed"><iframe width="' . $w . '" height="' . $h . '" src="' . $protocol . 'www.youtube.com/embed/' . $url_parse . '?v=1&wmode=transparent&autoplay=' . $autoplay . '" frameborder="0" allowfullscreen></iframe></div>';
    } else if (stristr($u, 'vimeo.com') !== false) {
        $url_parse = parse_url($u);
        if (!isset($url_parse['path'])) {
            return false;
        }
        $url_parse = ltrim($url_parse['path'], '/');

        return '<div class="mwembed"><iframe src="' . $protocol . 'player.vimeo.com/video/' . $url_parse . '?title=0&amp;byline=0&amp;portrait=0&amp;badge=0&amp;color=bc9b6a&wmode=transparent&autoplay=' . $autoplay . '" width="' . $w . '" height="' . $h . '" frameborder="0" allowFullScreen></iframe></div>';
    } else if (stristr($u, 'metacafe.com') !== false) {
        $url_parse = parse_url($u);
        $path = ltrim($url_parse['path'], '/');
        $id = explode('/', $path);
        if (!isset($id[1])) {
            return false;
        }
        return '<div class="mwembed"><iframe src="' . $protocol . 'www.metacafe.com/embed/' . $id[1] . '/?ap=' . $autoplay . '" width="' . $w . '" height="' . $h . '"  allowFullScreen frameborder=0></iframe></div>';
    } else if (stristr($u, 'dailymotion.com') !== false) {
        $url_parse = parse_url($u);
        $path = ltrim($url_parse['path'], '/');
        $id = explode('/', $path);
        $id = explode('_', $id[1]);
        if (!isset($id[0])) {
            return false;
        }
        return '<div class="mwembed"><iframe frameborder="0" width="' . $w . '" height="' . $h . '" src="' . $protocol . 'www.dailymotion.com/embed/video/' . $id[0] . '/?autoPlay=' . $autoplay . '"></iframe></div>';
    } else {
        return $u;
    }
}


function video_module_is_embed($str)
{
    $s = strtolower($str);
    if (stristr($s, '<iframe') != false or stristr($s, '<object') != false or stristr($s, '<embed') != false) {
        return true;
    } else {
        return false;
    }
}