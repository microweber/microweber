<div class="control-group form-group">
	
   <label class="mw-ui-label" ><?php echo $data["name"]; ?>
	<?php if ($settings["required"]): ?>  
	<span style="color:red;">*</span>
	<?php endif; ?> 
  </label>
	
	<div class="mw-custom-field-form-controls">
		<module type="captcha" />
	</div>
</div>
