<? include('settings_header.php'); ?>
   <? // p ($data['custom_field_value']) ?>
   
   <?
   if(empty($data['custom_field_values'])){
	//$data['custom_field_values'] = array(0);
   }
   
  // d($data);
   // p ($data['custom_field_values'])

   ?>




<div class="custom-field-col-left">

  <div class="mw-custom-field-group ">
  <label class="mw-ui-label" for="input_field_label<? print $rand ?>">
    <?php _e('Field name'); ?>
  </label>
  <div class="mw-custom-field-form-controls">
    <input type="text" class="mw-ui-field" value="<? print ($data['custom_field_name']) ?>" name="custom_field_name" id="input_field_label<? print $rand ?>">
  </div>
</div>
</div>

 <div class="custom-field-col-right">

   <label class="mw-ui-label">Values</label>
   
    <div class="mw-custom-field-group" style="padding-top: 0;" id="fields<?php print $rand; ?>">

     <? if(isarr($data['custom_field_values'])) : ?>
     <? foreach($data['custom_field_values'] as $v): ?>
      <div class="mw-custom-field-form-controls">
        <input type="text" class="mw-ui-field" onkeyup="mw.custom_fields.autoSaveOnWriting(this, 'custom_fields_edit<? print $rand ?>');" name="custom_field_value[]"  value="<? print $v; ?>" />
        <?php print $add_remove_controls; ?>
      </div>
  <? endforeach; ?>

  <? else: ?>


    <div class="mw-custom-field-form-controls">
        <input type="text" name="custom_field_value[]" class="mw-ui-field"  value="" />
        <?php print $add_remove_controls; ?>
      </div>
  <? endif; ?>



  <script type="text/javascript">
    mw.custom_fields.sort("fields<?php print $rand; ?>");
  </script>


  


    </div> <button type="button" class="mw-ui-btn mw-ui-btn-blue mw-custom-fields-save" onclick="__save();"><?php _e('Save changes'); ?></button>
    </div>

<? include('settings_footer.php'); ?>
