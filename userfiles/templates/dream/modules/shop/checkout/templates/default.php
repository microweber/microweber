<?php

/*

type: layout

name: Checkout

description: Checkout

*/

?>

<div>
    <?php if ($requires_registration and is_logged() == false): ?>
        <module type="users/register"/>
    <?php else: ?>
        <div class="clear"></div>
        <?php if ($payment_success == false): ?>
            <form class="mw-checkout-form" id="checkout_form_<?php print $params['id'] ?>" method="post"
                  action="<?php print api_link('checkout') ?>">
                <div class="alert hide"></div>


                <?php $cart_show_enanbled = get_option('data-show-cart', $params['id']); ?>
                <div <?php if ($step != 1): ?>style="display: none;"<?php endif; ?>>
                    <?php if ($cart_show_enanbled != 'n'): ?>
                        <div class="mw-cart-data-holder">
                            <module type="shop/cart" template="skin-1" id="cart_checkout_<?php print $params['id'] ?>"
                                    data-checkout-link-enabled="n"/>

                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="boxed boxed--border order-charges">
                                        <ul>
                                            <li>
                                                <span class="h5"><?php _e("Shipping to"); ?>:</span>
                                                <span><module type="shop/shipping" view="select"/></span>
                                            </li>


                                            <li>
                                                <span class="h5"><?php _e("Shipping price"); ?>:</span>
                                                <span><module type="shop/shipping" view="cost"/></span>
                                            </li>


                                            <?php if ($cart_totals) { ?>
                                                <?php foreach ($cart_totals as $cart_total_key => $cart_total_item) { ?>
                                                    <?php if ($cart_total_key != 'shipping') { ?>

                                                        <li>
                                                            <span class="h5"><?php print  $cart_total_item['label'] ?>:</span>
                                                            <span><?php print  $cart_total_item['amount'] ?></span>

                                                        </li>
                                                    <?php } ?>
                                                <?php } ?>
                                            <?php } ?>


                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php endif; ?>

                    <div class="row">
                        <div class="col-xs-12">
                            <a href="?step=2" class="btn"><?php _e("PROCEED TO SHIPMENT"); ?></a>
                        </div>
                    </div>
                </div>


                <div class="mw-cart-data-holder shop-checkout">
                    <div <?php if ($step != 2): ?>style="display: none;"<?php endif; ?>>

                        <div class="col-lg-8 col-sm-8 billing-details">
                            <div class="row">
                                <div class="col-sm-12">
                                    <h6 class="edit nodrop" field="checkout_personal_inforomation_title" rel="global"
                                        rel_id="<?php print $params['id'] ?>"><?php _e("Personal Information"); ?></h6>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-12 col-md-6">
                                    <input name="first_name" type="text" value="<?php if (isset($user['first_name'])) {
                                        print $user['first_name'];
                                    } ?>" placeholder="<?php _e("First Name"); ?>"/>
                                </div>
                                <div class="col-xs-12 col-md-6">
                                    <input name="last_name" type="text" value="<?php if (isset($user['last_name'])) {
                                        print $user['last_name'];
                                    } ?>" placeholder="<?php _e("Last Name"); ?>"/>
                                </div>

                                <div class="col-xs-12 col-md-6">
                                    <input name="email" type="text" value="<?php if (isset($user['email'])) {
                                        print $user['email'];
                                    } ?>" placeholder="<?php _e("Email"); ?>"/>
                                </div>

                                <div class="col-xs-12 col-md-6">
                                    <input name="phone" type="text" value="<?php if (isset($user['phone'])) {
                                        print $user['phone'];
                                    } ?>" placeholder="<?php _e("Phone"); ?>"/>
                                </div>
                            </div>

                            <?php if ($cart_show_shipping != 'n'): ?>
                                <div class="mw-shipping-and-payment">
                                    <module type="shop/shipping"/>
                                </div>
                            <?php endif; ?>

                            <module type="shop/checkout/terms"/>
                        </div>

                        <div class="col-lg-4 col-sm-4">
                            <?php if ($cart_show_payments != 'n'): ?>
                                <div class="mw-shipping-and-payment">
                                    <module type="shop/payments"/>
                                </div>
                            <?php endif; ?>
                        </div>


                        <div class="col-xs-12">
                            <a href="?step=1" class="btn pull-left">
                                <?php _e("Go back to cart"); ?>
                            </a>

                            <button class="btn pull-right"
                                    onclick="mw.cart.checkout('#checkout_form_<?php print $params['id'] ?>');"
                                    type="button"
                                    id="complete_order_button" <?php if ($tems): ?> disabled="disabled"   <?php endif; ?>>
                                <?php _e("Complete order"); ?>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="mw-checkout-responce"></div>
            </form>

        <?php else: ?>
            <h2>
                <?php _e("Your payment was successfull."); ?>
            </h2>
        <?php endif; ?>
    <?php endif; ?>
</div>