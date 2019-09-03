<div class="col-<?php echo $settings['field_size']; ?>">
<div class="form-group">
	<label> 
	<?php echo $data['name']; ?>
	<?php if ($settings['required']): ?>
	<span style="color: red;">*</span>
	<?php endif; ?>
	</label>
	<input type="hidden" class="mw-ui-field" data-custom-field-id="<?php echo $data['id']; ?>" name="<?php echo $data['name']; ?>" value="<?php echo $data['value']; ?>" />
	 <?php if ($data['help']): ?>
        <small class="mw-custom-field-help"><?php echo $data['help']; ?></small>
    <?php endif; ?>
	<div class="controls">
       <?php echo $data["value"]; ?>
  </div>
</div>
</div>