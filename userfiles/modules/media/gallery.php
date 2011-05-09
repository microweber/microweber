 
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
  
<?php print option_get('media_name', $params['module_id']) ?>
<?
//p($config);
  $skin = option_get('skin', $params['module_id']);  
 
 
$media1 = CI::model ( 'core' )->mediaGet($to_table = false, $to_table_id = false, $media_type = false, $order = "ASC", $queue_id = false, $no_cache = false, $id = false, $collection = $params['module_id']);
 
//$media1 = get_media($id, $for, $media_type,  $queue_id);
 	 $media1 = $media1['pictures'];
$media = $media1;

?>


<?php if(empty($media1)  ): ?>

Gallery is empty
<?php else : ?>
 
 
 <? if(trim($skin) =='' or $skin == '1'): ?>
 
   
    <?php $i = 1; if(!empty($media1)): ?>
    <?php foreach($media1 as $pic): ?>
    <?php $thumb =  CI::model ( 'core' )->mediaGetThumbnailForMediaId($pic['id'], $size);
	 $orig =  CI::model ( 'core' )->mediaGetThumbnailForMediaId($pic['id'], 'original');
//p($thumb);
?>
    <a href="<? print  $orig; ?>"  title="<?php print addslashes($pic['media_name']); ?>"  alt="<?php print addslashes($pic['media_description']); ?>" > <img src="<? print  $thumb; ?>" /></a> 
    <?php $i++; endforeach; ?>
    <?php endif; ?>
    
    
    <?php else : ?>
    
    <?
	
	
	$skin_file = dirname(__FILE__).'/skins/'.$skin.'.php';
	$skin_file  = normalize_path($skin_file ,0);
	
	$skins_url =dirToURL(dirname(__FILE__).'/skins/').'/';
	?>
     <?  if(is_file($skin_file)): ?>
    
    <?  include($skin_file); ?>
     <?php endif; ?>
 <?php endif; ?>
 
 
 
 

<?php endif; ?>



