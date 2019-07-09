<?php
$field_type = 'button';
if(isset($data['options']['field_type'])) {
	$field_type = $data['options']['field_type'];
}
?>
<div class="control-group form-group">

	<label class="mw-ui-label">&nbsp;</label>

	<div class="mw-custom-field-form-controls">
		<input type="<?php echo $field_type; ?>" class="mw-ui-btn" value="<?php _e($data["title"]); ?>"/>
	</div>
</div>
