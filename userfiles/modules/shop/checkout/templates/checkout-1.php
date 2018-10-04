<?php

/*

type: layout

name: Checkout 1

description: Checkout 1 cart template

*/

?>

<div class="mw-shopping-cart-checkout-layout-1">
    <?php if ($requires_registration and is_logged() == false): ?>
        <module type="users/register"/>
    <?php else: ?>

        <?php if ($payment_success == false): ?>

            <form class="mw-checkout-form" id="checkout_form_<?php print $params['id'] ?>" method="post" action="<?php print api_link('checkout') ?>">
                <?php $cart_show_enanbled = get_option('data-show-cart', $params['id']); ?>
                <div <?php if ($step != 1): ?>style="display: none;"<?php endif; ?>>
                    <?php if ($cart_show_enanbled != 'n'): ?>
                        <module type="shop/cart" template="big-1" id="cart_checkout_<?php print $params['id'] ?>" data-checkout-link-enabled="n"/>
                    <?php endif; ?>

                    <a href="?step=2" class="btn btn-default pull-right">
                        <?php _e("Proceed to shipment"); ?>
                    </a>
                </div>
                <div <?php if ($step != 2): ?>style="display: none;"<?php endif; ?>>
                    <div class="mw-shipping-and-payment">
                        <div class="row">
                            <div class="col-xs-12">
                                <h4 class="edit nodrop" field="checkout_personal_inforomation_title" rel="global"
                                    rel_id="<?php print $params['id'] ?>"><?php _e("Personal Information"); ?></h4>
                                <hr/>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12 col-md-5">
                                <div class="form-group">
                                    <label for="first_name" class="col-xs-12 col-sm-6 col-md-4 control-label"><?php _e("First Name"); ?></label>
                                    <div class="col-xs-12 col-sm-6 col-md-8">
                                        <input name="first_name" class="form-control" type="text" value="<?php if (isset($user['first_name'])) {
                                            print $user['first_name'];
                                        } ?>"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-5">
                                <div class="form-group">
                                    <label for="last_name" class="col-xs-12 col-sm-6 col-md-4 control-label"><?php _e("Last Name"); ?></label>
                                    <div class="col-xs-12 col-sm-6 col-md-8">
                                        <input name="last_name" class="form-control" type="text" value="<?php if (isset($user['last_name'])) {
                                            print $user['last_name'];
                                        } ?>"/>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-md-5">
                                <div class="form-group">
                                    <label for="email" class="col-xs-12 col-sm-6 col-md-4 control-label"><?php _e("Email"); ?></label>
                                    <div class="col-xs-12 col-sm-6 col-md-8">
                                        <input name="email" class="form-control" type="text" value="<?php if (isset($user['email'])) {
                                            print $user['email'];
                                        } ?>"/>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-md-5">
                                <div class="form-group">
                                    <label for="last_name" class="col-xs-12 col-sm-6 col-md-4 control-label"><?php _e("Phone"); ?></label>
                                    <div class="col-xs-12 col-sm-6 col-md-8">
                                        <input name="phone" class="form-control" type="text" value="<?php if (isset($user['phone'])) {
                                            print $user['phone'];
                                        } ?>"/>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php if ($cart_show_shipping != 'n'): ?>
                            <div class="mw-shipping-and-payment">
                                <module type="shop/shipping" template="shipping-1"/>
                            </div>
                        <?php endif; ?>
                        <?php if ($cart_show_payments != 'n'): ?>
                            <div class="mw-shipping-and-payment">
                                <module type="shop/payments" template="payments-1"/>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php if ($tems): ?>
                        <script>
                            $(document).ready(function () {
                                $('#i_agree_with_terms_row').click(function () {
                                    var el = $('#i_agree_with_terms');
                                    if (el.is(':checked')) {
                                        $('#complete_order_button').removeAttr('disabled');
                                    } else {
                                        $('#complete_order_button').attr('disabled', 'disabled');

                                    }
                                });
                            });
                        </script>


                        <div class="mw-ui-row" id="i_agree_with_terms_row">
                            <label class="mw-ui-check">
                                <input type="checkbox" name="terms" id="i_agree_with_terms" value="1" autocomplete="off"/>
                                <span class="edit" field="i_agree_with_terms_text" rel="shop_checkout">
                                    <?php _e('I agree with'); ?>
                                    <a href="<?php print site_url('tems') ?>" target="_blank"><?php _e('terms and conditions'); ?></a>

                                </span>
                            </label>
                        </div>
                    <?php endif; ?>

                    <a href="?step=1" class="btn btn-default pull-left">
                        <?php _e("Go back to cart"); ?>
                    </a>

                    <button class="btn btn-default pull-right" onclick="mw.cart.checkout('#checkout_form_<?php print $params['id'] ?>');" type="button"
                            id="complete_order_button" <?php if ($tems): ?> disabled="disabled"   <?php endif; ?>>
                        <?php _e("Complete order"); ?>
                    </button>
                </div>

                <div class="alert hide"></div>
                <div class="clear"></div>
            </form>
            <div class="mw-checkout-responce"></div>
        <?php else: ?>
            <h2>
                <?php _e("Your payment was successfull."); ?>
            </h2>
        <?php endif; ?>
    <?php endif; ?>
</div>