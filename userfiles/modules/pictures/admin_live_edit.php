<?php


$the_content_id = false;

if(defined('POST_ID') and  POST_ID != false){
	$the_content_id =  POST_ID;
 } else if(defined('PAGE_ID') and  PAGE_ID != false){
 
	$the_content_id = PAGE_ID;
 }

$for = $mod_name = $config['module'];
$for_module_id = $mod_id = $params['id'];


if (isset($params['rel_type']) and trim(strtolower(($params['rel_type']))) == 'post' and defined('POST_ID')) {
    $params['rel_id'] = POST_ID;
    $params['for'] = 'content';
}
if (isset($params['rel_type']) and trim(strtolower(($params['rel_type']))) == 'page' and defined('PAGE_ID')) {
    $params['rel_id'] = PAGE_ID;
    $params['for'] = 'content';
}
if (isset($params['rel_type']) and trim(strtolower(($params['rel_type']))) == 'content' and defined('CONTENT_ID')) {
    $params['rel_id'] = CONTENT_ID;
    $params['for'] = 'content';
}
 

$use_from_post = get_option('data-use-from-post', $params['id']) =='y';
$use_from_post_forced = false;
 
 if(isset($params['rel_type'])){
	 if((trim($params['rel_type']) == 'page') or(trim($params['rel_type']) == 'content') or ((trim($params['rel_type']) == 'post') or trim($params['rel_type']) == 'post') or trim($params['rel_type']) == 'post'){
	  $use_from_post = true;
	  $use_from_post_forced = 1;
		unset($params['rel_type']);
	 }
 }
if(isset($params['content-id']) and $params['content-id'] != 0){
	 $use_from_post = true;
	  $use_from_post_forced = 1;
	
}else if( $use_from_post == true){
	 if(POST_ID != false){
		$params['content-id'] = POST_ID;
	 }
	 else {
		$params['content-id'] = intval(PAGE_ID);
	 }
}
 
if(isset($params['content-id'])){
	$for_module_id = $for_id = $params['content-id'];
	 $for = 'content';
}
else {
  $for_module_id = $for_id = $params['id'];
  $for = 'modules';
}
if(!isset($quick_add)){
    $quick_add=false;
}
if(isset($params['quick-add'])){
    $quick_add=$params['quick-add'];
} 




  ?>
  
  
 
  
 <script  type="text/javascript">
 mw.require('options.js');
 </script>
<script  type="text/javascript">


__mw_pics_save_msg = function(){
	
	
	 
	
	
	 if(mw.notification != undefined){
			 mw.notification.success('Picture settings are saved!');
	 }
	 
	 var pics_from_post = $('#mw-use-post-pics:checked').val();
	 if(pics_from_post != undefined && pics_from_post == 'y'){
		 $("#mw-pics-list-live-ed").attr('for', 'content');
		 $("#mw-pics-list-live-ed").attr('for-id', '<?php print $the_content_id ?>');

	 } else {
		 $("#mw-pics-list-live-ed").attr('for', 'modules');
		 $("#mw-pics-list-live-ed").attr('for-id', '<?php print $mod_id ?>');
	 }
	  mw.reload_module_parent("pictures");
	 //  mw.reload_module_parent("#<?php print $params['id'] ?>");
	 
										
										
										
	 mw.reload_module("#mw-pics-list-live-ed");
	 
	 
	 
	
}

$(document).ready(function(){
    mw.options.form('#mw-pic-scope', __mw_pics_save_msg);
    mw.tabs({
       tabs:'.tab',
       nav:'.mw-ui-btn-nav a'
    });
});
 </script>
<div class="mw-ui-box-content">
<style scoped="scoped">

.tab{
  display: none;
}

</style>

 <?php if($quick_add == false): ?>
  <div class="mw-ui-btn-nav mw-ui-btn-nav-tabs">
    <a href="javascript:;" class="mw-ui-btn active"><?php _e("My pictures"); ?></a>
    <a href="javascript:;" class="mw-ui-btn"><?php _e("Skin/Template"); ?></a>
  </div>
   <?php endif; ?>
  <div class="tab mw-ui-box mw-ui-box-content" style="display: block">
 
 <?php if($quick_add == false and $use_from_post_forced == false): ?>
  <label class="mw-ui-check" id="mw-pic-scope">
      <input type="checkbox" reload="#mw-pics-list-live-ed" id="mw-use-post-pics" name="data-use-from-post" value="y" class="mw_option_field" <?php if( $use_from_post): ?>   checked="checked"  <?php endif; ?> />
      <span></span><span><?php _e("Use pictures from post"); ?></span></label>
     <?php endif; ?>
     <module type="pictures/admin_backend" for="<?php print $for ?>" for-id="<?php print $for_id ?>" id="mw-pics-list-live-ed" />
  </div>
  <?php if($quick_add == false): ?>
  <div class="tab mw-ui-box mw-ui-box-content">
    <module type="admin/modules/templates"  />
    <module type="settings/list"  for_module="<?php print $config['module'] ?>" for_module_id="<?php print $params['id'] ?>" >
  </div>
   <?php endif; ?>
</div>



