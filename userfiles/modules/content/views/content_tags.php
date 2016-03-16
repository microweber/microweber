<?php
if(!function_exists('content_tags')){
return;	
}
only_admin_access();

if(!isset($params['content-id'])){
	return;
}
$tags = content_tags($params['content-id']);
$tags_str = array();
if(!empty($tags)){
	foreach($tags as $tag){
		if(isset($tag['title'])){
			$tags_str[] = $tag['title'];
		}
	}
}
 

 ?>

<div class="mw-ui-field-holder">
  <label class="mw-ui-label">
    <?php _e("Tags"); ?>
    <small class="mw-help"
               data-help="Tags/Labels for this content. Use comma (,) to add multiple tags"> (?) </small> </label>
  <input type="text" name="tags" class="mw-ui-field" value="<?php print implode(',',$tags_str); ?>" />
</div>
