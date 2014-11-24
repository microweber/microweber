<?php include('settings_header.php'); ?>
    <div class="mw-custom-field-group">
      <label class="mw-custom-field-label" for="custom_field_value<?php print $rand; ?>">Value</label>
      <div class="mw-custom-field-form-controls">
        <input type="text"  name="custom_field_value"  value="<?php print ($data['custom_field_value']) ?>" id="custom_field_value<?php print $rand; ?>">
      </div>
    </div>
    <?php include('settings_footer.php'); ?>
