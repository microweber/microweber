<div class="control-group form-group">
	
   <label class="mw-ui-label" ><?php print $data["name"]; ?>
	<?php if (isset($data['options']) == true and isset($data['options']["required"]) == true): ?>  
	<span style="color:red;">*</span>
	<?php endif; ?> 
  </label>
	
	<div class="mw-custom-field-form-controls">
		<module type="captcha" />
	</div>
</div>
