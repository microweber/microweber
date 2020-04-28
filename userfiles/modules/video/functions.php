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
        if(!isset($p['query']) or $p['query'] == false){
            return false;
        }

        $id = explode('v=', $p['query']);
        parse_str($p['query'],$vars);

        if (isset($vars['v'])) {
            return '<div class="mwembed"><iframe width="' . $w . '" height="' . $h . '" src="' . $protocol . 'www.youtube.com/embed/' . $vars['v'] . '?v=1&wmode=transparent&autoplay=' . $autoplay . '" frameborder="0" allowfullscreen></iframe></div>';
        } else {
            return false;
        }
    } else if (stristr($u, 'youtu.be') !== false) {
        $url_parse = parse_url($u);
        $url_parse = ltrim($url_parse['path'], '/');
        return '<div class="mwembed"><iframe width="' . $w . '" height="' . $h . '" src="' . $protocol . 'www.youtube.com/embed/' . $url_parse . '?v=1&wmode=transparent&autoplay=' . $autoplay . '" frameborder="0" allowfullscreen></iframe></div>';
    }  elseif (stristr($u, 'facebook.com') !== false) {
        $p = parse_url($u);
        if(!isset($p['query']) or $p['query'] == false){
            return false;
        }

        $id = explode('v=', $p['query']);
        parse_str($p['query'],$vars);

        if (isset($vars['v'])) {

            return '<script>(function(d, s, id) {  var js, fjs = d.getElementsByTagName(s)[0];  if (d.getElementById(id)) return;  js = d.createElement(s); js.id = id;  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";  fjs.parentNode.insertBefore(js, fjs);}(document, \'script\', \'facebook-jssdk\'));</script><div class="fb-post" data-href="https://www.facebook.com/video.php?v='.$vars['v'].'" data-width="' . $w . '" data-height="' . $h . '"><div class="fb-xfbml-parse-ignore"></div></div>';


        } else {
            return false;
        }
    }else if (stristr($u, 'vimeo.com') !== false) {
        $url_parse = parse_url($u);
        if (!isset($url_parse['path'])) {
            return false;
        }
        $url_parse = ltrim($url_parse['path'], '/');

        return '<div class="mwembed"><div style="padding:56.25% 0 0 0;position:relative;"><iframe src="https://player.vimeo.com/video/' . $url_parse . '?title=0&byline=0&portrait=0&autoplay=' . $autoplay . '" style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe></div><script src="https://player.vimeo.com/api/player.js"></script></div>';
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
