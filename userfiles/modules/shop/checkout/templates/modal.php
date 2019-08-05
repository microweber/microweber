<?php

/*

type: layout

name: Checkout

description: Checkout

*/


?>

<script type="text/javascript">
    mw.require("<?php print modules_url(); ?>shop/checkout/styles.css", true);
</script>

<div class="checkout-modal" id="checkout_modal_<?php print $params['id'] ?>">
    <div>
        <?php if ($requires_registration and is_logged() == false): ?>

            <script>
                $(document).ready(function () {

                    if (!!$.fn.selectpicker) {
                        $('#loginModal').modal();
                    }

                })
            </script>


            <a></a>


        <?php else: ?>
            <div class="clear"></div>


            <form class="mw-checkout-form" id="checkout_form_<?php print $params['id'] ?>" method="post">


                <div class="modal-content">
                    <div class="modal-header">



                        <?php if(!isset($params['no-close-btn'])) { ?>

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>

                        <?php } ?>



                        <div class="row w-100">
                            <div class="col-3 step-button">
                                <a href="#" class="js-show-step" data-step="shopping-cart">
                                    <i class="material-icons">shopping_cart</i>



                                    <span><?php _e("Shopping Cart"); ?> </span>

                                 </a>
                            </div>
                            <div class="col-3 step-button muted">
                                <a href="#" class="js-show-step" data-step="delivery-address">
                                    <i class="material-icons">local_shipping</i>
                                     <span><?php _e("Delivery Address"); ?> </span>



                                 </a>
                            </div>
                            <div class="col-3 step-button muted">
                                <a href="#" class="js-show-step" data-step="payment-method">
                                    <i class="material-icons">payment</i>
                                    <span><?php _e("Payment Method"); ?> </span>
                                </a>
                            </div>
                            <div class="col-3 step-button muted">
                                <a href="#" class="js-show-step" data-step="checkout-complete">
                                    <i class="material-icons">remove_red_eye</i>
                                    <span><?php _e("Complete"); ?></span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="js-step-content js-shopping-cart">



                            <?php $cart_show_enanbled = get_option('data-show-cart', $params['id']); ?>
                            <?php if ($cart_show_enanbled != 'n'): ?>
                                <br/>
                                <module type="shop/cart" template="modal" data-checkout-link-enabled="n"
                                        id="cart_checkout_<?php print $params['id'] ?>"/>

                            <?php endif; ?>


                        </div>
                        <div class="js-step-content js-delivery-address">


                            <div class="m-t-20 edit nodrop" field="checkout_personal_information_title" rel="global"
                                 rel_id="<?php print $params['id'] ?>">
                                <div class="pull-right red">* <?php _e("All fields are required"); ?></div>
                                <p class="bold m-b-10"><?php _e("Personal Information"); ?></p>
                            </div>


                            <div class="row">
                                <div class="col-6">

                                    <div class="field-holder">
                                        <input name="first_name" class="form-control input-lg" type="text" value=""
                                               placeholder="<?php _e("First Name"); ?>"/>
                                    </div>

                                </div>
                                <div class="col-6">
                                    <div class="field-holder">
                                        <input name="last_name" class="form-control input-lg" type="text" value=""
                                               placeholder="<?php _e("Last Name"); ?>"/>
                                    </div>
                                </div>

                            </div>


                            <div class="row">
                                <div class="col-6">

                                    <div class="field-holder">
                                        <input name="email" class="form-control input-lg" type="text" value=""
                                               placeholder="<?php _e("Email"); ?>" required/>
                                    </div>

                                </div>
                                <div class="col-6">
                                    <div class="field-holder">
                                        <input name="phone" class="form-control input-lg" type="text" value=""
                                               placeholder="<?php _e("Phone"); ?>"/>
                                    </div>
                                </div>

                            </div>


                            <module type="shop/shipping" data-store-values="true" template="modal"/>

                            <div class="m-t-10">
                                <a href="#" class="btn btn-default btn-lg btn-block js-show-step"
                                   data-step="payment-method"><?php _e("Continue"); ?></a>
                            </div>


                        </div>

                        <div class="js-step-content js-payment-method">

                            <div class="m-t-20">


                                <module type="shop/payments" data-store-values="true" template="modal"/>


                                <div class="mw-cart-action-holder">


                                    <hr/>

                                    <module type="shop/checkout/terms"/>

                                    <?php $shop_page = get_content('is_shop=1'); ?>
                                    <button class="btn btn-default btn-lg btn-block"
                                            onclick="mw.cart.checkout('#checkout_form_<?php print $params['id'] ?>');"
                                            type="button"
                                            id="complete_order_button" <?php if ($terms): ?> disabled="disabled"   <?php endif; ?>>
                                        <?php _e("Complete order"); ?>
                                    </button>
                                    <?php if (is_array($shop_page)): ?>
                                        <?php

                                        /*<a href="<?php print page_link($shop_page[0]['id']); ?>" class="btn btn-default pull-left"
                                           type="button">
                                            <?php _e("Continue Shopping"); ?>
                                        </a>*/

                                        ?>
                                    <?php endif; ?>
                                    <div class="clear"></div>
                                </div>


                            </div>
                        </div>
                        <div class="js-step-content js-checkout-complete">
                            <div class="text-center p-40">
                                <h3><?php _e("Thank you for your purchase!"); ?></h3>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
            <div class="mw-checkout-response"></div>


        <?php endif; ?>
    </div>
</div>


<script>


    $(document).ready(function () {

        mw.cart.modal.init('#checkout_modal_<?php print $params['id'] ?>')


        setTimeout(function () {


            $('.step-button:nth-child(1) .js-show-step', '#checkout_modal_<?php print $params['id'] ?>').addClass('active');
            $('.js-step-content:nth-child(1)', '#checkout_modal_<?php print $params['id'] ?>').show();



            <?php  if($payment_success){ ?>
            mw_cart_show_payment_success_tab()

            <?php }  ?>




        }, 500);


        mw.on('mw.cart.checkout.success', function (event, data) {


            if (typeof(data.order_completed) != 'undefined' && data.order_completed) {
                mw_cart_show_payment_success_tab()
            }

        });


    });


    function mw_cart_show_payment_success_tab() {
        $('.js-show-step', '#checkout_modal_<?php print $params['id'] ?>').off('click');

        $('.step-button .js-show-step', '#checkout_modal_<?php print $params['id'] ?>').removeClass('active');
        $('.step-button', '#checkout_modal_<?php print $params['id'] ?>').addClass('muted');
        $('.js-step-content', '#checkout_modal_<?php print $params['id'] ?>').hide();


        $('.step-button:nth-child(4)', '#checkout_modal_<?php print $params['id'] ?>').removeClass('muted');
        $('.step-button:nth-child(4) .js-show-step', '#checkout_modal_<?php print $params['id'] ?>').addClass('active');
        $('.js-step-content:nth-child(4)', '#checkout_modal_<?php print $params['id'] ?>').show();


    }


</script>