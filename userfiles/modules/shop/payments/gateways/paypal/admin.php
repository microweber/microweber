<?php must_have_access(); ?>

<div class="mb-3 float-right">
    <img src="<?php print $config['url_to_module'] ?>paypal.svg"  style="width: 50px; margin-top: -60px;"/>
</div>

<div class="clearfix"></div>

<div class="form-group">
    <label class="control-label d-block"><?php _e("Test mode"); ?></label>

    <div class="custom-control custom-radio d-inline-block mr-2">
        <input type="radio" id="paypalexpress_testmode1" name="paypalexpress_testmode" class="mw_option_field custom-control-input" data-option-group="payments" value="y" <?php if (get_option('paypalexpress_testmode', 'payments') == 'y'): ?> checked="checked" <?php endif; ?>>
        <label class="custom-control-label" for="paypalexpress_testmode1"><?php _e("Yes"); ?></label>
    </div>

    <div class="custom-control custom-radio d-inline-block mr-2">
        <input type="radio" id="paypalexpress_testmode2" name="paypalexpress_testmode" class="mw_option_field custom-control-input" data-option-group="payments" value="n" <?php if (get_option('paypalexpress_testmode', 'payments') != 'y'): ?> checked="checked" <?php endif; ?>>
        <label class="custom-control-label" for="paypalexpress_testmode2"><?php _e("No"); ?></label>
    </div>
</div>

<div class="form-group">
    <label class="control-label"><?php _e("Paypal username"); ?>: </label>
    <input type="text" class="mw_option_field form-control" name="paypalexpress_username" placeholder="paypal@example.com" data-option-group="payments" value="<?php print get_option('paypalexpress_username', 'payments'); ?>">
</div>
