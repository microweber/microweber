<?php include('settings_header.php'); ?>
<div class="custom-field-settings-values">
<div class="mw-custom-field-group">
    <label class="mw-custom-field-label" for="custom_field_width_size<?php print $rand; ?>"><b><?php _e('Type'); ?></b></label>
    <div class="mw-custom-field-form-controls">

    	<?php
    	if (isset($data['options']['field_type'][0])) {
    		$field_type = $data['options']['field_type'][0];
    	}
    	if (isset($data['options']['field_type']) && is_string($data['options']['field_type'])) {
    		$field_type = $data['options']['field_type'];
    	}

    	$buttonOptions = array();
    	$buttonOptions['submit'] = 'Submit';
    	$buttonOptions['button'] = 'Button';

    	?>

       <select class="form-select  mw-full-width" name="options[field_type]">

       	<?php foreach($buttonOptions as $optionKey=>$optionValue): ?>
        <option <?php if($field_type == $optionKey):?>selected="selected"<?php endif; ?> value="<?php echo $optionKey; ?>"><?php echo $optionValue; ?></option>
        <?php endforeach; ?>

       </select>
    </div>
</div>
<?php print $savebtn; ?>
</div>
<?php include('settings_footer.php'); ?>
