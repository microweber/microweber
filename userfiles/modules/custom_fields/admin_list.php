<script>
if(typeof __smart_field_opener !== 'function'){
      __smart_field_opener = function(e){
		if(e === undefined){
		return;	
		}
        if(mw.tools.hasClass(e.target.className, 'mw-ui-field') || mw.tools.hasClass(e.target.className, 'mw-custom-fields-from-page-title-text')){
            mw.tools.toggle('.custom_fields_selector', '#smart_field_opener');
        }

      }
}
</script>

<?php

  $for = 'module';
 if(isset($params['for'])){
	$for = $params['for'];
 }

 $live_edit = false;
  if(isset($params['live_edit'])){
	$live_edit = $params['live_edit'];
 }

  if(isset($params['rel_id'])){
	 $params['for_module_id'] = $params['rel_id'];
 }

   if(!isset($params['for_module_id'])){
	 if(isset($params['id'])){
		 $params['for_module_id'] = $params['id'];
	 }


	 if(isset($params['data-id'])){
		 $params['for_module_id'] = $params['data-id'];
	 }
 }


 $diff = false;

  if(isset($params['save_to_content_id'])){
		 //
			 $diff = get_custom_fields($for,$params['save_to_content_id'],1,false,false);

		  }

// d($params );
?>
<?php
$data = array();
 if(isset($params['for_module_id'])): ?>
<?php
if(isset($params['default-fields'])){
	mw()->fields_manager->make_default($for,$params['for_module_id'],$params['default-fields']);
}

	$more = get_custom_fields($for,$params['for_module_id'],1,false,false);

 
 $custom_names_for_content = array();
if(is_array( $diff) and is_array($more) ){
 $i=0;
	 foreach($more as $item2){

	 foreach($diff as $item1){
			  if(isset($item1['copy_of_field'])){
				  if($item1['copy_of_field'] == $item2['id']){
				//print $item1['copy_of_field'];
				 unset($more[$i]);
				  }
			  }

		  }
		 $i++;
	 }


}
 
?>
 
<?php if(!empty( $more)):  ?>

<div class="mw-ui-field mw-tag-selector mw-custom-fields-tags" onclick="__smart_field_opener(event)">
  <?php if(isset($params['save_to_content_id']) and isset($params["rel_id"]) and intval(($params["rel_id"]) > 0)): ?>
  <?php $p = get_content_by_id($params["rel_id"]); ?>
  <?php if(isset($p['title'])): ?>
  <div class="mw-custom-fields-from-page-title"> <span class="mw-custom-fields-from-page-title-text">
    <?php _e("From"); ?>
    <strong><?php print $p['title'] ?></strong></span> </div>
  <?php endif; ?>
  <?php endif; ?>
  <?php foreach( $more as $field): ?>

  <?php if(isset($params['save_to_content_id'])): ?>
  <a class="mw-ui-btn mw-ui-btn-small mw-field-type-<?php print $field['type']; ?>" href="javascript:;"
    onmouseup="mw.custom_fields.copy_field_by_id('<?php print $field['id'] ?>', 'content', '<?php print intval($params['save_to_content_id']); ?>');"><span class="ico ico-<?php print $field['type']; ?>"></span><?php print ($field['title']); ?>
  </a>
  <?php else: ?>

  <a class="mw-ui-btn mw-ui-btn-small mw-field-type-<?php print $field['type']; ?>" href="javascript:;"
    data-id="<?php print $field['id'] ?>"
    id="custom-field-<?php print $field['id'] ?>"
    onmouseup="mw.custom_fields.edit('.mw-admin-custom-field-edit-item','<?php print $field['id'] ?>', false, event);">


    <span class="ico ico-<?php print $field['type'] ?>"></span>
    <span onclick="mw.admin.custom_fields.del(<?php print $field['id'] ?>, this.parentNode);" class="mw-icon-close"></span>

    <?php print ($field['title']); ?>


  </a>

  <?php endif; ?>
  <?php endforeach; ?>
</div>
<?php else : ?>
<?php if(!isset($params['save_to_content_id'])): ?>
<div class="mw-ui-field mw-tag-selector mw-custom-fields-tags" onclick="__smart_field_opener(event)">
  <div class="mw-custom-fields-from-page-title"> <span class="mw-custom-fields-from-page-title-text">
    <?php _e("You dont have any custom fields"); ?>.
    </span> </div>
</div>
<?php endif; ?>
<?php endif; ?>
<?php endif; ?>
