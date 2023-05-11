<?php must_have_access(); ?>

<!--<div class="mb-3 float-right">-->
<!--    <img src="--><?php //print $config['url_to_module'] ?><!--paypal.svg"  style="width: 50px; margin-top: -60px;"/>-->
<!--</div>-->

<div class="clearfix"></div>


<?php if (get_option('payment_gw_shop/payments/gateways/paypal', 'payments')): ?>
    <div class="d-flex align-items-center mb-3">
        <span class="badge bg-green me-2"></span>
        <p class="text-success mb-0"><?php _e("Activated") ?> </p>
    </div>
<?php endif; ?>

<div class="form-group d-flex align-items-center justify-content-between">
    <label class="form-label d-block"><?php _e("Test mode"); ?></label>

    <div class="form-check form-check-single form-switch" style="width: unset;">
        <input type="checkbox" id="paypalexpress_testmode1" name="paypalexpress_testmode" class="mw_option_field form-check-input" data-value-unchecked="n" data-value-checked="y" data-option-group="payments" value="y" <?php if (get_option('paypalexpress_testmode', 'payments') == 'y'): ?> checked="checked" <?php endif; ?>>
    </div>
</div>

<div class="form-group">
    <label class="form-label"><?php _e("Paypal username"); ?>: </label>
    <input type="text" class="mw_option_field form-control" name="paypalexpress_username" placeholder="paypal@example.com" data-option-group="payments" value="<?php print get_option('paypalexpress_username', 'payments'); ?>">
</div>
