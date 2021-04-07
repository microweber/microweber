<?php include('settings_header.php'); ?>



<?php 
if (!isset($data['rows'])) {
	$data['rows'] = 5;
}
?>

<style>
    #mw-custom-fields-text-holder textarea {
      resize:both;
    }
</style>

<div class="custom-field-settings-values">
    <div class="mw-custom-field-group">
      <label class="control-label" for="value<?php print $rand; ?>"><?php _e("Value"); ?></label>
        <div id="mw-custom-fields-text-holder">
            <textarea class="form-control" name="value"><?php print ($data['value']) ?></textarea>
        </div>
    </div>

    <div class="mw-custom-field-group">
        <label class="control-label" for="value<?php print $rand; ?>"><?php _e("Textarea Rows"); ?></label>
        <div id="mw-custom-fields-text-holder">
          <input type="number" onkeyup="" class="form-control" value="<?php print ($data['rows']) ?>" name="options[rows]" id="input_field_label<?php print $rand; ?>">
        </div>
    </div>
<?php print $savebtn; ?>
</div>

<?php include('placeholder_settings.php'); ?>

<?php include('settings_footer.php'); ?>
