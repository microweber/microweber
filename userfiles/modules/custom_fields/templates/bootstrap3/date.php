<?php
$rand = uniqid();
?>
<div class="col-md-<?php echo $settings['field_size']; ?>">
<div class="form-group">
	<label> 
  <?php echo $data["name"]; ?>
  <?php if ($settings['required']): ?>  
  <span style="color:red;">*</span>
  <?php endif; ?>
  </label>
  <div class="mw-custom-field-form-controls">
    <input type="text" <?php if ($settings['required']): ?> required="true"  <?php endif; ?>  data-custom-field-id="<?php echo $data["id"]; ?>"  name="<?php print $data["name"]; ?>" id="date_<?php echo $rand; ?>" placeholder="<?php echo $data["placeholder"]; ?>" class="mw-ui-field form-control" />
  </div>
</div>
</div>

 <script>
    mw.require("datepicker.css", true);
    mw.require("datepicker.js", true);
 </script>
 <script>
    $(document).ready(function(){
      mw.$( "#date_<?php print $rand; ?>" ).datepicker();
    });
 </script>
