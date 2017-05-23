<?php

/*

type: layout

name: Payments 1

description: Payments 1

*/
?>

<style>
    .mw-shipping-and-payment .methods ul {
        list-style: none;
        margin: 0;
        padding: 0;
        overflow: auto;
    }

    .mw-shipping-and-payment .methods ul li {
        color: #AAAAAA;
        display: inline-block;
        position: relative;
        padding: 0;
        margin: 10px 10px 0 0;
    }

    .mw-shipping-and-payment .methods ul li input[type=radio] {
        position: absolute;
        visibility: hidden;
    }

    .mw-shipping-and-payment .methods ul li label {
        display: block;
        position: relative;
        font-weight: 300;
        font-size: 14px;
        text-transform: uppercase;
        padding: 25px 20px;
        margin: 0 auto;
        z-index: 9;
        cursor: pointer;
        -webkit-transition: all 0.25s linear;
    }

    .mw-shipping-and-payment .methods ul li:hover label {
        color: #FFFFFF;
    }

    .mw-shipping-and-payment .methods ul li .check {
        display: block;
        position: absolute;
        border: 1px solid transparent;
        border-radius: 5px;
        background: #e4e4e4;
        height: 70px;
        width: 100%;
        top: 0px;
        z-index: 5;
        transition: border .25s linear;
        -webkit-transition: border .25s linear;
        color: #000;
    }

    .mw-shipping-and-payment .methods ul li:hover .check,
    .mw-shipping-and-payment .methods input[type=radio]:checked ~ .check {
        background: #000;
        color: #fff;
    }

    .mw-shipping-and-payment .methods input[type=radio]:checked ~ label {
        color: #fff;
    }

    .mw-shipping-and-payment .methods .alert.alert-warning,
    .mw-shipping-and-payment .methods .alert.alert-info{
        border: 0;
        background: none;
        color: #636363;
        font-size:16px;
    }
</style>
<div class="mw-shipping-and-payment">
    <?php if (count($payment_options) > 0): ?>
        <div class="row">
            <div class="col-xs-12">
                <h4 class="edit nodrop" field="checkout_payment_information_title" rel="global" rel_id="<?php print $params['id'] ?>"><?php _e("Payment method"); ?></h4>
                <hr/>
            </div>
        </div>

        <div class="methods">
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-sm-offset-6">
                    <ul name="payment_gw" class="mw-payment-gateway mw-payment-gateway-<?php print $params['id']; ?>">
                        <?php $count = 0;
                        foreach ($payment_options as $payment_option) : $count++; ?>
                            <li>
                                <input type="radio" id="option-<?php print $count; ?>" <?php if ($count == 1): ?> checked="checked" <?php endif; ?> value="<?php print  $payment_option['gw_file']; ?>"
                                       name="payment_gw"/>
                                <label for="option-<?php print $count; ?>"><?php print  _e($payment_option['name']); ?></label>
                                <div class="check"></div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-sm-6 col-sm-offset-6">
                    <div id="mw-payment-gateway-selected-<?php print $params['id']; ?>">
                        <?php if (isset($payment_options[0])): ?>
                            <module type="<?php print $payment_options[0]['gw_file'] ?>"/>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

</div>