


<?php

$code = get_option('title', $params['id']);

if($code !=''){




$code = html_entity_decode($code);

function video_module_is_embed($str){
     $s = strtolower($str);
     if(stristr($s,'<iframe') != false or stristr($s,'<object') != false or stristr($s,'<embed') != false){
       return true;
     }
     else{return false;}
}
function video_module_url2embed($u){
    if(stristr($u,'youtube.com') !== false){
       $p = parse_url($u);
       $id = explode('v=', $p['query']);
       return '<div class="element mw-embed-iframe" ><iframe width="560" height="315" src="http://www.youtube.com/embed/'.$id[1].'?v=1&wmode=transparent" frameborder="0" allowfullscreen></iframe></div>';
    }
    else if(stristr($u,'youtu.be') !== false){
        $url_parse = parse_url($u);
        $url_parse = ltrim($url_parse['path'], '/');
       return '<div class="element mw-embed-iframe" ><iframe width="560" height="315" src="http://www.youtube.com/embed/'.$url_parse.'?v=1&wmode=transparent" frameborder="0" allowfullscreen></iframe></div>';
    }
    else if(stristr($u,'vimeo.com') !== false){
        $url_parse = parse_url($u);
        $url_parse = ltrim($url_parse['path'], '/');
       return '<div class="element mw-embed-iframe"><iframe src="http://player.vimeo.com/video/'.$url_parse.'?title=0&amp;byline=0&amp;portrait=0&amp;badge=0&amp;color=bc9b6a&wmode=transparent" width="560" height="315" frameborder="0" allowFullScreen></iframe></div>';
    }
    else if(stristr($u,'metacafe.com') !== false){
      $url_parse = parse_url($u);
      $path = ltrim($url_parse['path'], '/');
      $id = explode('/', $path);
      return '<div class="element mw-embed-iframe" ><iframe src="http://www.metacafe.com/embed/'.$id[1].'/" width="315px" height="560" style="height:315px;width:560px;" allowFullScreen frameborder=0></iframe></div>';
    }
    else if(stristr($u,'dailymotion.com') !== false){
      $url_parse = parse_url($u);
      $path = ltrim($url_parse['path'], '/');
      $id = explode('/', $path);
      $id = explode('_', $id[1]);
      return '<div class="element mw-embed-iframe"><iframe frameborder="0" width="315px" height="560" src="http://www.dailymotion.com/embed/video/'.$id[0].'"></iframe></div>';
    }
}

if(video_module_is_embed($code) == true){
    print $code;
}
else{
    print video_module_url2embed($code);
}


}

else{ ?>
<div class="mw-notification mw-warning">
  <div>Add video URL or Embed Code</div>
</div>
<?php }  ?>

