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
        <input class="form-control" type="text" placeholder="ex.: 001-8892345678" name="value" value="<?php if ($data['value'] == ''): ?><?php else : print $data['value'];endif; ?>"/> 
    </div>

    <?php print $savebtn; ?>
</div>


<?php include('placeholder_settings.php'); ?>
<?php include('settings_footer.php'); ?>
