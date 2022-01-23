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

        <?php if ($requires_registration and is_logged() == false): ?>
            <script>
                $(document).ready(function () {

                    if (!!$.fn.selectpicker) {
                        $('#loginModal').modal();
                    }
                })
            </script>
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
                                   <a href="#" class="js-show-step js-show-step-shopping-cart" data-step="shopping-cart">
                                       <i class="mdi mdi-cart"></i>
                                       <h6 class="font-weight-bold mb-1"><?php _e("Shopping Cart"); ?></h6>

                                   </a>
                               </div>
                               <div class="col-3 step-button muted">
                                   <a href="#" class="js-show-step js-show-step-delivery-address" data-step="delivery-address">
                                       <i class="material-icons">local_shipping</i>
                                       <h6 class="font-weight-bold mb-1"><?php _e("Delivery Address"); ?> </h6>
                                   </a>
                               </div>
                               <div class="col-3 step-button muted">
                                   <a href="#" class="js-show-step js-show-step-payment-method" data-step="payment-method">
                                       <i class="material-icons">payment</i>
                                       <h6 class="font-weight-bold mb-1"><?php _e("Payment Method"); ?></h6>
                                   </a>
                               </div>
                               <div class="col-3 step-button muted">
                                   <a href="#" class="js-show-step js-show-step-checkout-complete" data-step="checkout-complete">
                                       <i class="material-icons">check_circle</i>
                                       <h6 class="font-weight-bold mb-1"><?php _e("Complete"); ?></h6>
                                   </a>
                               </div>
                           </div>
                       </div>


                       <div class="modal-body">
                           <div class="js-step-content js-shopping-cart">
                               <?php $cart_show_enanbled = get_option('data-show-cart', $params['id']); ?>
                               <?php if ($cart_show_enanbled != 'n'): ?>
                                   <module type="shop/cart" template="modal" data-checkout-link-enabled="n" id="cart_checkout_<?php print $params['id'] ?>"/>
                               <?php endif; ?>
                           </div>

                           <div class="js-step-content js-delivery-address">

                               <div class="my-4 edit nodrop" field="checkout_personal_information_title" rel="global"
                                    rel_id="<?php print $params['id'] ?>">
                                   <small class="pull-right text-muted">*<?php _e("Fields are required"); ?></small>
                                   <label class="control-label mb-2"><?php _e("Personal Information"); ?></label>
                                   <small class="text-muted d-block mb-2"> <?php _e("Add your personal information"); ?></small>
                               </div>
                               <div class="row">
                                   <div class="col-12 col-md-6">
                                       <div class="form-group">
                                           <label for="exampleInputFirstName"><?php _e("First Name"); ?></label>
                                           <input required name="first_name" type="text" value="<?php if (!empty($checkout_session['first_name'])) echo $checkout_session['first_name']; ?>" class="form-control"
                                                  placeholder="<?php _e("First Name"); ?>">
                                       </div>
                                   </div>
                                   <div class="col-12 col-md-6">
                                       <div class="form-group">
                                           <label for="exampleInputLastName"><?php _e("Last Name"); ?></label>
                                           <input required name="last_name" type="text" value="<?php if (!empty($checkout_session['last_name'])) echo $checkout_session['last_name']; ?>" class="form-control"
                                                  placeholder="<?php _e("Last Name"); ?>">
                                       </div>
                                   </div>
                               </div>

                               <div class="row">
                                   <div class="col-12 col-md-6">
                                       <div class="form-group">
                                           <label for="exampleInputEmail1"><?php _e("Email"); ?></label>
                                           <input required name="email" type="email" value="<?php if (!empty($checkout_session['email'])) echo $checkout_session['email']; ?>" class="form-control"
                                                  placeholder="<?php _e("Enter email"); ?>">

                                       </div>
                                   </div>
                                   <div class="col-12 col-md-6">
                                       <div class="form-group">
                                           <label for="exampleInputPhone"><?php _e("Phone"); ?></label>
                                           <input required name="phone" type="text" value="<?php if (!empty($checkout_session['phone'])) echo $checkout_session['phone']; ?>" class="form-control"
                                                  placeholder="<?php _e("Enter phone"); ?>">
                                       </div>
                                   </div>
                               </div>

                               <module type="shop/shipping" data-store-values="true" template="modal"/>

                               <div class="m-t-10">
                                   <a href="#" class="btn btn-primary d-flex justify-content-center btn-lg rounded mt-1 js-show-step"
                                      data-step="payment-method"><?php _e("Continue"); ?></a>
                               </div>
                           </div>

                           <div class="js-step-content js-payment-method">
                               <module type="shop/payments" data-store-values="true" template="modal"/>
                               <div class="mw-cart-action-holder">
                                   <module type="shop/checkout/terms"/>
                                   <hr/>
                                   <?php $shop_page = get_content('is_shop=1'); ?>
                                   <button class="btn btn-primary d-flex justify-content-center w-100 btn-lg rounded"
                                           onclick="mw.cart.checkout('#checkout_form_<?php print $params['id'] ?>');"
                                           type="button"
                                           id="complete_order_button" <?php if ($terms): ?> disabled="disabled"   <?php endif; ?>>
                                       <?php _e("Complete order"); ?>
                                   </button>
                                   <div class="clear"></div>
                               </div>
                           </div>
                           <div class="js-step-content js-checkout-complete">
                               <div class="text-center p-10">
                                   <h3><?php _e("Thank you for your purchase!"); ?></h3>
                               </div>
                           </div>
                       </div>
                       <div class="mw-checkout-response"></div>
               </form>

        <?php endif; ?>

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
