<?php

 
return include('manager.php');


 
//$params['global'] = 1;
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
<script  type="text/javascript">
 mw.manage_content_quick_sort = function(){
  if(!mw.$("#mw-quick-sort-posts").hasClass("ui-sortable")){
   mw.$("#mw-quick-sort-posts").sortable({
     items: '.manage-post-item',
     axis:'y',
     handle:'.mw_admin_posts_sortable_handle',
     update:function(){
       var obj = {ids:[]}
       $(this).find('.select_posts_for_action').each(function(){
        var id = this.attributes['data-content-id'].nodeValue;
        obj.ids.push(id);
      });

       $.post("<?php print api_link('content/reorder'); ?>", obj, function(){
		   
		   mw.reload_module_parent('posts')
		   mw.reload_module_parent('shop/products')
		   mw.reload_module_parent('content')
		   });
     },
     start:function(a,ui){
      $(this).height($(this).outerHeight());
      $(ui.placeholder).height($(ui.item).outerHeight())
      $(ui.placeholder).width($(ui.item).outerWidth())
    },

       //placeholder: "custom-field-main-table-placeholder",
       scroll:false


     });

 }
}
</script>
<div class="manage-posts-holder" id="mw-quick-sort-posts">
  <?php if(is_array($data)): ?>
  <?php foreach ($data as $item): ?>

  <?php
  $pub_class = '';
				if(isset($item['is_active']) and $item['is_active'] == 'n'){
					$pub_class = ' content-unpublished';
				}

   ?>


  <div data-content-id="<?php print ($item['id']) ?>" class="select_posts_for_action manage-post-item manage-post-item-<?php print ($item['id']) ?> <?php print $pub_class ?>">
    <div class="manage-post-itemleft">
            <span class="ico iMove mw_admin_posts_sortable_handle left" onmousedown="mw.manage_content_quick_sort()"></span>

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
        <div class="manage-post-item-links"> <a href="javascript:mw.edit_content_live_edit('<?php print ($item['id']) ?>');"><?php _e("Quick Edit"); ?></a>  <a target="_top"  href="<?php print content_link($item['id']); ?>/editmode:y"><?php _e("Live edit"); ?></a>  
		
		
		
		 <a href="javascript:mw.delete_content_live_edit('<?php print ($item['id']) ?>');"><?php _e("Delete"); ?></a>
		
		
		
		 </div>
      </div>
      <div class="manage-post-item-author" title="<?php print user_name($item['created_by']); ?>"><?php print user_name($item['created_by'],'username') ?></div>
    </div>
 


  </div>
  <?php endforeach; ?>
     <?php endif; ?>
</div>