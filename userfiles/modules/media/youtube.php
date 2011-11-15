<?php $h =  option_get('vid_h', $params['module_id']); ?>
<?php $w =  option_get('vid_w', $params['module_id']) ;


 

if(intval($h) == 0){
	$h = 390;
}

 
 
 

if(intval($w) == 0){
	$w = 480;
}
?>

<div>
<? $embed_code =  option_get('embed_code', $params['module_id'])   ?>
<? if(trim($embed_code) == ''): ?>
<img src="  <? print $config['url_to_module'] ?>img/default_vid.png" title="Video is empty" align="left" />  
<? else :  ?>
<? $embed_code = explode('v=', $embed_code); ?>

<iframe width="<?  print $w ?>" height="<?  print $h ?>" src="http://www.youtube.com/embed/<? print $embed_code[1] ?>" frameborder="0" allowfullscreen></iframe>


 
<? endif; ?>
</div>
