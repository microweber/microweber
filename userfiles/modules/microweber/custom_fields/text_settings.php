<?php include('settings_header.php'); ?>

<style>
    #mw-custom-fields-text-holder textarea{
        resize:both;
    }
</style>
<div class="custom-field-settings-name">
    <label class="control-label" for="input_field_label<?php print $rand; ?>"><?php _e('Title'); ?></label>
    <small class="text-muted d-block mb-2"><?php _e('Title label of the field');?></small>
    <input type="text" onkeyup="" class="form-control" value="<?php echo $data['name']; ?>" name="name" id="input_field_label<?php print $rand; ?>">
</div>
<!--
<div class="mw-ui-field-holder">
    <label class="mw-ui-check"><input type="checkbox"  class="mw-custom-field-option" name="options[as_text_area]"  <?php /*if($settings["as_text_area"]): */?> checked="checked" <?php /*endif; */?> value="1" id="mw-custom-fields-text-switch"><span></span><span><?php /*_e("Use as Text Area"); */?></span></label>
</div>-->

<div class="custom-field-settings-values">
    <div class="mw-custom-field-group">
        <label class="control-label" for="value<?php echo $rand; ?>"><?php _e("Value"); ?></label>
        <small class="text-muted d-block mb-2"><?php _e('This attribute specifies the value of description');?></small>
        <div id="mw-custom-fields-text-holder">
            <textarea class="form-control" name="value"><?php echo $data['value']; ?></textarea>
        </div>
    </div>
    <?php print $savebtn; ?>
</div>

<?php include('placeholder_settings.php'); ?>

<?php include('settings_footer.php'); ?>
