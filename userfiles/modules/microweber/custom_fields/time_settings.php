<?php include('settings_header.php'); ?>


<div class="custom-field-settings-name">

    <div class="mw-custom-field-group ">
        <label class="mw-ui-label" for="input_field_label<?php print $rand; ?>">
            <?php _e('Title'); ?>
        </label>

        <input type="text" class="mw-ui-field mw-full-width" value="<?php print ($data['name']) ?>" name="name" id="input_field_label<?php print $rand; ?>">

    </div>
</div>

<div class="mw-custom-field-group">
    <label class="mw-ui-label" for="value<?php print $rand; ?>"><?php _e("Placeholder"); ?></label>
    <div id="mw-custom-fields-text-holder">
        <input type="text" class="mw-ui-field mw-full-width" name="placeholder" value="<?php echo $data['placeholder']; ?>" />
    </div>
</div>

<div class="custom-field-settings-values">

    
    <?php print $savebtn; ?>
</div>

<?php include('settings_footer.php'); ?>
