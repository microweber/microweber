<?php include('settings_header.php'); ?>

<div class="custom-field-settings-name">
  <label class="mw-ui-label" for="input_field_label<?php print $rand; ?>">
    <?php _e('Title'); ?>
  </label>
  <input type="text" onkeyup="" class="mw-ui-field mw-full-width" value="<?php print ($data['name']) ?>" name="name" id="input_field_label<?php print $rand; ?>">
</div>

<div class="custom-field-settings-values">
<?php print $savebtn; ?> 
</div>
<?php include('settings_footer.php'); ?>
