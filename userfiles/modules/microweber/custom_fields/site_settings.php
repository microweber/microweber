<?php include('settings_header.php'); ?>

<div class="custom-field-settings-values mb-3">

    <label class="control-label" for="value<?php print $rand; ?>"><?php _e("Value"); ?></label>
    <small class="text-muted d-block mb-2"><?php _e('Ex. https://yourwebsite.com');?></small>
    <input type="text" class="form-control" name="value" value="<?php print ($data['value']) ?>" id="value<?php print $rand; ?>">

    <?php print $savebtn; ?>
</div>

<?php include('placeholder_settings.php'); ?>

<?php include('settings_footer.php'); ?>
