<?php include('settings_header.php'); ?>
  <?php // p ($data['custom_field_value']) ?>
  <?php
   if(empty($data['custom_field_values'])){
	//$data['custom_field_values'] = array(0);
   }
   
  // d($data);
   // p ($data['custom_field_values'])

   ?>


 <div class="custom-field-settings-name">


  <label class="mw-ui-label" for="input_field_label<?php print $rand; ?>">
    <?php _e('Title'); ?>
  </label>

    <input type="text" class="mw-ui-field" value="<?php print ($data['custom_field_name']) ?>" name="custom_field_name" id="input_field_label<?php print $rand; ?>">


</div>



   <div class="custom-field-settings-values">

      <label class="mw-ui-label"><?php _e("Values"); ?></label>
      <div class="mw-custom-field-group" style="padding-top: 0;" id="fields<?php print $rand; ?>">
        <?php if(is_array($data['custom_field_values'])) : ?>
        <?php foreach($data['custom_field_values'] as $v): ?>
        <div class="mw-custom-field-form-controls">
          <input type="text" class="mw-ui-field"  name="custom_field_value[]"  value="<?php print $v; ?>">
          <?php print $add_remove_controls; ?> </div>
        <?php endforeach; ?>
        <?php else: ?>
        <div class="mw-custom-field-form-controls">
          <input type="text" name="custom_field_value[]" class="mw-ui-field"  value="" />
          <?php print $add_remove_controls; ?>
        </div>
        <?php endif; ?>
        <script type="text/javascript">
            mw.custom_fields.sort("fields<?php print $rand; ?>");
        </script>
      </div>
      
      <?php print $savebtn; ?>
  </div>
  <?php include('settings_footer.php'); ?>
