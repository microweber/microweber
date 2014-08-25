<?php
$field = false;


if(isset($params['field-id'])){
	$field = get_custom_field_by_id($params['field-id']);
}

 
 ?>
<script>

        mw.on.moduleReload('<?php print $params['id']; ?>', function(){
            mw.admin.custom_fields.initValues(mwd.getElementById('<?php print $params['id']; ?>').querySelectorAll('.mw-admin-custom-field-name-edit-inline, .mw-admin-custom-field-value-edit-inline'));
        });

 </script>
<?php if(isset($field['custom_field_type']) and ( $field['custom_field_type'] == 'select' or $field['custom_field_type'] == 'dropdown' or $field['custom_field_type'] == 'checkbox' or $field['custom_field_type'] == 'radio')): ?>
<?php if(isset($field['custom_field_values']) and is_array($field['custom_field_values'])): ?>
<?php $vals =  $field['custom_field_values']; ?>
<?php elseif(isset($field['custom_field_value'])): ?>
<?php $vals =  $field['custom_field_value']; ?>
<?php else: ?>
<?php $vals = ''; ?>
<?php endif; ?>
<?php if(is_string($vals)) {
		$vals = array($vals);
	   	} ?>

<span class="custom-fields-values-holder">
<?php $i=0; foreach($vals as $val): ?>
<?php $i++; ?>
<span class="mw-admin-custom-field-value-edit-inline-holder"> <span class="mw-admin-custom-field-value-edit-inline" data-id="<?php print $field['id']; ?>"><?php print $val; ?></span> <span class="delete-custom-fields" onclick="mw.admin.custom_fields.deleteFieldValue(this);"></span> <span class="custom-field-comma">,</span> </span>
<?php endforeach; ?>
</span> <span class="mw-ui-btn mw-ui-btn-small mw-ui-btn-invert mw-ui-btn-icon btn-create-custom-field-value show-on-hover" data-id="<?php print $field['id']; ?>"><span class="mw-icon-plus"></span> <?php _e("Add"); ?></span>
<?php elseif(isset($field['custom_field_type']) and ( $field['custom_field_type'] == 'text' or $field['custom_field_type'] == 'message' or $field['custom_field_type'] == 'textarea' or $field['custom_field_type'] == 'title')): ?>
<textarea class="mw-admin-custom-field-value-edit-text mw-ui-field" style=" width:100%; overflow:hidden;" data-id="<?php print $field['id']; ?>"><?php print $field['custom_field_value']; ?></textarea>
<?php elseif(isset($field['custom_field_type']) and (( $field['custom_field_type'] == 'address') or $field['custom_field_type'] == 'upload')): ?>
<div  style="width:100%; display:block; min-height:20px;" onclick="mw.admin.custom_fields.edit_custom_field_item('#mw-custom-fields-list-settings-<?php print $field['id']; ?>',<?php print $field['id']; ?>);"><?php print $field['custom_field_values_plain']; ?></div>
 
<?php else: ?>
<?php
		$vals = '';
		if($field['custom_field_values_plain'] != ''): ?>
<?php $vals = $field['custom_field_values_plain'];?>
<?php elseif(is_string($field['custom_field_value'])): ?>
<?php $vals = $field['custom_field_value'];?>
<?php endif; ?>
<span class="custom-fields-values-holder"><span class="mw-admin-custom-field-value-edit-inline-holder"><span class="mw-admin-custom-field-value-edit-inline" data-id="<?php print $field['id']; ?>"><?php print $vals; ?></span></span></span>
<?php endif; ?>
