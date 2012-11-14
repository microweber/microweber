<?

$rand = rand();
$rand = round($rand);

$add_remove_controls = ''.
'<span class="ico iAdd mw-addfield" onclick="mw.custom_fields.add(this);" title="'. _e("Add", true). '"></span>'.
'<span class="ico iRemove mw-removefield" onclick="mw.custom_fields.remove(this);mw.custom_fields.save(\'custom_fields_edit'.$rand.'\');" title="'. _e("Remove", true). '"></span>'.
'<span class="ico iMove custom-fields-handle-field" title="'. _e("Move", true). '"></span>';





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
?>

<?php
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
    $data['custom_field_name'] =  $field_type;
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

if (isset($data['to_table'])) {
  $for = $data['to_table'];
	
}

if (!isset($data['position'])) {
  $data['position'] = 0;
	
}


if (isset($data['to_table_id'])) {
  $for_module_id = $data['to_table_id'];
	
}
?>

<div  id="custom_fields_edit<? print $rand ?>" >

<? if (isset($data['id']) and intval($data['id']) != 0): ?>
<input type="hidden" name="id" value="<? print intval($data['id']) ?>" />
<? endif; ?>
<? if (isset($for_module_id) and $for_module_id != false): ?>
<? $db_t = $for; ?>
<input type="hidden" name="to_table" value="<? print db_get_assoc_table_name(guess_table_name($db_t )); ?>" />
<input type="hidden" name="to_table_id" value="<? print strval($for_module_id) ?>" />
<? endif; ?>
<input type="hidden" name="custom_field_type" value="<? print trim($field_type) ?>" />
<input type="hidden" name="position" value="<? print $data['position'] ?>" />

<div class="mw-custom-field-group ">
  <label class="mw-ui-label" for="input_field_label<? print $rand ?>">
    <?php _e('Field name'); ?>
  </label>
  <div class="mw-custom-field-form-controls">
    <input type="text" class="mw-ui-field" <? if (isset($data['id']) and intval($data['id']) != 0): ?>onkeyup="mw.custom_fields.autoSaveOnWriting(this, 'custom_fields_edit<? print $rand ?>');"<?php endif; ?> value="<? print ($data['custom_field_name']) ?>" name="custom_field_name" id="input_field_label<? print $rand ?>">
  </div>
</div>
 
