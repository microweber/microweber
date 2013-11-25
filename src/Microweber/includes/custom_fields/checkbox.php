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
	<div <?php if (isset($data['input_class'])): ?> clssssass="<?php print $data['input_class'] ?>"  <?php endif; ?>>
	
<div class="custom-field-title"><?php print $data["custom_field_name"]; ?></div>
<div class="control-group form-group custom-fields-type-checkbox">
	<div class="mw-customfields-checkboxes">
		<?php $i = 0; foreach($data['custom_field_values'] as $v):  ?>
		<?php $i++; ?>
			<label class="checkbox"   >
				<input type="checkbox"   name="<?php print $data["custom_field_name"]; ?>[]" id="field-<?php print $data["id"]; ?>"  data-custom-field-id="<?php print $data["id"]; ?>" value="<?php print $v; ?>" />
				<span><?php print ($v); ?></span> </label>
		
		<?php endforeach; ?>
	</div>
</div>
</div>
<?php endif; ?>
