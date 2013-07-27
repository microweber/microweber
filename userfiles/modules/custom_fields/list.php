<script>

    if(typeof __smart_field_opener !== 'function'){
          __smart_field_opener = function(e){
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
			 $diff = get_custom_fields($for,$params['save_to_content_id'],1,false,false, false, true);

		  }
		  
		  
		  


?>
<?php
$data = array();
 if(isset($params['for_module_id'])): ?>
<?php
if(isset($params['default-fields'])){
	make_default_custom_fields($for,$params['for_module_id'],$params['default-fields']);
}

	$more = get_custom_fields($for,$params['for_module_id'],1,false,false);
 if(isset($params['save_to_content_id']) and intval($params['save_to_content_id'] )== 0){
	  // 	$more = get_custom_fields($for,$params['for_module_id'],1,false,false, false, true);

		   }

 // d($diff);
 $custom_custom_field_names_for_content = array();
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

// foreach($diff as $item1){
//	 $i=0;
//	 $custom_custom_field_names_for_content[] = $item1['custom_field_name'];
//	  foreach($more as $item2){
//
//	 if(in_array($item2['custom_field_name'], $custom_custom_field_names_for_content) or intval($item2['copy_of_field']) == intval($item1['id']) or $item1['custom_field_name'] == $item2['custom_field_name']){
////d($item2['custom_field_name']);
//		 unset($more[$i]);
//	 } else {
//		$data[] =  $item2;
//	 }
//	 //	 $custom_custom_field_names_for_content[] = $item2['custom_field_name'];
//
//	 $i++;
// 		}
// }
}
 if(!empty($data)){
	//$more = $data;
 }
?>
<?php if(!empty( $more)):  ?>
 <div class="mw-ui-field mw-tag-selector mw-custom-fields-tags" onclick="__smart_field_opener(event)">
  <?php if(isset($params['save_to_content_id']) and isset($params["rel_id"]) and intval(($params["rel_id"]) > 0)): ?>
  <?php $p = mw('content')->get_by_id($params["rel_id"]); ?>
  <?php if(isset($p['title'])): ?>
  <div class="mw-custom-fields-from-page-title"> <span class="mw-custom-fields-from-page-title-text">
    <?php _e("From"); ?>
    <strong><?php print $p['title'] ?></strong></span> </div>
  <?php endif; ?>
  <?php endif; ?>
  <?php foreach( $more as $field): ?>
  <?php if(isset($params['save_to_content_id'])): ?>
  <a class="mw-ui-btn mw-ui-btn-small mw-field-type-<?php print $field['custom_field_type']; ?>" href="javascript:;"
    onmouseup="mw.custom_fields.copy_field_by_id('<?php print $field['id'] ?>', 'content', '<?php print intval($params['save_to_content_id']); ?>');"><span class="ico ico-<?php print $field['custom_field_type']; ?>"></span><?php print ($field['title']); ?> </a>
  <?php else: ?>
  <a class="mw-ui-btn mw-ui-btn-small mw-field-type-<?php print $field['custom_field_type']; ?>" href="javascript:;"
    data-id="<?php print $field['id'] ?>"
    id="custom-field-<?php print $field['id'] ?>"
    onmouseup="mw.custom_fields.edit('.mw-admin-custom-field-edit-item','<?php print $field['id'] ?>', false, event);"> <span class="ico ico-<?php print $field['custom_field_type'] ?>"></span> <span onclick="mw.custom_fields.del(<?php print $field['id'] ?>, this.parentNode);" class="mw-ui-btnclose"></span> <?php print ($field['title']); ?> </a>
  <?php endif; ?>
  <?php endforeach; ?>
</div>
<?php else : ?>
<?php if(!isset($params['save_to_content_id'])): ?>
<div class="mw-ui-field mw-tag-selector mw-custom-fields-tags" onclick="__smart_field_opener(event)">
  <div class="mw-custom-fields-from-page-title"> <span class="mw-custom-fields-from-page-title-text">
    <?php _e("You dont have any custom fields"); ?>
    . </span> </div>
</div>
<?php endif; ?>
<?php endif; ?>
<?php endif; ?>
