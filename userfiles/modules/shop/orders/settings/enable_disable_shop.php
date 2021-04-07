<script type="text/javascript">
    $(document).ready(function () {
        mw.options.form('.<?php print $config['module_class'] ?>', function () {
            mw.notification.success("<?php _ejs("Saved"); ?>.");
        });
    });
</script>

<div class="form-group">
    <label class="control-label d-block"><?php _e("Online shop status"); ?></label>
    <small class="text-muted d-block mb-2"><?php _e("Choose the status of your online shop"); ?></small>
    <div class="custom-control custom-radio d-inline-block mr-2">
        <input name="shop_disabled" class="mw_option_field custom-control-input" id="shop_disabled_0" data-option-group="website" value="n" type="radio" <?php if (get_option('shop_disabled', 'website') != "y"): ?> checked="checked" <?php endif; ?> >
        <label class="custom-control-label" for="shop_disabled_0"><?php _e("Enable"); ?></label>
    </div>

    <div class="custom-control custom-radio d-inline-block mr-2">
        <input name="shop_disabled" class="mw_option_field custom-control-input" id="shop_disabled_1" data-option-group="website" value="y" type="radio" <?php if (get_option('shop_disabled', 'website') == "y"): ?> checked="checked" <?php endif; ?> >
        <label class="custom-control-label" for="shop_disabled_1"><?php _e("Disable"); ?></label>
    </div>
</div>