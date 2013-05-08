<?php
 
$params['global'] = 1;
$params['return'] = 1;
$config['module'] = 'posts';
$set_content_type = 'post';
   $set_content_type_from_opt =  get_option('data-content-type', $params['parent-module-id']);
   if( $set_content_type_from_opt != false and  $set_content_type_from_opt != ''){
	 $set_content_type  = $set_content_type_from_opt;
	 $params['content_type'] = $set_content_type;
   }
 
  
include_once($config['path_to_module'].'../posts/index.php');

?> 
 
<div class="manage-posts-holder">
  <?php if(isarr($data)): ?>
  <?php foreach ($data as $item): ?>

  <?php
  $pub_class = '';
				if(isset($item['is_active']) and $item['is_active'] == 'n'){
					$pub_class = ' content-unpublished';
				}

   ?>


  <div class="manage-post-item manage-post-item-<?php print ($item['id']) ?> <?php print $pub_class ?>">
    <div class="manage-post-itemleft">
      
      <?php
    	$pic  = get_picture(  $item['id']);


		 ?>
      <?php if($pic == true ): ?>
      <a class="manage-post-image left" style="background-image: url('<?php print thumbnail($pic, 108) ?>');"  onClick="mw.url.windowHashParam('action','editpost:<?php print ($item['id']) ?>');return false;"></a>
      <?php else : ?>
      <a class="manage-post-image manage-post-image-no-image left"  onClick="mw.url.windowHashParam('action','editpost:<?php print ($item['id']) ?>');return false;"></a>
      <?php endif; ?>

      <?php $edit_link = admin_url('view:content#action=editpost:'.$item['id']);  ?>

      <div class="manage-post-main">
        <h3 class="manage-post-item-title"><a target="_top" href="<?php print $edit_link ?>" onClick="mw.url.windowHashParam('action','editpost:<?php print ($item['id']) ?>');return false;"><?php print strip_tags($item['title']) ?></a></h3>
        <small><a  class="manage-post-item-link-small" target="_top"  href="<?php print content_link($item['id']); ?>/editmode:y" onClick="mw.edit_content_live_edit('<?php print ($item['id']) ?>');"><?php print content_link($item['id']); ?></a></small>
        <div class="manage-post-item-description"> <?php print character_limiter(strip_tags($item['description']), 60);
      ?> </div>
        <div class="manage-post-item-links"> <a href="javascript:mw.edit_content_live_edit('<?php print ($item['id']) ?>');">Quick Edit</a>  <a target="_top"  href="<?php print content_link($item['id']); ?>/editmode:y">Live edit</a>   </div>
      </div>
      <div class="manage-post-item-author" title="<?php print user_name($item['created_by']); ?>"><?php print user_name($item['created_by'],'username') ?></div>
    </div>
 


  </div>
  <?php endforeach; ?>
     <?php endif; ?>
</div>