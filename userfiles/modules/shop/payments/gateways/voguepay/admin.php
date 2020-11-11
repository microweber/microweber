<?php must_have_access(); ?>

<div class="mb-3 float-right">
    <img src="<?php print $config['url_to_module'] ?>voguepay.svg"  style="width: 40px; margin-top: -70px;"/>
</div>

<div class="clearfix"></div>

<div class="form-group">
    <label class="control-label">Developer Code: </label>
    <input type="text" class="mw_option_field form-control" name="voguepay_developer_code" placeholder="" data-option-group="payments" value="<?php print get_option('voguepay_developer_code', 'payments'); ?>">
</div>

<div class="form-group">
    <label class="control-label">Merchant ID: </label>
    <input type="text" class="mw_option_field form-control" name="voguepay_merchant_id" placeholder="" data-option-group="payments" value="<?php print get_option('voguepay_merchant_id', 'payments'); ?>">
</div>

<div class="form-group">
    <label class="control-label d-block"><?php _e("Test mode"); ?></label>

    <div class="custom-control custom-radio d-inline-block mr-2">
        <input type="radio" id="voguepay_test_mode1" name="voguepay_test_mode" class="mw_option_field custom-control-input" data-option-group="payments" value="1" <?php if (get_option('voguepay_test_mode', 'payments') == 1): ?> checked="checked" <?php endif; ?>>
        <label class="custom-control-label" for="voguepay_test_mode1"><?php _e("Yes"); ?></label>
    </div>

    <div class="custom-control custom-radio d-inline-block mr-2">
        <input type="radio" id="voguepay_test_mode2" name="voguepay_test_mode" class="mw_option_field custom-control-input" data-option-group="payments" value="0" <?php if (get_option('voguepay_test_mode', 'payments') != 1): ?> checked="checked" <?php endif; ?>>
        <label class="custom-control-label" for="voguepay_test_mode2"><?php _e("No"); ?></label>
    </div>
</div>