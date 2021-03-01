<?php include('settings_header.php'); ?>

<div class="custom-field-settings-name mb-2">
  <label class="control-label" for="input_field_label<?php print $rand; ?>">
    <?php _e('Title'); ?>
  </label>
    <small class="text-muted d-block mb-2"><?php _e('The name of your field');?></small>
    <input type="text" onkeyup="" class="form-control" value="<?php print ($data['name']) ?>" name="name" id="input_field_label<?php print $rand; ?>">
</div>

<?php include('placeholder_settings.php'); ?>
<?php include('settings_footer.php'); ?>
