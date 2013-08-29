


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

