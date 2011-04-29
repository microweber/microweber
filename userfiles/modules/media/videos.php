<script>
   
    
    </script>
<?
  $id = $params['content_id'];
  $for = $params['for'];
  
  $size = $params['size'];
  
  $queue_id = $params['queue_id'];
  
  
   
  $media_type = $params['type'];
  if($media_type == false){
	  
	$media_type = 'video';  
  }
  
   if($size == false){
	  $size = 250;

  }

  ?>
<?  $media1 = get_media($id, $for, $media_type,  $queue_id);
 	 $media1 = $media1['videos'];


?>
<?php if(empty($media1)  ): ?>
Video gallery is empty
<?php else : ?>

<script>
$(document).ready(function(){
    $("#video_gal a.v_gal_item").click(function(){

        var embed = $(this).parent().find(".embed_code:first").val();
        Modal.box(embed, 640, 380);
        Modal.overlay();
        $("#modalbox embed, #modalbox object").css({
           width:640,
           height:360
        });

        return false;
    });
});
</script>
<div class="slide_engine">
  <ul class="media_module_content" id="video_gal">
    <?php $i = 1; if(!empty($media1)): ?>
    <?php foreach($media1 as $pic): ?>
    <?php $thumb =  CI::model ( 'core' )->mediaGetThumbnailForMediaId($pic['id'], $size);
	 $orig =  CI::model ( 'core' )->mediaGetThumbnailForMediaId($pic['id'], 'original');
 if($pic['url']){
	$url1 = $pic['url']; 
 }
 
 if($pic['original_link']){
	$url1 = $pic['original_link']; 
 }
?>
    <li id="picture_id_<?php print $pic['id'] ?>">
      <?  if($pic['embed_code']) :?>
      <a href="#" class="v_gal_item">
        <span style="background-image: url(<? print  $thumb; ?>)"></span>
        <strong><?php print addslashes($pic['media_name']); ?></strong>
      </a>
      <textarea style="display: none" class="embed_code">
        <?php print html_entity_decode($pic['embed_code']) ?>
      </textarea>
      <?php else : ?>
      <img src="<? print  $thumb; ?>" />
      <?php endif; ?>
      <a href="<?php print $url1; ?>" target="_blank"  title="<?php print addslashes($pic['media_name']); ?>"  alt="<?php print addslashes($pic['media_description']); ?>" > <strong>Name of this video here</strong> </a></li>
    <?php $i++; endforeach; ?>
    <?php endif; ?>
  </ul>
</div>
<?php endif; ?>
