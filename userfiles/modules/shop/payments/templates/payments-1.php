<?php

/*

type: layout

name: Payments 1

description: Payments 1

*/
?>

<div class="mw-shipping-and-payments">
    <?php if (count($payment_options) > 0): ?>
        <div class="row">
            <div class="col-xs-12">
                <h4 class="edit nodrop" field="checkout_payment_information_title" rel="global" rel_id="<?php print $params['id'] ?>"><?php _e("Payment method"); ?></h4>
                <hr/>
            </div>
        </div>

        <div class="methods">
            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <ul name="payment_gw" class="mw-payment-gateway mw-payment-gateway-<?php print $params['id']; ?>">
                        <?php $count = 0;
                        foreach ($payment_options as $payment_option) : $count++; ?>
                            <li>
                                <div class="wrap-valign">
                                    <div class="wrap-valign-inner">
                                        <input type="radio" id="option-<?php print $count; ?>" <?php if ($count == 1): ?> checked="checked" <?php endif; ?>
                                               value="<?php print  $payment_option['gw_file']; ?>"
                                               name="payment_gw"/>
                                        <label for="option-<?php print $count; ?>"><?php print  _e($payment_option['name']); ?></label>
                                        <div class="check"></div>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="col-xs-12 col-sm-6">
                    <div id="mw-payment-gateway-selected-<?php print $params['id']; ?>">
                        <?php if (isset($payment_options[0])): ?>
                            <module type="<?php print $payment_options[0]['gw_file'] ?>"/>
                        <?php endif; ?>
                    </div>
                    <hr/>
                </div>
            </div>

            <div class="row">

            </div>

            <div class="row">
                <div class="col-xs-12 col-sm-6 col-sm-offset-6">
                    <div class="row">
                        <?php $print_total = cart_total(); ?>
                        <div class="col-xs-6 total-lable"><?php _e("Total"); ?></div>
                        <div class="col-xs-6 right total-price">
                            <?php print currency_format($print_total); ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    <?php endif; ?>

</div>