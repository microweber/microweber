<script>
     $(document).ready(function(){
       $("#image_gal a").modal("single")
     });
    
    </script>
<?
  $id = $params['content_id'];
  $for = $params['for'];
  
  $size = $params['size'];
  
  $queue_id = $params['queue_id'];
  
  
   
  $media_type = $params['type'];
  if($media_type == false){
	  
	$media_type = 'picture';  
  }
  
   if($size == false){
	  $size = 120;
 
  }

  ?>
<?  $media1 = get_media($id, $for, $media_type,  $queue_id);
 	 $media1 = $media1['pictures'];


?>
<?php if(empty($media1)  ): ?>

Gallery is empty
<?php else : ?>
<div class="slide_engine">
  <ul class="media_module_content" id="image_gal">
    <?php $i = 1; if(!empty($media1)): ?>
    <?php foreach($media1 as $pic): ?>
    <?php $thumb =  CI::model ( 'core' )->mediaGetThumbnailForMediaId($pic['id'], $size);
	 $orig =  CI::model ( 'core' )->mediaGetThumbnailForMediaId($pic['id'], 'original');
//p($thumb);
?>
    <li id="picture_id_<?php print $pic['id'] ?>"> <a href="<? print  $orig; ?>"  title="<?php print addslashes($pic['media_name']); ?>"  alt="<?php print addslashes($pic['media_description']); ?>"    style="background-image: url('<? print  $thumb; ?>')"> </a></li>
    <?php $i++; endforeach; ?>
    <?php endif; ?>
  </ul>
</div>
<?php endif; ?>
