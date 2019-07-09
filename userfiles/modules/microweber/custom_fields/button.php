<?php
$field_type = 'button';
if(isset($data['options']['field_type'])) {
	$field_type = $data['options']['field_type'];
}
?>
<input type="<?php echo $field_type; ?>" class="mw-ui-btn pull-right" value="<?php _e($data["title"]); ?>"/>

