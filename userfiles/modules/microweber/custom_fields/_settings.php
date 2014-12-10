<?php include('settings_header.php'); ?>
    <div class="mw-custom-field-group">
      <label class="mw-custom-field-label" for="value<?php print $rand; ?>">Value</label>
      <div class="mw-custom-field-form-controls">
        <input type="text"  name="value"  value="<?php print ($data['value']) ?>" id="value<?php print $rand; ?>">
      </div>
    </div>
    <?php include('settings_footer.php'); ?>
