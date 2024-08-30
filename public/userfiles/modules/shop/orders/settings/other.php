<?php
$from_live_edit = false;
if (isset($params["live_edit"]) and $params["live_edit"]) {
    $from_live_edit = $params["live_edit"];
}
?>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<h1 class="main-pages-title"><?php _e('Other settings'); ?></h1>


<div class="card mb-7">
    <div class="card-body mb-3 <?php if ($from_live_edit): ?>card-in-live-edit<?php endif; ?>">
        <div class="row">

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

            <div class="col-xl-3 mb-xl-0 mb-3">
                <h5 class="font-weight-bold settings-title-inside"><?php _e('Shop settings'); ?></h5>
                <small class="text-muted"><?php _e('More important settings to your online store'); ?>.</small>
            </div>
            <div class="col-xl-9">

                <div class="card bg-azure-lt ">
                    <div class="card-body ">
                        <div class="row">
                            <div class="form-group">
                                <label class="form-label d-block"><?php _e("Terms and Conditions"); ?></label>
                                <small class="text-muted"><?php _e("User must agree with terms or conditions"); ?>.</small>
                                <br>

                                <div class="custom-control custom-radio d-inline-block mt-2 me-2">
                                    <input name="shop_require_terms" class="mw_option_field form-check-input" id="shop_require_terms_0" data-option-group="website" value="0" type="radio" <?php if (get_option('shop_require_terms', 'website') != 1): ?> checked="checked" <?php endif; ?> >
                                    <label class="custom-control-label" for="shop_require_terms_0"><?php _e("No"); ?></label>
                                </div>

                                <div class="custom-control custom-radio d-inline-block">
                                    <input name="shop_require_terms" class="mw_option_field form-check-input" id="shop_require_terms_1" data-option-group="website" value="1" type="radio" <?php if (get_option('shop_require_terms', 'website') == 1): ?> checked="checked" <?php endif; ?> >

                                    <label class="custom-control-label" for="shop_require_terms_1"><?php _e("Yes"); ?></label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label d-block"><?php _e("Purchasing requires registration"); ?></label>
                                <small class="text-muted"><?php _e("Do the user need to have registration to make a order"); ?>?</small>
                                <br>


                                <div class="custom-control custom-radio d-inline-block me-2 mt-2">
                                    <input name="shop_require_registration" class="mw_option_field form-check-input" id="shop_require_registration_0" data-option-group="website" value="0" type="radio" <?php if (get_option('shop_require_registration', 'website') != 1): ?> checked="checked" <?php endif; ?> >
                                    <label class="custom-control-label" for="shop_require_registration_0"><?php _e("No"); ?></label>
                                </div>

                                <div class="custom-control custom-radio d-inline-block me-2">
                                    <input name="shop_require_registration" class="mw_option_field form-check-input" id="shop_require_registration_1" data-option-group="website" value="1" type="radio" <?php if (get_option('shop_require_registration', 'website') == 1): ?> checked="checked" <?php endif; ?> >
                                    <label class="custom-control-label" for="shop_require_registration_1"><?php _e("Yes"); ?></label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label d-block"><?php _e("Require fields for checkout"); ?></label>
                                <small class="text-muted"><?php _e("Select which fields are required when user is on checkout page"); ?>.</small>


                                <div class="custom-control custom-checkbox my-2">
                                    <input type="checkbox" name="shop_require_first_name" data-option-group="website" value="1" class="mw_option_field  form-check-input" id="check-shop_require_first_name" <?php if (get_option('shop_require_first_name', 'website') == 1): ?> checked="checked" <?php endif; ?>>
                                    <label class="custom-control-label" for="check-shop_require_first_name"></label> <?php _e("First Name"); ?>
                                </div>

                                <div class="custom-control custom-checkbox my-2">
                                    <input type="checkbox" name="shop_require_last_name" data-option-group="website" value="1" class="mw_option_field  form-check-input" id="check-shop_require_last_name" <?php if (get_option('shop_require_last_name', 'website') == 1): ?> checked="checked" <?php endif; ?>>
                                    <label class="custom-control-label" for="check-shop_require_last_name"></label> <?php _e("Last Name"); ?>
                                </div>

                                <div class="custom-control custom-checkbox my-2">
                                    <input type="checkbox" name="shop_require_email" data-option-group="website" value="1" class="mw_option_field  form-check-input" id="check-shop_require_email" <?php if (get_option('shop_require_email', 'website') == 1): ?> checked="checked" <?php endif; ?>>
                                    <label class="custom-control-label" for="check-shop_require_email"></label> <?php _e("Email"); ?>
                                </div>


                                <div class="custom-control custom-checkbox my-2">
                                    <input type="checkbox" name="shop_require_phone" data-option-group="website" value="1" class="mw_option_field  form-check-input" id="check-shop_require_phone" <?php if (get_option('shop_require_phone', 'website') == 1): ?> checked="checked" <?php endif; ?>>
                                    <label class="custom-control-label" for="check-shop_require_phone"></label> <?php _e("Phone"); ?>
                                </div>

                            </div>

<!--                            <module type="shop/orders/settings/enable_disable_shop"/>-->


                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>


<div class="card mb-7">
    <div class="card-body mb-3">
        <div class="row">

            <div class="col-xl-3 mb-xl-0 mb-3">
                <h5 class="font-weight-bold settings-title-inside"><?php _e('Shop unit settings'); ?></h5>
                <small class="text-muted"><?php _e('Choose the right units that your shop supports'); ?>.</small>
            </div>
            <div class="col-xl-9">

                <div class="card bg-azure-lt ">
                    <div class="card-body ">
                        <div class="row">
                            <module type="shop/shipping/set_units" id="mw_set_shipping_units"/>







                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>


<div class="card mb-7">
    <div class="card-body mb-3">
        <div class="row">

            <div class="col-xl-3 mb-xl-0 mb-3">
                <h5 class="font-weight-bold settings-title-inside"><?php _e('Custom settings'); ?></h5>
                <small class="text-muted"><?php _e('Define custom settings to your shop'); ?>.</small>
            </div>
            <div class="col-xl-9">

                <div class="card bg-azure-lt ">
                    <div class="card-body ">
                        <div class="row">
                            <div class="form-group">
                                <label class="form-label"><?php _e("Checkout URL"); ?></label>
                                <small class="text-muted mb-3 d-block"><?php _e('Specify a different than the default checkout page URL'); ?>.</small>


                                <?php $checkout_url = get_option('checkout_url', 'shop'); ?>
                                <input name="checkout_url" class="mw_option_field form-control" type="text" option-group="shop" value="<?php print get_option('checkout_url', 'shop'); ?>" placeholder="<?php _e('Use default'); ?>"/>
                            </div>

                            <div class="form-group">
                                <label class="form-label"><?php _e("Custom order id"); ?></label>
                                <small class="text-muted mb-3 d-block"><?php _e('Define, if necessary, how to spell your order numbers'); ?>.</small>

                                <input name="custom_order_id" class="mw_option_field form-control" type="text" option-group="shop" value="<?php print get_option('custom_order_id', 'shop'); ?>" placeholder="ORD-{id}"/>
                            </div>

                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

