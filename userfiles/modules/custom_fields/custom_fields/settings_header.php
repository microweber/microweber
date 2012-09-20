<?
$rand = rand();

$rand = round($rand);


$is_for_module = url_param('for_module_id', 1);
$for = url_param('for', 1);
 
if (!empty($params)) {

    if (isset($params['custom_field_type']) and trim($params['custom_field_type']) != '') {

        $field_type = $params['custom_field_type'];
    }
}
?>
<script type="text/javascript">


    function save_cf_<? print $rand ?>(){
        var serializedForm = serializedForm = $("#custom_fields_edit<? print $rand ?> :input").serialize();
        $.post("<? print site_url('api/save_custom_field') ?>",    serializedForm, function(data)         {

            mw.reload_module('custom_fields')
            mw.reload_module('#mw_custom_fields_list_<? print strval($is_for_module) ?>');


            if(serializedForm.id == undefined){
                //$('#custom_fields_edit<? print strval($rand) ?>').fadeOut();

            }






        });
    }


    function remove_cf_<? print $rand ?>(){
        var serializedForm = serializedForm = $("#custom_fields_edit<? print $rand ?> :input").serialize();
        $.post("<? print site_url('api/remove_field') ?>",    serializedForm, function(data)         {

            mw.reload_module('custom_fields')
            mw.reload_module('#mw_custom_fields_list_<? print strval($is_for_module) ?>');
            $('#custom_fields_edit<? print strval($rand) ?>').fadeOut();

        });

    }

</script>
<?php
if (!isset($data['id'])) {
    $data['id'] = 0;
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
?>
<div class="form-horizontal" id="custom_fields_edit<? print $rand ?>"  >
    <fieldset>
        <? if (isset($data['id']) and intval($data['id']) != 0): ?>
            <input type="hidden" name="id" value="<? print intval($data['id']) ?>" />
        <? endif; ?>
        <? if (isset($for_module_id) and $for_module_id != false): ?>
        <? $db_t = $for; ?>
            <input type="text" name="to_table" value="<? print guess_table_name($db_t ); ?>" />
            <input type="hidden" name="to_table_id" value="<? print strval($for_module_id) ?>" />
        <? endif; ?>
        <div class="control-group">
            <label class="control-label" for="input_field_label<? print $rand ?>"><?php _e('Field name'); ?></label>
            <div class="controls">
                <input type="text" class="input-xlarge"  value="<? print ($data['custom_field_name']) ?>" name="custom_field_name" id="input_field_label<? print $rand ?>">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="select_custom_field_type<? print $rand ?>"><?php _e('Field type'); ?></label>
            <div class="controls">
                <select id="select_custom_field_type<? print $rand ?>" name="custom_field_type">
                    <option <? if (trim($field_type) == 'text'): ?> selected="selected" <? endif; ?> value="text">text</option>
                    <option  <? if (trim($field_type) == 'dropdown'): ?>  selected="selected"  <? endif; ?>  value="dropdown">dropdown</option>
                    <option  <? if (trim($field_type) == 'checkbox'): ?>  selected="selected"  <? endif; ?>  value="checkbox">checkbox</option>




                </select>
            </div>
        </div>
