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
    <div class="mw-custom-field-group ">
        <label class="control-label"><?php _e("Value"); ?></label>
        <small class="text-muted d-block mb-2"><?php _e('This attribute specifies the value of description');?></small>
        <input
                class="form-control" type="text"
                placeholder="ex.: 001-8892345678"
                name="value"
                value="<?php if ($data['value'] == ''): ?>ex.: 001-8892345678<?php else : print $data['value'];endif; ?>"/>
    </div>

    <div class="mw-custom-field-group">
        <label class="control-label" for="value<?php print $rand; ?>"><?php _e("Placeholder"); ?></label>
        <small class="text-muted d-block mb-2"><?php _e('Specifies a short hint that describes the expected value of an input field');?></small>

        <div id="mw-custom-fields-text-holder">
            <input type="text" class="form-control" name="placeholder" value="<?php echo $data['placeholder']; ?>" />
        </div>
    </div>

    <label class="mw-ui-check"><input type="checkbox" class="mw-custom-field-option"
                                      name="options[required]" <?php if (isset($data['options']) == true and isset($data['options']["required"]) == true): ?> checked="checked" <?php endif; ?>
                                      value="1"><span></span><span><?php _e("Required"); ?>?</span></label>
    <?php print $savebtn; ?>
</div>
<?php include('settings_footer.php'); ?>
