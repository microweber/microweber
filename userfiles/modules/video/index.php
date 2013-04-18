


<?php


$prior = get_option('prior', $params['id']);

$code = get_option('embed_url', $params['id']);

$upload =  get_option('upload', $params['id']);


$w = get_option('width', $params['id']);
$h = get_option('height', $params['id']);
$autoplay = get_option('autoplay', $params['id']) == 'y';


if($w == '') {$w = '315';}
if($h == '') {$w = '560';}
if($autoplay == '') {$autoplay = '0';}



if($prior=='1'){




if($code !=''){




$code = html_entity_decode($code);

function video_module_is_embed($str){
     $s = strtolower($str);
     if(stristr($s,'<iframe') != false or stristr($s,'<object') != false or stristr($s,'<embed') != false){
       return true;
     }
     else{return false;}
}
function video_module_url2embed($u, $w, $h, $autoplay){
    if(stristr($u,'youtube.com') !== false){
       $p = parse_url($u);
       $id = explode('v=', $p['query']);
       return '<iframe width="'.$w.'" height="'.$h.'" src="http://www.youtube.com/embed/'.$id[1].'?v=1&wmode=transparent&autoplay='.$autoplay.'" frameborder="0" allowfullscreen></iframe>';
    }
    else if(stristr($u,'youtu.be') !== false){
        $url_parse = parse_url($u);
        $url_parse = ltrim($url_parse['path'], '/');
       return '<iframe width="'.$w.'" height="'.$h.'" src="http://www.youtube.com/embed/'.$url_parse.'?v=1&wmode=transparent&autoplay='.$autoplay.'" frameborder="0" allowfullscreen></iframe>';
    }
    else if(stristr($u,'vimeo.com') !== false){
        $url_parse = parse_url($u);
        $url_parse = ltrim($url_parse['path'], '/');
       return '<iframe src="http://player.vimeo.com/video/'.$url_parse.'?title=0&amp;byline=0&amp;portrait=0&amp;badge=0&amp;color=bc9b6a&wmode=transparent&autoplay='.$autoplay.'" width="'.$w.'" height="'.$h.'" frameborder="0" allowFullScreen></iframe>';
    }
    else if(stristr($u,'metacafe.com') !== false){
      $url_parse = parse_url($u);
      $path = ltrim($url_parse['path'], '/');
      $id = explode('/', $path);
      return '<iframe src="http://www.metacafe.com/embed/'.$id[1].'/?ap='.$autoplay.'" width="'.$w.'" height="'.$h.'"  allowFullScreen frameborder=0></iframe>';
    }
    else if(stristr($u,'dailymotion.com') !== false){
      $url_parse = parse_url($u);
      $path = ltrim($url_parse['path'], '/');
      $id = explode('/', $path);
      $id = explode('_', $id[1]);
      return '<iframe frameborder="0" width="'.$w.'" height="'.$h.'" src="http://www.dailymotion.com/embed/video/'.$id[0].'/?autoPlay='.$autoplay.'"></iframe>';
    }
}

if(video_module_is_embed($code) == true){
    print $code;
}
else{
    print video_module_url2embed($code, $w, $h, $autoplay);
}


}

else{

 print mw_notif("Upload Video or paste URL or Embed Code.");

}

}

else if($prior == '2'){
    if($upload!=''){
       if($autoplay=='0'){
         $autoplay = 'false';
       }
       else{
         $autoplay = 'true';
       }
       print '<embed width="'.$w.'" height="'.$h.'" autoplay="'.$autoplay.'" wmode="transparent" src="' . $upload . '"></embed>';
    }
    else{
       print mw_notif("Upload Video or paste URL or Embed Code.");
    }
}
else{
  print mw_notif("Upload Video or paste URL or Embed Code.");
}


?>

