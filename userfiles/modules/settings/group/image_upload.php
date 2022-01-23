<?php must_have_access(); ?>
<script type="text/javascript">
    $(document).ready(function () {

        mw.options.form('.<?php print $config['module_class'] ?>', function () {
            mw.notification.success("<?php _ejs("All changes are saved"); ?>.");
        });
    });
</script>

<?php

if (isset($params['show_description_text']) and $params['show_description_text']): ?>
    <h3><?php _e("Looks like you are trying to upload big images"); ?></h3>
    <h4><?php _e("For best experience you may want to enable the Automatic image resize"); ?></h4>
<?php endif; ?>

<div class="form-group">
    <label class="control-label d-block"><?php _e("Enable automatic image resize on upload?"); ?></label>
    <?php $automatic_image_resize_on_upload = get_option('automatic_image_resize_on_upload', 'website'); ?>

    <div class="custom-control custom-radio d-inline-block mr-2">
        <input type="radio" id="img_resize_choice1" class="mw_option_field custom-control-input" name="automatic_image_resize_on_upload" <?php if ($automatic_image_resize_on_upload == 'y'): ?> checked <?php endif; ?> value="y" option-group="website">
        <label class="custom-control-label" for="img_resize_choice1"><?php _e("Yes"); ?></label>
    </div>

    <div class="custom-control custom-radio d-inline-block mr-2">
        <input type="radio" id="img_resize_choice2" class="mw_option_field custom-control-input" name="automatic_image_resize_on_upload" <?php if (!$automatic_image_resize_on_upload or $automatic_image_resize_on_upload == 'n'): ?> checked <?php endif; ?> value="n" option-group="website">
        <label class="custom-control-label" for="img_resize_choice2"><?php _e("No"); ?></label>
    </div>

    <div class="custom-control custom-radio d-inline-block mr-2">
        <input type="radio" id="img_resize_choice3" class="mw_option_field custom-control-input" name="automatic_image_resize_on_upload" <?php if ($automatic_image_resize_on_upload == 'd'): ?> checked <?php endif; ?> value="d" option-group="website">
        <label class="custom-control-label" for="img_resize_choice3"><?php _e("Disable notification"); ?></label>
    </div>
</div>