<?php include('settings_header.php'); ?>


<div class="custom-field-settings-name">

    <div class="mw-custom-field-group ">
        <label class="control-label" for="input_field_label<?php print $rand; ?>">
            <?php _e('Title'); ?>
        </label>
        <small class="text-muted d-block mb-2"><?php _e('The name of your field');?></small>
        <input type="text" class="form-control" value="<?php print ($data['name']) ?>" name="name" id="input_field_label<?php print $rand; ?>">

    </div>
</div>

<div class="mw-custom-field-group">
    <label class="control-label" for="value<?php print $rand; ?>"><?php _e("Placeholder"); ?></label>
    <small class="text-muted d-block mb-2"><?php _e('Specifies a short hint that describes the expected value of an input field');?></small>
    <div id="mw-custom-fields-text-holder">
        <input type="text" class="form-control" name="placeholder" value="<?php echo $data['placeholder']; ?>" />
    </div>
</div>

<div class="custom-field-settings-values">
    <?php print $savebtn; ?>
</div>

<?php include('settings_footer.php'); ?>
