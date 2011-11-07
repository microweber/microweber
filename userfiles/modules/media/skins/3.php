<!--<link rel="stylesheet" type="text/css" href="<? print $skins_url;  ?>3/jquery.ad-gallery.css">-->
<? $rr= rand();

$gal_data_json = array();
?>



 <?php $i = 1; if(!empty($media)): ?>
  <?php foreach($media1 as $pic): ?>
  <?php $thumb =  CI::model ( 'core' )->mediaGetThumbnailForMediaId($pic['id'], 800);
	 $orig =  CI::model ( 'core' )->mediaGetThumbnailForMediaId($pic['id'], 'original');
 
 $gal_data_json1=array();
 $gal_data_json1['thumb'] = $thumb;
 $gal_data_json1['image'] = $orig;
 $gal_data_json1['big'] = $big;
 $gal_data_json1['title'] = addslashes($pic['media_name']);
  $gal_data_json1['description'] = addslashes($pic['media_description']);
  
  
  
 $gal_data_json[] = $gal_data_json1;
 
 
 
?>
 
  <?php $i++; endforeach; ?>
  <?php endif; ?>




<?php $h =  option_get('galeria_skin_height', $params['module_id']); ?>
<?php $w =  option_get('galeria_skin_width', $params['module_id']) ;

if(intval($h) == 0){
	$h = 500;
}

if(intval($w) == 0){
	$w = 500;
}
?>



<link rel="stylesheet" href="<? print $skins_url;  ?>3/themes/classic/galleria.classic.css">

<script type="text/javascript" src="<? print $skins_url;  ?>3/galleria-1.2.4.js"></script>
<script src="<? print $skins_url;  ?>3/themes/classic/galleria.classic.js"></script> 
<script type="text/javascript">

//Galleria.loadTheme('<? print $skins_url;  ?>3/themes/classic/galleria.classic.js');
  $(function() {
   

 
 
 var gallery_data<? print $params['module_id'] ?> = eval('<? print json_encode($gal_data_json);  ?>')
 
 
 
 
 
 
 
 
 
 
 $('#gallery<? print $params['module_id'] ?><? print $rr ?>').galleria({
        imageCrop: true,
		width:<? print intval($w); ?>,
		dataSource: gallery_data<? print $params['module_id'] ?>,
        height:<? print intval($h); ?>, 
		 transition: 'fade'
    });
   
	
 
 
 
  });
  </script>
<div id="gallery<? print $params['module_id'] ?><? print $rr ?>">

</div>
