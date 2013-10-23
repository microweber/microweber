<?php


 

$for = $config['module'];
$for_module_id = $params['id'];

 $use_from_post = get_option('data-use-from-post', $params['id']) =='y';
 	  $use_from_post_forced = false;
 
 if(isset($params['rel'])){
	 if((trim($params['rel']) == 'page') or(trim($params['rel']) == 'content') or ((trim($params['rel']) == 'post') or trim($params['rel']) == 'post') or trim($params['rel']) == 'post'){
	  $use_from_post = true;
	  $use_from_post_forced = 1;
		unset($params['rel']);
	 }
 }
 
if( $use_from_post){
 

 
 if(POST_ID != false){
	$params['content-id'] = POST_ID;
 }
 else {
	$params['content-id'] = PAGE_ID;
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
 
<div class="mw_simple_tabs mw_tabs_layout_simple">

 <?php if($quick_add == false): ?>
  <ul class="mw_simple_tabs_nav">
    <li><a href="javascript:;" class="active"><?php _e("My pictures"); ?></a></li>
    <li><a href="javascript:;"><?php _e("Skin/Template"); ?></a></li>
  </ul>
   <?php endif; ?>
  <div class="tab">

 <?php if($quick_add == false and $use_from_post_forced == false): ?>
  <label class="mw-ui-check">
      <input type="checkbox" name="data-use-from-post" value="y" class="mw_option_field" <?php if( $use_from_post): ?>   checked="checked"  <?php endif; ?> data-also-reload="<?php print $config['the_module'] ?>" />
      <span></span><span><?php _e("Use pictures from post"); ?></span></label>
     <?php endif; ?>
     <module type="pictures/admin_backend" for="<?php print $for ?>" for-id="<?php print $for_id ?>" />
  </div>
  <?php if($quick_add == false): ?>
  <div class="tab"> <strong><?php _e("Skin/Template"); ?></strong>
    <module type="admin/modules/templates"  />
    <module type="settings/list"  for_module="<?php print $config['module'] ?>" for_module_id="<?php print $params['id'] ?>" >
  </div>
   <?php endif; ?>
</div>
<div class="mw_clear"></div>
<div class="vSpace"></div>
<div class="vSpace"></div>
