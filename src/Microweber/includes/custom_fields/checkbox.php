<?php

//$rand = rand();

if (!isset($data['id'])) {
include('empty_field_vals.php');
}



if (!isset( $data['input_class']) and isset($params['input-class'])) {
     $data['input_class'] = $params['input-class'];
} elseif (!isset( $data['input_class']) and  isset($params['input_class'])) {
     $data['input_class'] = $params['input_class'];
} else {
	 
	
}
?>
<?php

    $is_required = (isset($data['options']) == true and isset($data['options']["required"]) == true);

?>
<?php if(!empty($data['custom_field_values'])) : ?>
<div class="mw-ui-field-holder custom-fields-type-checkbox">
<div class="mw-ui-label"><?php print $data["custom_field_name"]; ?></div>

	<div class="mw-customfields-checkboxes">
		<?php $i = 0; foreach($data['custom_field_values'] as $v):  ?>
		<?php $i++; ?>
			<label class="mw-ui-check"   >
				<input type="checkbox"   name="<?php print $data["custom_field_name"]; ?>[]" id="field-<?php print $data["id"]; ?>"  data-custom-field-id="<?php print $data["id"]; ?>" value="<?php print $v; ?>" />
				<span></span>
				<span><?php print ($v); ?></span>
            </label>
		
		<?php endforeach; ?>
	</div>
</div>

<?php endif; ?>
