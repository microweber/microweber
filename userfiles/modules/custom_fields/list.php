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
$list_preview = false;
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

if(isset($params['list-preview']) and $params['list-preview'] != 'false'){
  $list_preview = true;
}
 $diff = false;

  if(isset($params['save_to_content_id'])){
		 //
			 $diff = get_custom_fields($for,$params['save_to_content_id'],1,false,false, false, true);

		  }
		  
		  
	$suggest_from_rel = false;
 
	if(isset($params['suggest-from-related']) and $params['suggest-from-related'] != 'false'){
	  $suggest_from_rel = true;
	}
	  


?>
<?php

 
$data = array();
 if(isset($params['for_module_id'])): ?>
<?php
if(isset($params['default-fields'])){
	mw('fields')->make_default($for,$params['for_module_id'],$params['default-fields']);
}

	$more = get_custom_fields($for,$params['for_module_id'],1,false,false);

 if($suggest_from_rel == true){
	 $par =array();
	 $par['rel'] = $for;
	 $more = get_custom_fields($for,'all',1,false,false);
	 $have = array();
	  if(isset($diff) and is_array($diff)){
		$i=0;
		 foreach($diff as $item){
			if(isset($item['custom_field_name']) and in_array($item['custom_field_name'],$have)){
				 unset($diff[$i]);
			} else if(isset($diff[$i]) and isset($item['custom_field_name'])){
				$have[] = $item['custom_field_name'];
			 }
			 $i++; 
		 }
	 }
	 if(is_array($more)){
		$i=0;
		 foreach($more as $item){
			if(isset($item['custom_field_name']) and in_array($item['custom_field_name'],$have)){
				 unset($more[$i]);
			} else if(isset($more[$i]) and isset($item['custom_field_name'])){
				$have[] = $item['custom_field_name'];
			 }
			 $i++; 
		 }
	 }
	 
 }
 $custom_custom_field_names_for_content = array();
if(is_array( $diff) and is_array($more) ){
    $i=0;
	 foreach($more as $item2){

	 foreach($diff as $item1){
			  if(isset($more[$i]) and isset($item1['copy_of_field'])){
				  if($item1['copy_of_field'] == $item2['id']){
				//print $item1['copy_of_field'];
				 unset($more[$i]);
				  }
			  }
			    if(isset($more[$i]) and isset($item1['custom_field_name'])){
				  if($item1['custom_field_name'] == $item2['custom_field_name']){
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
<?php if($list_preview == false): ?>

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
 <?php if(isset($more) and !empty($more)): ?>
 <br />
 <label class="mw-ui-label">Preview of custom fields</label>
 <?php endif; ?>
<table width="100%" cellspacing="0" cellpadding="0" class="mw-ui-admin-table">
  <thead>
    <tr>
      <th width="20%">Name</th>
      <th>Value</th> 
    </tr>
  </thead>
  <tbody>
   
      <?php foreach( $more as $field): ?>
       <tr>
      <td><?php print $field['custom_field_name']; ?> 
     
      
      </td>
       
      
      <td ondblclick="mw.custom_fields.edit('.mw-admin-custom-field-edit-item','<?php print $field['id'] ?>', false);"><?php  print $field['custom_field_values_plain']; ?></td>
      <!-- <td> <a class="mw-ui-admin-table-show-on-hover mw-ui-btn mw-ui-btn-meduim" href="javascript:;" onmouseup="mw.custom_fields.edit('.mw-admin-custom-field-edit-item','<?php print $field['id'] ?>', false);">edit</a>
      
      </td>-->
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php endif; ?>
<?php else : ?>
<?php if(!isset($params['save_to_content_id']) and $suggest_from_rel == false and $list_preview == false): ?>
<div class="mw-ui-field mw-tag-selector mw-custom-fields-tags" onclick="__smart_field_opener(event)">
  <div class="mw-custom-fields-from-page-title"> <span class="mw-custom-fields-from-page-title-text">
    <?php _e("You dont have any custom fields"); ?>
    . </span> </div>
</div>
<?php endif; ?>
<?php endif; ?>
<?php endif; ?>
