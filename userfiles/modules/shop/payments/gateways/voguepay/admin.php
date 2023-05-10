<?php must_have_access(); ?>

<!--<div class="mb-3 float-right">-->
<!--    <img src="--><?php //print $config['url_to_module'] ?><!--voguepay.svg"  style="width: 40px; margin-top: -70px;"/>-->
<!--</div>-->

<div class="clearfix"></div>

<?php if (get_option('payment_gw_shop/payments/gateways/voguepay', 'payments')): ?>
    <div class="d-flex align-items-center mb-3">
        <span class="badge bg-green me-2"></span>
        <p class="text-success mb-0"><?php _e("Activated") ?> </p>
    </div>
<?php endif; ?>

<div class="form-group">
    <label class="form-label">Developer Code: </label>
    <input type="text" class="mw_option_field form-control" name="voguepay_developer_code" placeholder="" data-option-group="payments" value="<?php print get_option('voguepay_developer_code', 'payments'); ?>">
</div>

<div class="form-group">
    <label class="form-label">Merchant ID: </label>
    <input type="text" class="mw_option_field form-control" name="voguepay_merchant_id" placeholder="" data-option-group="payments" value="<?php print get_option('voguepay_merchant_id', 'payments'); ?>">
</div>

<div class="form-group">
    <label class="form-label d-block"><?php _e("Test mode"); ?></label>

    <div class="custom-control custom-radio d-inline-block me-2">
        <input type="radio" id="voguepay_test_mode1" name="voguepay_test_mode" class="mw_option_field form-check-input" data-option-group="payments" value="1" <?php if (get_option('voguepay_test_mode', 'payments') == 1): ?> checked="checked" <?php endif; ?>>
        <label class="custom-control-label" for="voguepay_test_mode1"><?php _e("Yes"); ?></label>
    </div>

    <div class="custom-control custom-radio d-inline-block me-2">
        <input type="radio" id="voguepay_test_mode2" name="voguepay_test_mode" class="mw_option_field form-check-input" data-option-group="payments" value="0" <?php if (get_option('voguepay_test_mode', 'payments') != 1): ?> checked="checked" <?php endif; ?>>
        <label class="custom-control-label" for="voguepay_test_mode2"><?php _e("No"); ?></label>
    </div>
</div>
