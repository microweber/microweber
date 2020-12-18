<script type="text/javascript">
    $(document).ready(function () {
        mw.options.form('.<?php print $config['module_class'] ?>', function () {
            mw.notification.success("<?php _ejs("Saved"); ?>.");
        });
    });
</script>


<div id="shop-settings-accordion" class="mw-ui-box mw-ui-box-silver-blue active m-t-20">
    <div class="mw-ui-box-header" onclick="mw.accordion('#shop-settings-accordion');">
        <div class="header-holder">
            <i class="mai-shop"></i> <?php print _e('Shop settings'); ?>
        </div>
    </div>

    <div class="mw-accordion-content mw-ui-box-content">
        <div class="mw-ui-row">
            <div class="mw-ui-col">
                <div class="m-b-10">
                    <label class="mw-ui-label bold p-b-10"><?php _e("Users must agree to Terms and Conditions"); ?></label>

                    <div class="mw-ui-check-selector">
                        <label class="mw-ui-check" style="margin-right: 15px;">
                            <input name="shop_require_terms" class="mw_option_field" data-option-group="website" value="0" type="radio" <?php if (get_option('shop_require_terms', 'website') != 1): ?> checked="checked" <?php endif; ?> >
                            <span></span><span><?php _e("No"); ?></span>
                        </label>
                        <label class="mw-ui-check">
                            <input name="shop_require_terms" class="mw_option_field" data-option-group="website" value="1" type="radio" <?php if (get_option('shop_require_terms', 'website') == 1): ?> checked="checked" <?php endif; ?> >
                            <span></span><span><?php _e("Yes"); ?></span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="mw-ui-col">
                <div class="m-b-10">
                    <label class="mw-ui-label bold p-b-10"><?php _e("Purchasing requires registration"); ?></label>

                    <div class="mw-ui-check-selector">
                        <label class="mw-ui-check" style="margin-right: 15px;">
                            <input name="shop_require_registration" class="mw_option_field" data-option-group="website" value="0" type="radio" <?php if (get_option('shop_require_registration', 'website') != 1): ?> checked="checked" <?php endif; ?> >
                            <span></span><span><?php _e("No"); ?></span>
                        </label>
                        <label class="mw-ui-check">
                            <input name="shop_require_registration" class="mw_option_field" data-option-group="website" value="1" type="radio" <?php if (get_option('shop_require_registration', 'website') == 1): ?> checked="checked" <?php endif; ?> >
                            <span></span><span><?php _e("Yes"); ?></span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="mw-ui-col">
                <div class="m-b-10">

<module type="shop/orders/settings/enable_disable_shop" />


                </div>
            </div>
        </div>

        <script>
            $(document).ready(function () {
                $('.js-show-others').on('click', function () {
                    $('.js-others').toggleClass('hidden');
                });
            });
        </script>
        <button type="button" class="mw-ui-btn mw-ui-btn-small mw-ui-btn-info mw-ui-btn-outline js-show-others"><?php print _e('Advanced'); ?></button>
        <div class="mw-ui-row js-others hidden">
            <div class="m-b-10">
                <label class="mw-ui-label bold p-b-10"><?php _e("Checkout URL"); ?></label>
                <?php $checkout_url = get_option('checkout_url', 'shop'); ?>
                <input name="checkout_url" class="mw-ui-field mw_option_field block-field" type="text" option-group="shop" value="<?php print get_option('checkout_url', 'shop'); ?>" placeholder="<?php _e('Use default'); ?>"/>
            </div>

            <div class="m-b-10">
                <label class="mw-ui-label bold p-b-10"><?php _e("Custom order id"); ?></label>
                <input name="custom_order_id" class="mw-ui-field mw_option_field block-field" type="text" option-group="shop" value="<?php print get_option('custom_order_id', 'shop'); ?>" placeholder="ORD-{id}"/>
            </div>
        </div>
    </div>
</div>

<module type="shop/shipping/set_units" id="mw_set_shipping_units"/>

