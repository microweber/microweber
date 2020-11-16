<?php

/*

type: layout

name: Default

description: Default cart template

*/

?>
<?php if ($requires_registration and is_logged() == false): ?>
    <module type="users/register"/>
<?php else: ?>
    <?php if ($payment_success == false): ?>

        <form class="mw-checkout-form" id="checkout_form_<?php print $params['id'] ?>" method="post"
              action="<?php print api_link('checkout') ?>">
            <?php $cart_show_enanbled = get_option('data-show-cart', $params['id']); ?>
            <?php if ($cart_show_enanbled != 'n'): ?>
                <br/>
                <module type="shop/cart" template="big" id="cart_checkout_<?php print $params['id'] ?>"
                        data-checkout-link-enabled="n"/>
            <?php endif; ?>


            <?php include(__DIR__ . '/partials/shipping-and-payment.php'); ?>

            <div class="alert hide"></div>
            <div class="mw-cart-action-holder">
                <hr/>

                <module type="shop/checkout/terms"/>
                <br/>

                <?php $shop_page = get_content('is_shop=1'); ?>
                <button class="btn btn-warning pull-right mw-checkout-btn"
                        onclick="mw.cart.checkout('#checkout_form_<?php print $params['id'] ?>');"
                        type="button"
                        id="complete_order_button" <?php if ($terms): ?> disabled="disabled"   <?php endif; ?>>
                    <?php _e("Complete order"); ?>
                </button>


                <?php if (is_array($shop_page)): ?>
                    <a href="<?php print page_link($shop_page[0]['id']); ?>" class="btn btn-default pull-left"
                       type="button">
                        <?php _e("Continue Shopping"); ?>
                    </a>
                <?php endif; ?>


                <?php if (is_module('shop/coupons')): ?>

                    &nbsp;  <a class="btn btn-default" onclick="mw.tools.open_module_modal('shop/coupons');" href="javascript:;">Discounts </a>

                <?php endif; ?>

                <div class="clear"></div>


            </div>
        </form>
        <div class="mw-checkout-responce"></div>
    <?php else: ?>
        <h2>
            <?php _e("Your payment was successfull."); ?>
        </h2>
    <?php endif; ?>
<?php endif; ?>