<?php
$from_live_edit = false;
if (isset($params["live_edit"]) and $params["live_edit"]) {
    $from_live_edit = $params["live_edit"];
}
?>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<div class="card style-1 mb-3 <?php if ($from_live_edit): ?>card-in-live-edit<?php endif; ?>">
    <div class="card-header">
        <module type="admin/modules/info_module_title" for-module="<?php print $params['module'] ?>"/>
    </div>

    <div class="card-body pt-3">
        <script type="text/javascript">
            $(document).ready(function () {
                mw.options.form('.<?php print $config['module_class'] ?>', function () {
                    mw.notification.success("<?php _ejs("Saved"); ?>.");
                });

                $('.js-show-others').on('click', function () {
                    $('.js-others').toggleClass('d-none');
                });
            });
        </script>

        <h5 class="font-weight-bold"><?php _e('Shop settings'); ?></h5>

        <div class="form-group">
            <label class="control-label d-block"><?php _e("Users must agree to Terms and Conditions"); ?></label>

            <div class="custom-control custom-radio d-inline-block mr-2">
                <input name="shop_require_terms" class="mw_option_field custom-control-input" id="shop_require_terms_0" data-option-group="website" value="0" type="radio" <?php if (get_option('shop_require_terms', 'website') != 1): ?> checked="checked" <?php endif; ?> >
                <label class="custom-control-label" for="shop_require_terms_0"><?php _e("No"); ?></label>
            </div>

            <div class="custom-control custom-radio d-inline-block">
                <input name="shop_require_terms" class="mw_option_field custom-control-input" id="shop_require_terms_1" data-option-group="website" value="1" type="radio" <?php if (get_option('shop_require_terms', 'website') == 1): ?> checked="checked" <?php endif; ?> >

                <label class="custom-control-label" for="shop_require_terms_1"><?php _e("Yes"); ?></label>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label d-block"><?php _e("Purchasing requires registration"); ?></label>

            <div class="custom-control custom-radio d-inline-block mr-2">
                <input name="shop_require_registration" class="mw_option_field custom-control-input" id="shop_require_registration_0" data-option-group="website" value="0" type="radio" <?php if (get_option('shop_require_registration', 'website') != 1): ?> checked="checked" <?php endif; ?> >
                <label class="custom-control-label" for="shop_require_registration_0"><?php _e("No"); ?></label>
            </div>

            <div class="custom-control custom-radio d-inline-block mr-2">
                <input name="shop_require_registration" class="mw_option_field custom-control-input" id="shop_require_registration_1" data-option-group="website" value="1" type="radio" <?php if (get_option('shop_require_registration', 'website') == 1): ?> checked="checked" <?php endif; ?> >
                <label class="custom-control-label" for="shop_require_registration_1"><?php _e("Yes"); ?></label>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label d-block"><?php _e("Require fields for checkout"); ?></label>

            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="shop_require_first_name" data-option-group="website" value="1" class="mw_option_field  custom-control-input" id="check-shop_require_first_name" <?php if (get_option('shop_require_first_name', 'website') == 1): ?> checked="checked" <?php endif; ?>>
                <label class="custom-control-label" for="check-shop_require_first_name"></label> <?php _e("First Name"); ?>
            </div>

            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="shop_require_last_name" data-option-group="website" value="1" class="mw_option_field  custom-control-input" id="check-shop_require_last_name" <?php if (get_option('shop_require_last_name', 'website') == 1): ?> checked="checked" <?php endif; ?>>
                <label class="custom-control-label" for="check-shop_require_last_name"></label> <?php _e("Last Name"); ?>
            </div>

            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="shop_require_email" data-option-group="website" value="1" class="mw_option_field  custom-control-input" id="check-shop_require_email" <?php if (get_option('shop_require_email', 'website') == 1): ?> checked="checked" <?php endif; ?>>
                <label class="custom-control-label" for="check-shop_require_email"></label> <?php _e("Email"); ?>
            </div>


 <div class="custom-control custom-checkbox">
                <input type="checkbox" name="shop_require_phone" data-option-group="website" value="1" class="mw_option_field  custom-control-input" id="check-shop_require_phone" <?php if (get_option('shop_require_phone', 'website') == 1): ?> checked="checked" <?php endif; ?>>
                <label class="custom-control-label" for="check-shop_require_phone"></label> <?php _e("Phone"); ?>
            </div>

        </div>

        <module type="shop/orders/settings/enable_disable_shop"/>

        <div class="my-3">
            <button type="button" class="btn btn-outline-primary btn-sm js-show-others"><?php _e('Advanced'); ?></button>
        </div>

        <div class="js-others d-none">
            <div class="form-group">
                <label class="control-label"><?php _e("Checkout URL"); ?></label>
                <?php $checkout_url = get_option('checkout_url', 'shop'); ?>
                <input name="checkout_url" class="mw_option_field form-control" type="text" option-group="shop" value="<?php print get_option('checkout_url', 'shop'); ?>" placeholder="<?php _e('Use default'); ?>"/>
            </div>

            <div class="form-group">
                <label class="control-label"><?php _e("Custom order id"); ?></label>
                <input name="custom_order_id" class="mw_option_field form-control" type="text" option-group="shop" value="<?php print get_option('custom_order_id', 'shop'); ?>" placeholder="ORD-{id}"/>
            </div>
        </div>

        <hr class="thin"/>

        <module type="shop/shipping/set_units" id="mw_set_shipping_units"/>
    </div>
</div>

