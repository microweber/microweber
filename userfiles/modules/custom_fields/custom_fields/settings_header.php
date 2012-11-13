

<?

$add_remove_controls = ''.
'<span class="ico iAdd mw-addfield" onclick="mw.custom_fields.add(this);" title="'. _e("Add", true). '"></span>'.
'<span class="ico iRemove mw-removefield" onclick="mw.custom_fields.remove(this);" title="'. _e("Remove", true). '"></span>'.
'<span class="ico iMove" title="'. _e("Move", true). '"></span>';


$rand = rand();
 
$rand = round($rand);
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
<script type="text/javascript">

mw.custom_fields.save = function(id){
    var obj = mw.form.serialize("#"+id);
    $.post("<? print site_url('api_html/save_custom_field') ?>", obj, function(data) {
        if(obj.id === undefined){
             mw.reload_module('[data-parent-module="custom_fields"]');
        }
        else {
            $("#"+id).parents('tr').first().find('.custom-field-preview-cell').html(data);
        }
          mw.$(".mw-live-edit [data-type='custom_fields']").each(function(){
          // if(!mw.tools.hasParentsWithClass(this, 'mw_modal') && !mw.tools.hasParentsWithClass(this, 'is_admin')){
               mw.reload_module(this);
          // }
        });
    });
}

mw.custom_fields.del = function(id){
    var q = "Are you sure you want to delete this?";
    mw.tools.confirm(q, function(){
      var obj = mw.form.serialize("#"+id);
      $.post("<? print site_url('api/remove_field') ?>",  obj, function(data){
        $("#"+id).parents('tr').first().remove();
      });
    });

}



</script>
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
 if ($data['custom_field_name'] == '') {
    $data['custom_field_name'] =  $field_type;
}
 
 
 
if (isset($data['type'])) {
	  $field_type = $data['type'];
} else {
if (!isset($field_type)) {
    $field_type = 'text';
}
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
<div class="mw-custom-field-group ">
  <label class="mw-ui-label" for="input_field_label<? print $rand ?>">
    <?php _e('Field name'); ?>
  </label>
  <div class="mw-custom-field-form-controls">
    <input type="text" class="mw-ui-field"   value="<? print ($data['custom_field_name']) ?>" name="custom_field_name" id="input_field_label<? print $rand ?>">
  </div>
</div>
<div class="mw-custom-field-group<? print $hidden_class ?>">
  <label class="mw-ui-label" for="select_custom_field_type<? print $rand ?>">
    <?php _e('Field type'); ?>
  </label>
  <div>
    <div class="mw-ui-select">
      <select class="mw-ui-field" id="select_custom_field_type<? print $rand ?>" name="custom_field_type">
        <option <? if (trim($field_type) == 'text'): ?> selected="selected" <? endif; ?> value="text">Text</option>
        <option  <? if (trim($field_type) == 'dropdown'): ?>  selected="selected"  <? endif; ?>  value="dropdown">Dropdown</option>
        <option  <? if (trim($field_type) == 'checkbox'): ?>  selected="selected"  <? endif; ?>  value="checkbox">Checkbox</option>
        <option  <? if (trim($field_type) == 'price'): ?>  selected="selected"  <? endif; ?>  value="price">Price</option>
      </select>
    </div>
  </div>
</div>
