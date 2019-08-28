<div class="col-<?php echo $settings['field_size']; ?>">
<div class="mw-ui-field-holder custom-fields-type-checkbox">
<div class="mw-ui-label"><?php echo $data["name"]; ?></div>
	<div class="mw-customfields-checkboxes">
		<?php $i = 0; foreach($data['values'] as $value):  ?>
		<?php $i++; ?>
			<label class="mw-ui-check">
				<input class="form-control" type="checkbox" name="<?php echo $data["name"]; ?>[]" id="field-<?php echo $data["id"]; ?>"  data-custom-field-id="<?php echo $data["id"]; ?>" value="<?php echo $value; ?>" />
				<span></span>
				<span><?php echo $value; ?></span>
            </label>
		
		<?php endforeach; ?>
	</div>
</div>
</div>