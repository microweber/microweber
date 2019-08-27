<div class="col-<?php echo $settings['field_size']; ?>">
<div class="form-group">
	<label> 
	<?php echo $data['name']; ?>
	<?php if ($settings['required']): ?>
	<span style="color: red;">*</span>
	<?php endif; ?>
	</label>
	<input type="email" class="mw-ui-field form-control" <?php if ($settings['required']): ?>required="true"<?php endif; ?> data-custom-field-id="<?php echo $data['id']; ?>" name="<?php echo $data['name']; ?>" placeholder="<?php echo $data['placeholder']; ?>" />
</div>
</div>