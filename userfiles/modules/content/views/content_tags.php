<?php
if(!function_exists('content_tags')){
return;	
}
only_admin_access();

if(!isset($params['content-id'])){
	return;
}
$tags_str = content_tags($params['content-id']);

 if(!$tags_str){
	 $tags_str = array();
 }

 ?>

<div class="mw-ui-field-holder">
  <label class="mw-ui-label">
    <?php _e("Tags"); ?>
    <small class="mw-help"
               data-help="<?php _e('Tags/Labels for this content. Use comma (,) to add multiple tags'); ?>"> (?) </small> </label>
  <input type="text" name="tags" class="mw-ui-field" value="<?php print implode(',',$tags_str); ?>" />
</div>
