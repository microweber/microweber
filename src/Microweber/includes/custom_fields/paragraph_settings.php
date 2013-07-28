<?php include('settings_header.php'); ?>
  <div class="mw-custom-field-group">
    <div class="mw-custom-field-form-controls">
      <textarea onkeyup="mw.custom_fields.autoSaveOnWriting(this, 'custom_fields_edit<?php print $rand; ?>');" name="custom_field_value"   class="mw-ui-field"><?php print ($data['custom_field_value']) ?></textarea>
    </div>
  </div>
  <?php include('settings_footer.php'); ?>
