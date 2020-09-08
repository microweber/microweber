<?php




  $layout=get_option('layout', $params['id']);
  $url_to_like=get_option('url', $params['id']);
  $color=get_option('color', $params['id']);
  $show_faces=get_option('show_faces', $params['id']);


  if($layout == false and isset($params['layout'])){
          $layout = $params['layout'];
  }
   if($layout == false and isset($params['layout'])){
          $layout = $params['layout'];
  }
   if($color == false and isset($params['color'])){
          $color = $params['color'];
  }
   if($show_faces == false and isset($params['show-faces'])){
          $show_faces = $params['show-faces'];
  }
     if($url_to_like == false and isset($params['url-to-like'])){
          $url_to_like = $params['url-to-like'];
  }
  
  if($layout == false or trim($layout) == ''){
	$layout = "standard"  ;
  } else {
	$layout = trim($layout);
  }
   if($url_to_like == false or trim($url_to_like) == ''){
	 $cur_url = url_current(true); 
	 $cur_url = urlencode($cur_url);
	 $url_to_like = $cur_url;
  } else {
	  $url_to_like = mw('format')->prep_url(trim($url_to_like));
  }
  
  if($color == false or trim($color) == ''){
	$color = "light"  ;
  }
  $show_faces_str = '';
  if($show_faces == false or trim($show_faces) != 'n'){
	$show_faces_str = '&amp;show_faces=true';
  }
  
?>

<div class='mwembed'>
  <iframe src="//www.facebook.com/plugins/like.php?href=<?php print $url_to_like; ?>&amp;send=false&amp;layout=<?php print $layout; ?>&amp;width=450<?php print $show_faces_str; ?>&amp;font&amp;colorscheme=<?php print $color; ?>&amp;action=like&amp;height=80&amp;appId=<?php print get_option('fb_app_id', 'users');?>" scrolling="no" frameborder="0" allowTransparency="true" allow="encrypted-media"></iframe>
</div>