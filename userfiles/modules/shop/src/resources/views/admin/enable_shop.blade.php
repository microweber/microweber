<div>

    <script type="text/javascript">
        $(document).ready(function () {
            mw.options.form('.js-shop-module', function () {
                mw.notification.success("<?php _ejs("Saved"); ?>.");
                window.location.reload();
            });
        });
    </script>


    <?php

    $isShopDisabled = get_option('shop_disabled', 'website') == "y";
    ?>
    <div class="js-shop-module">
        <label class="form-check-label d-block"><?php _e("Enable Online shop"); ?></label>
        <label class="form-check form-switch">
            <input name="shop_disabled" class="form-check-input mw_option_field " data-option-group="website" data-value-checked="n" data-value-unchecked="y" type="checkbox" <?php if (!$isShopDisabled): ?> checked="checked" <?php endif; ?>>
        </label>
    </div>


</div>
