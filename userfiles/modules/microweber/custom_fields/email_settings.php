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

<div class="custom-field-settings-values">
    <div class="mw-custom-field-group">
        <label class="control-label" for="value<?php print $rand; ?>"><?php _e("Value"); ?></label>
        <small class="text-muted d-block mb-2"><?php _e('This attribute specifies the value of description');?></small>

        <input type="text" class="form-control" name="value" value="<?php print ($data['value']) ?>" id="value<?php print $rand; ?>">
        <label class="mw-ui-check mt-3">
            <input type="checkbox" class="mw-custom-field-option"
                   name="options[required]" <?php if (isset($data['options']) == true and isset($data['options']["required"]) == true): ?>
                checked="checked" <?php endif; ?> value="1">
            <label class="mw-ui-check"><input type="checkbox"  class="mw-custom-field-option" name="options[required]"  <?php if(isset($data['options']) == true and isset($data['options']["required"]) == true): ?> checked="checked" <?php endif; ?> value="1"><span></span><span><?php _e("Required"); ?>?</span></label>
            <small class="text-muted d-block mb-2"><?php _e('Are the choices required');?></small>
        </label>
    </div>
    <?php print $savebtn; ?>
</div>

<div class="mw-custom-field-group">
    <label class="control-label" for="value<?php print $rand; ?>"><?php _e("Placeholder"); ?></label>
    <div id="mw-custom-fields-text-holder">
        <input type="text" class="form-control" name="placeholder" value="<?php echo $data['placeholder']; ?>" />
    </div>
</div>

<?php include('settings_footer.php'); ?>
