<?php

 $rand = rand();
//$rand = round($rand);

$add_remove_controls = ''.
'<span class="ico iAdd2 mw-addfield" onclick="mw.custom_fields.add(this);" title="'. _e("Add", true). '"></span>'.
'<span class="ico iRemove2 mw-removefield" onclick="mw.custom_fields.remove(this);mw.custom_fields.save(\'custom_fields_edit'.$rand.'\');" title="'. _e("Remove", true). '"></span>'.
'<span class="ico iMove custom-fields-handle-field" title="'. _e("Move", true). '"></span>';


$savebtn = '<button type="button" class="mw-ui-btn mw-ui-btn-blue mw-custom-fields-save" onclick="__save();">'. _e('Save', true). '</button>';
$savebtn = '';


if(!isset($settings)){
$settings = 1;	
} else if($settings == false){
	$settings = 1;
}
  $hidden_class = '';
 if(intval($settings) == 2){

 }
 $hidden_class = ' mw-hide ';
$is_for_module = url_param('for_module_id', 1);
$for = url_param('for', 1);
 
if (!empty($params)) {

    if (isset($params['custom_field_type']) and trim($params['custom_field_type']) != '') {

        $field_type = $params['custom_field_type'];
    }
}
?><?php
if (!isset($data['id'])) {
    $data['id'] = 0;
	
}
if (intval($data['id']) == 0) {
include('empty_field_vals.php');
}
if (!isset($data['custom_field_name'])) {
    $data['custom_field_name'] = '';
}
 if (isset($data['custom_field_type'])) {
	  $field_type = $data['custom_field_type'];
}

 
 
 
if (isset($data['type'])) {
	  $field_type = $data['type'];
} else {
if (!isset($field_type)) {
    $field_type = 'text';
}
}

if (!isset($data['type'])) {
	$data['type'] =  $field_type;
}

 if ($data['custom_field_name'] == '') {
	 
	  $data['custom_field_name'] =  ucfirst($field_type);
	 switch($field_type){
		case 'text': 
		 $data['custom_field_name'] = 'text field';
		 
		 break;
		 
		 case 'site': 
		 $data['custom_field_name'] = 'web site';
		 
		 break;
		 
		 	 case 'upload': 
		 $data['custom_field_name'] = 'file upload';
		 
		 break;
		 
		 
		  
		 	 case 'checkbox': 
		 $data['custom_field_name'] = 'multiple choices';
		 
		 break;
		 
		 
		  case 'radio': 
		 $data['custom_field_name'] = 'single choice';
		 
		 break;
		 
	 }
	 
	 
	 
 $data['custom_field_name'] =  ucwords( $data['custom_field_name'] );
	 
	 
  
}

 
if (!isset($data['custom_field_required'])) {
    $data['custom_field_required'] = 'n';
}
if (!isset($data['custom_field_is_active'])) {
    $data['custom_field_is_active'] = 'y';
}
if (!isset($data['custom_field_help_text'])) {
    $data['custom_field_help_text'] = '';
}
if (!isset($data['custom_field_value'])) {
    $data['custom_field_value'] = '';
}

if (isset($params['for_module_id'])) {
    $for_module_id = $params['for_module_id'] ;
} else {
 $for_module_id = false ;	
}

if (isset($data['rel'])) {
  $for = $data['rel'];
	
}

if (!isset($data['position'])) {
  $data['position'] = 0;
	
}

if (isset($data['rel_id'])) {
  $for_module_id = $data['rel_id'];
	
}



if (isset($data['for_module_id'])) {
  $for_module_id = $data['for_module_id'];
	
}
 
if (isset($data['save_to_content_id'])) {
  $save_to_content_id = $data['save_to_content_id'];
	
}
?>

<div class="mw-field-type-<?php print trim($field_type) ?>" id="custom_fields_edit<?php print $rand; ?>" >
<?php if (isset($data['id']) and intval($data['id']) != 0): ?>
<input type="hidden" name="cf_id" value="<?php print intval($data['id']) ?>" />
<?php endif; ?>
<?php if (isset($data['for']) and $data['for'] != false): ?>
<?php $db_t = $for; ?>
<input type="hidden" name="rel" value="<?php print db_get_assoc_table_name(guess_table_name($db_t )); ?>" />
<input type="hidden" name="rel_id" value="<?php print strval($for_module_id) ?>" />
<?php endif; ?>
<?php if (isset($save_to_content_id)): ?>
<input type="hidden" name="copy_rel_id" value="<?php print strval($save_to_content_id) ?>" />
<?php endif; ?>
<input type="hidden" name="custom_field_type" value="<?php print trim($field_type) ?>" />
<input type="hidden" name="position" value="<?php print $data['position'] ?>" />



<script>


$(document).ready(function(){

var master = mwd.getElementById('custom_fields_edit<?php print $rand; ?>');

var fields = master.querySelector('input[type="text"], input[type="email"], textarea, input[type="checkbox"], input[type="radio"], select');

 //$(fields).attr("onchange", "__save()");


if( typeof is_body_click_binded === 'undefined' ){
       is_body_click_binded = true;
 $(mwd.body).bind("click", function(e){
   //__save()
   if(window.name.contains("module-settings") && self !== top){
      if(e.target.type == 'checkbox'){
        __save()
      }

   }
 });


}




});

</script>

