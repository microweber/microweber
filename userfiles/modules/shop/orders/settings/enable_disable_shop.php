<script type="text/javascript">
    $(document).ready(function () {
        mw.options.form('.<?php print $config['module_class'] ?>', function () {
            mw.notification.success("<?php _ejs("Saved"); ?>.");
        });
    });
</script>

<div class="">
    <label class="form-check-label d-block"><?php _e("Online shop status"); ?></label>
    <small class="text-muted d-block mb-2"><?php _e("Choose the status of your online shop"); ?></small>
    <label class="form-check form-switch">
        <input name="shop_disabled" class="form-check-input mw_option_field " data-option-group="website" data-value-checked="n" data-value-unchecked="y" type="checkbox" <?php if (get_option('shop_disabled', 'website') == "n"): ?> checked="checked" <?php endif; ?>>
    </label>
</div>


