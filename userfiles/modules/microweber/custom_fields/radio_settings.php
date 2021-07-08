<?php include('settings_header.php'); ?>
 <div class="custom-field-settings-values">
   <label class="control-label"><?php _e('Values'); ?></label>
    <small class="text-muted d-block mb-2"><?php _e('Add, remove and change positions of your values');?></small>


     <div class="mw-custom-field-group" style="padding-top: 0;" id="fields<?php print $rand; ?>">

     <?php if(is_array($data['values']) and !empty($data['values'])) : ?>
     <?php foreach($data['values'] as $v): ?>
      <div class="mw-custom-field-form-controls d-flex">
          <i class="mdi mdi-cursor-move custom-fields-handle-field align-self-center mr-2"></i>
          <input type="text" class="form-control col-6"  name="value[]"  value="<?php print $v; ?>" />
        <?php print $add_remove_controls; ?>
      </div>
  <?php endforeach; ?>

  <?php else: ?>
     <div class="mw-custom-field-form-controls d-flex">
        <input type="text" name="value[]" class="mw-ui-field mw-full-width"  value="" />
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
