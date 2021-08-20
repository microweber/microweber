<div class="mw-flex-col-md-<?php echo $settings['field_size']; ?>">
<div class="mw-ui-field-holder">
	<label class="mw-ui-label">
	<?php echo $data['name']; ?>
	<?php if ($settings['required']): ?>
	<span style="color: red;">*</span>
	<?php endif; ?>
	</label>
	 <?php if ($data['help']): ?>
        <small class="mw-custom-field-help"><?php echo $data['help']; ?></small>
    <?php endif; ?>
	<div class="mw-ui-controls">
		<textarea type="text" rows="<?php echo $settings['rows']; ?>" class="mw-ui-field" <?php if ($settings['required']): ?>required<?php endif; ?> data-custom-field-id="<?php echo $data['id']; ?>" name="<?php echo $data['name_key']; ?>" placeholder="<?php echo $data['placeholder']; ?>"><?php echo $data['value']; ?></textarea>
	</div>
</div>
</div>
