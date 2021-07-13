<?php include('settings_header.php'); ?>
  <?php // p ($data['value']) ?>
  <?php
   if(empty($data['values'])){
	//$data['values'] = array(0);
   }
   
  // d($data);
   // p ($data['values'])

   ?>
<div class="custom-field-settings-values">
  <label class="mw-ui-label"><?php _e("Values"); ?></label>
  <div class="mw-custom-field-group" style="padding-top: 0;" id="fields<?php print $rand; ?>">
    <?php if(is_array($data['values']) and !empty($data['values'])) : ?>
    <?php foreach($data['values'] as $v): ?>
    <div class="mw-custom-field-form-controls d-flex">
        <i class="mdi mdi-cursor-move custom-fields-handle-field align-self-center mr-2"></i>
        <input type="text" class="form-control col-5"  name="value[]"  value="<?php print $v; ?>">
      <?php print $add_remove_controls; ?> </div>
    <?php endforeach; ?>
    <?php else: ?>
    <div class="mw-custom-field-form-controls">
        <i class="mdi mdi-cursor-move custom-fields-handle-field align-self-center mr-2"></i>
        <input type="text" name="value[]" class="form-control col-5"  value="" />
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
