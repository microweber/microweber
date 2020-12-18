<?php include('settings_header.php'); ?>

<div class="custom-field-settings-name">

    <div class="mw-custom-field-group ">
        <label class="mw-ui-label" for="input_field_label<?php print $rand; ?>">
            <?php _e('Title'); ?>
        </label>

        <input type="text" class="mw-ui-field mw-full-width" value="<?php print ($data['name']) ?>" name="name" id="input_field_label<?php print $rand; ?>">

    </div>
</div>


<div class="custom-field-settings-values">

    <label class="mw-ui-label"><?php _e("Value"); ?></label>


    <input
            class="mw-ui-field mw-full-width" type="text"
            placeholder="ex.: 001-8892345678"
            name="value"
            value="<?php if ($data['value'] == ''): ?>ex.: 001-8892345678<?php else : print $data['value'];endif; ?>"/>
    <br/>
    <br/>


    <div class="mw-custom-field-group">
        <label class="mw-ui-label" for="value<?php print $rand; ?>"><?php _e("Placeholder"); ?></label>
        <div id="mw-custom-fields-text-holder">
            <input type="text" class="mw-ui-field mw-full-width" name="placeholder" value="<?php echo $data['placeholder']; ?>" />
        </div>
    </div>




    <label class="mw-ui-check"><input type="checkbox" class="mw-custom-field-option"
                                      name="options[required]" <?php if (isset($data['options']) == true and isset($data['options']["required"]) == true): ?> checked="checked" <?php endif; ?>
                                      value="1"><span></span><span><?php _e("Required"); ?>?</span></label>
   <br/>
   <br/>
    <?php print $savebtn; ?>
</div>
<?php include('settings_footer.php'); ?>
