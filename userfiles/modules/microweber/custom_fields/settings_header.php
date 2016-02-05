 <?php

 $rand = rand();


  $data_orig = $data;
//$rand = round($rand);
 
$add_remove_controls = ''.
'<span class="mw-icon-plus" onclick="mw.custom_fields.add(this);" title="'. _e("Add", true). '"></span>'.
'<span class="mw-icon-close" onclick="mw.custom_fields.remove(this);mw.custom_fields.save(\'custom_fields_edit'.$rand.'\');" title="'. _e("Remove", true). '"></span>'.
'<span class="mw-icon-drag custom-fields-handle-field" title="'. _e("Move", true). '"></span>';


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
$is_for_module = mw()->url_manager->param('for_module_id', 1);
$for = mw()->url_manager->param('for', 1);
 
if (!empty($params)) {

    if (isset($params['type']) and trim($params['type']) != '') {

        $field_type = $params['type'];
    }
}

if (!isset($data['id'])) {
    $data['id'] = 0;
	
}

if($for == false and isset($data['rel_type'])){
	$for = $data['rel_type'];
}
 


if (intval($data['id']) == 0) {
include(__DIR__.DS.'empty_field_vals.php');
}
foreach ($data_orig as $key => $value) {
	//$data[$key] =  $value;
}
 


if (!isset($data['name'])) {
    $data['name'] = '';
}
 if (isset($data['type'])) {
	  $field_type = $data['type'];
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

 if ($data['name'] == '') {
	 
	  $data['name'] =  ucfirst($field_type);
	 switch($field_type){
		case 'text': 
		 $data['name'] = 'text field';
		 
		 break;
		 
		 case 'site': 
		 $data['name'] = 'web site';
		 
		 break;
		 
		 	 case 'upload': 
		 $data['name'] = 'file upload';
		 
		 break;
		 
		 
		  
		 	 case 'checkbox': 
		 $data['name'] = 'multiple choices';
		 
		 break;
		 
		 
		  case 'radio': 
		 $data['name'] = 'single choice';
		 
		 break;
		 
	 }
	 
	 
	 
 $data['name'] =  ucwords( $data['name'] );
	 
	 
  
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
if (!isset($data['value'])) {
    $data['value'] = '';
}

if(!isset($for_module_id) or $for_module_id == false){
	if (isset($params['for_module_id'])) {
	    $for_module_id = $params['for_module_id'] ;
	} else {
	 $for_module_id = false ;	
	}
}
if(!isset($for) or $for == false){
if (isset($data['rel_type'])) {
  $for = $data['rel_type'];
	
}
}

if (!isset($data['position'])) {
  $data['position'] = 0;
	
}
if(!isset($for_module_id) or $for_module_id == false){
if (isset($data['rel_id'])) {
  $for_module_id = $data['rel_id'];
	
}
}


if (!isset( $for_module_id) and isset($data['for_module_id'])) {
  $for_module_id = $data['for_module_id'];
	
}
 
if (isset($data['save_to_content_id'])) {
  $save_to_content_id = $data['save_to_content_id'];
	
}
if($for_module_id == false){
	if (isset($data_orig['rel_id'])) {
  $for_module_id = $data_orig['rel_id'];
		
	}
}

if($for_module_id == false){
	if (isset($data_orig['for_module_id'])) {
  $for_module_id = $data_orig['for_module_id'];
		
	}
}


if($for == false){
	if (isset($data_orig['rel_type'])) {
  $for = $data_orig['rel_type'];
		
	}
}



if($for == false){
	if (isset($data_orig['for'])) {
  $for = $data_orig['for'];
		
	}
}

 


?>



   
<div class="mw-field-type-<?php print trim($field_type) ?>" id="custom_fields_edit<?php print $rand; ?>">
<?php if (isset($data['id']) and intval($data['id']) != 0): ?>
<input type="hidden" name="cf_id" value="<?php print intval($data['id']) ?>" />
<?php endif; ?>
<?php if (isset($data['for']) and $data['for'] != false): ?>
<?php $db_t = $for; ?>
<input type="hidden" name="rel" value="<?php print ($db_t); ?>" />
<input type="hidden" name="rel_id" value="<?php print strval($for_module_id) ?>" />
<?php endif; ?>
<?php if (isset($save_to_content_id)): ?> 
<input type="hidden" name="copy_rel_id" value="<?php print strval($save_to_content_id) ?>" />
<?php endif; ?>
<input type="hidden" name="type" value="<?php print trim($field_type) ?>" />

<?php 


/*<input type="hidden" name="position" value="<?php print $data['position'] ?>" /> */

?>

<script>


$(document).ready(function(){

var master = mwd.getElementById('custom_fields_edit<?php print $rand; ?>');

var fields = master.querySelector('input[type="text"], input[type="email"], textarea, input[type="checkbox"], input[type="radio"], select');

 

if( typeof is_body_click_binded === 'undefined' ){
       is_body_click_binded = true;



}




});

</script>
</div>
