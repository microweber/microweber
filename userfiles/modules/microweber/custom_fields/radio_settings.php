<?php include('settings_header.php'); ?>
   <?php // p ($data['value']) ?>
   
   <?php
   if(empty($data['values'])){
	//$data['values'] = array(0);
   }
   
 
 
   ?>




<div class="custom-field-settings-name">

  <div class="mw-custom-field-group ">
  <label class="mw-ui-label" for="input_field_label<?php print $rand; ?>">
    <?php _e('Field name'); ?>
  </label>

    <input type="text" class="mw-ui-field" value="<?php print ($data['name']) ?>" name="name" id="input_field_label<?php print $rand; ?>">

</div>
</div>

 <div class="custom-field-settings-values">

   <label class="mw-ui-label"><?php _e('Values'); ?></label>
   
    <div class="mw-custom-field-group" style="padding-top: 0;" id="fields<?php print $rand; ?>">

     <?php if(is_array($data['values'])) : ?>
     <?php foreach($data['values'] as $v): ?>
      <div class="mw-custom-field-form-controls">
        <input type="text" class="mw-ui-field" xxxonkeyup="mw.custom_fields.autoSaveOnWriting(this, 'custom_fields_edit<?php print $rand; ?>');" name="value[]"  value="<?php print $v; ?>" />
        <?php print $add_remove_controls; ?>
      </div>
  <?php endforeach; ?>

  <?php else: ?>


    <div class="mw-custom-field-form-controls">
        <input type="text" name="value[]" class="mw-ui-field"  value="" />
        <?php print $add_remove_controls; ?>
      </div>
  <?php endif; ?>



  <script type="text/javascript">
    mw.custom_fields.sort("fields<?php print $rand; ?>");
  </script>


  
   <label class="mw-ui-check"><input type="checkbox"  class="mw-custom-field-option" name="options[required]"  <?php if(isset($data['options']) == true and isset($data['options']["required"]) == true): ?> checked="checked" <?php endif; ?> value="1"><span></span><span><?php _e("Required"); ?>?</span></label>

    </div> <?php print $savebtn; ?>
    </div>

<?php include('settings_footer.php'); ?>
