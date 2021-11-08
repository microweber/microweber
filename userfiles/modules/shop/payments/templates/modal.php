<?php

/*

type: layout

name: Payments 1

description: Payments 1

*/
?>
<script type="text/javascript">
    $(document).ready(function () {
        var logoPath = mw.$('.mw-payment-gateway-<?php print $params['id']; ?>').find('option:selected').data('logo');
        $('.js-gateway-img-holder').find('img').attr('src', logoPath).show();

        mw.$('.mw-payment-gateway-<?php print $params['id']; ?>').on('change', function () {
            mw.trigger('mw.cart.paymentMethodChange');

            mw.$('.mw-payment-gateway-selected-<?php print $params['id']; ?> .module:first').attr('data-selected-gw', $(this).find('option:selected'));
            mw.load_module('' + this.value, '#mw-payment-gateway-selected-<?php print $params['id']; ?>');

            logoPath = $(this).find('option:selected').data('logo');
            $('.js-gateway-img-holder').find('img').attr('src', logoPath).show();
        });

        var ritems = $('.mw-shipping-and-payments input[type="radio"]');
        ritems.on('input', function (){
            ritems.each(function (){
                $(this).parent()[this.checked ? 'addClass' : 'removeClass']('btn-primary');
            });
        });

        // $('.methods input, .methods select').addClass('input-lg');
    });
</script>

<div class="mw-shipping-and-payments">
    <?php if ($payment_options and count($payment_options) > 0): ?>
               <div class="form-row">
                    <div class="methods">
                       <div class="edit nodrop mt-2" field="checkout_payment_information_title" rel="global"
                            rel_id="<?php print $params['id'] ?>">
                           <label class="control-label"><?php _e("Choose Payment Method"); ?></label>
                           <small class="text-muted d-block mb-2"><?php _e("Choose from the available payment methods to pay this order."); ?></small>
                           <div class="text-center my-4 d-sm-inline-block d-md-flex flex-wrap">
                               <?php $count = 0;
                               foreach ($payment_options as $payment_option) : $count++; ?>

                                   <label class="btn btn-outline-primary btn-lg <?php if($count == 1) { print 'btn-primary';}   ?>  custom-control custom-radio mw-payment-gateway mw-payment-gateway-<?php print $params['id']; ?> mx-1">
                                       <input style="display: none;" value="<?php print  $payment_option['gw_file']; ?>" name="payment_gw" type="radio" class="custom-control-input" <?php if($count == 1) { print 'checked';} ?>>
                                       <span for="customRadio1"><?php _e($payment_option['name']); ?></span>
                                   </label>
                               <?php endforeach; ?>
                           </div>
                       </div>

                       <div class="edit nodrop" field="checkout_payment_information_payments" rel="global"
                            rel_id="<?php print $params['id'] ?>">
                           <label class="control-label"><?php _e("Finish your order"); ?></label>
                           <small class="text-muted d-block mb-2"><?php _e("Please full the fields of the selected payment method below, if it has."); ?></small>

                           <div class="mx-3 mt-4 mb-6" id="mw-payment-gateway-selected-<?php print $params['id']; ?>">
                               <?php if (isset($payment_options[0])): ?>
                                   <module type="<?php print $payment_options[0]['gw_file'] ?>"/>
                               <?php endif; ?>
                           </div>
                           <div class="mw-checkout-response"></div>

                           <div class="mt-5">
                               <?php $cart_subtotal = cart_totals('subtotal'); ?>
                               <?php _e("Subtotal") ?>: <?php echo $cart_subtotal['amount']; ?>

                               <div class="row my-1 ml-auto">
                                   <?php $print_total = cart_total(); ?>
                                   <label class="col-xs-6 checkout-modal-total-label mr-1 control-label"><?php _e("Total"); ?>:</label>
                                   <label class="col-xs-6 checkout-modal-total-price pl-0">
                                       <?php print currency_format($print_total); ?>
                                   </label>
                               </div>
                               <small class="text-muted mt-1"><i>*<?php _e("Shopping cart will be emptied when order is completed"); ?></i></small>
                           </div>
                       </div>
                    </div>
                </div>
    <?php endif; ?>
</div>
