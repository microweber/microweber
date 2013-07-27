


<?php


$prior = mw('option')->get('prior', $params['id']);

$code = mw('option')->get('embed_url', $params['id']);

$upload =  mw('option')->get('upload', $params['id']);


$w = mw('option')->get('width', $params['id']);
$h = mw('option')->get('height', $params['id']);
$autoplay = mw('option')->get('autoplay', $params['id']) == 'y';


if($w == '') {$w = '450';}
if($h == '') {$h = '350';}
if($autoplay == '') {$autoplay = '0';}



if($prior=='1'){




if($code !=''){

$code = html_entity_decode($code);
if(stristr($code,'<iframe') !== false){


$code = preg_replace('#\<iframe(.*?)\ssrc\=\"(.*?)\"(.*?)\>#i',
                        '<iframe$1 src="$2?wmode=transparent"$3>', $code);
}
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
      if(!isset(  $id[1])){
        return false;
      }
       return '<div class="mwembed"><iframe width="'.$w.'" height="'.$h.'" src="http://www.youtube.com/embed/'.$id[1].'?v=1&wmode=transparent&autoplay='.$autoplay.'" frameborder="0" allowfullscreen></iframe></div>';
    }
    else if(stristr($u,'youtu.be') !== false){
        $url_parse = parse_url($u);
        $url_parse = ltrim($url_parse['path'], '/');
       return '<div class="mwembed"><iframe width="'.$w.'" height="'.$h.'" src="http://www.youtube.com/embed/'.$url_parse.'?v=1&wmode=transparent&autoplay='.$autoplay.'" frameborder="0" allowfullscreen></iframe></div>';
    }
    else if(stristr($u,'vimeo.com') !== false){
        $url_parse = parse_url($u);
         if(!isset(  $url_parse['path'])){
        return false;
      }
        $url_parse = ltrim($url_parse['path'], '/');

       return '<div class="mwembed"><iframe src="http://player.vimeo.com/video/'.$url_parse.'?title=0&amp;byline=0&amp;portrait=0&amp;badge=0&amp;color=bc9b6a&wmode=transparent&autoplay='.$autoplay.'" width="'.$w.'" height="'.$h.'" frameborder="0" allowFullScreen></iframe></div>';
    }
    else if(stristr($u,'metacafe.com') !== false){
      $url_parse = parse_url($u);
      $path = ltrim($url_parse['path'], '/');
      $id = explode('/', $path);
      if(!isset(  $id[1])){
        return false;
      }
      return '<div class="mwembed"><iframe src="http://www.metacafe.com/embed/'.$id[1].'/?ap='.$autoplay.'" width="'.$w.'" height="'.$h.'"  allowFullScreen frameborder=0></iframe></div>';
    }
    else if(stristr($u,'dailymotion.com') !== false){
      $url_parse = parse_url($u);
      $path = ltrim($url_parse['path'], '/');
      $id = explode('/', $path);
      $id = explode('_', $id[1]);
      if(!isset(  $id[0])){
        return false;
      }
      return '<div class="mwembed"><iframe frameborder="0" width="'.$w.'" height="'.$h.'" src="http://www.dailymotion.com/embed/video/'.$id[0].'/?autoPlay='.$autoplay.'"></iframe></div>';
    }
}
  if(video_module_is_embed($code) == true){
      print '<div class="mwembed">' . $code + '</div>';
  }
  else{
      print video_module_url2embed($code, $w, $h, $autoplay);
  }
}

  else{
     mw('format')->lnotif("<div class='video-module-default-view mw-open-module-settings'><img src='" .$config['url_to_module'] . "video.png' /></div>");
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
       print '<div class="mwembed "><embed width="'.$w.'" height="'.$h.'" autoplay="'.$autoplay.'" wmode="transparent" src="' . $upload . '"></embed></div>';
    }
    else{


      // print mw('format')->lnotif("Upload Video or paste URL or Embed Code.");

      mw('format')->lnotif("<div class='video-module-default-view mw-open-module-settings'><img src='" .$config['url_to_module'] . "video.png' /></div>");

    }
}
else{

    mw('format')->lnotif("<div class='video-module-default-view mw-open-module-settings'><img src='" .$config['url_to_module'] . "video.png' /></div>");

  //print mw('format')->lnotif("Upload Video or paste URL or Embed Code.");
}

