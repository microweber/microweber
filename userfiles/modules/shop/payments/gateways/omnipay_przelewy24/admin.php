<?php must_have_access(); ?>

<!--<div class="mb-3 float-right">-->
<!--    <img src="--><?php //print $config['url_to_module'] ?><!--omnipay_przelewy24.svg" style="width: 40px; margin-top: -70px;"/>-->
<!--</div>-->

<div class="clearfix"></div>

<?php if (get_option('payment_gw_shop/payments/gateways/omnipay_przelewy24', 'payments')): ?>
    <div class="d-flex align-items-center mb-3">
        <span class="badge bg-green me-2"></span>
        <p class="text-success mb-0"><?php _e("Activated") ?> </p>
    </div>
<?php endif; ?>


<div class="form-group">
    <label class="form-label">Merchant Id: </label>
    <input type="text" class="mw_option_field form-control" name="przelewy24_merchant_id" placeholder="" data-option-group="payments" value="<?php print get_option('przelewy24_merchant_id', 'payments'); ?>">
</div>

<div class="form-group">
    <label class="form-label">CRC key: </label>
    <input type="text" class="mw_option_field form-control" name="przelewy24_crc" placeholder="" data-option-group="payments" value="<?php print get_option('przelewy24_crc', 'payments'); ?>">
</div>

<div class="form-group">
    <label class="form-label d-block"><?php _e("Test mode"); ?></label>

    <div class="custom-control custom-radio d-inline-block me-2">
        <input type="radio" id="przelewy24_testmode1" name="przelewy24_testmode" class="mw_option_field form-check-input" data-option-group="payments" value="y" <?php if (get_option('przelewy24_testmode', 'payments') == 'y'): ?> checked="checked" <?php endif; ?>>
        <label class="custom-control-label" for="przelewy24_testmode1"><?php _e("Yes"); ?></label>
    </div>

    <div class="custom-control custom-radio d-inline-block me-2">
        <input type="radio" id="przelewy24_testmode2" name="przelewy24_testmode" class="mw_option_field form-check-input" data-option-group="payments" value="n" <?php if (get_option('przelewy24_testmode', 'payments') != 'y'): ?> checked="checked" <?php endif; ?>>
        <label class="custom-control-label" for="przelewy24_testmode2"><?php _e("No"); ?></label>
    </div>
</div>
