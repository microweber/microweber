<script type="text/javascript">
    $(document).ready(function () {
        mw.options.form('.<?php print $config['module_class'] ?>', function () {
            mw.notification.success("<?php _ejs("Saved"); ?>.");
        });
    });
</script>

<label class="mw-ui-label bold p-b-10"><?php _e("Disable online shop"); ?></label>

<div class="mw-ui-check-selector">
    <label class="mw-ui-check" style="margin-right: 15px;">
        <input name="shop_disabled" class="mw_option_field" data-option-group="website" value="n" type="radio" <?php if (get_option('shop_disabled', 'website') != "y"): ?> checked="checked" <?php endif; ?> >
        <span></span><span><?php _e("No"); ?></span>
    </label>
    <label class="mw-ui-check">
        <input name="shop_disabled" class="mw_option_field" data-option-group="website" value="y" type="radio" <?php if (get_option('shop_disabled', 'website') == "y"): ?> checked="checked" <?php endif; ?> >
        <span></span> <span><?php _e("Yes"); ?></span>
    </label>
</div>