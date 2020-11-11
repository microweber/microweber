<?php must_have_access(); ?>


<div class="mb-3 float-right">
    <img src="<?php print $config['url_to_module'] ?>paypal_pro.svg"  style="width: 50px; margin-top: -70px;"/>
</div>

<div class="form-group">
    <label class="control-label d-block"><?php _e("Test mode"); ?></label>

    <div class="custom-control custom-radio d-inline-block mr-2">
        <input type="radio" id="paypalpro_testmode1" name="paypalpro_testmode" class="mw_option_field custom-control-input" data-option-group="payments" value="y" <?php if (get_option('paypalpro_testmode', 'payments') == 'y'): ?> checked="checked" <?php endif; ?>>
        <label class="custom-control-label" for="paypalpro_testmode1"><?php _e("Yes"); ?></label>
    </div>

    <div class="custom-control custom-radio d-inline-block mr-2">
        <input type="radio" id="paypalpro_testmode2" name="paypalpro_testmode" class="mw_option_field custom-control-input" data-option-group="payments" value="n" <?php if (get_option('paypalpro_testmode', 'payments') != 'y'): ?> checked="checked" <?php endif; ?>>
        <label class="custom-control-label" for="paypalpro_testmode2"><?php _e("No"); ?></label>
    </div>
</div>

<!--<div class="form-group">
    <label class="control-label">API Username:</label>
<input type="text" class="form-control mw_option_field" name="paypalpro_username"    data-option-group="payments"  value="<?php print get_option('paypalpro_username', 'payments'); ?>" >
</div>-->

<div class="form-group">
    <label class="control-label"><?php _e("API Username"); ?>:</label>
    <input type="text" class="form-control mw_option_field" name="paypalpro_apikey" data-option-group="payments" value="<?php print get_option('paypalpro_apikey', 'payments'); ?>">
</div>

<div class="form-group">
    <label class="control-label"><?php _e("API Password"); ?>:</label>
    <input type="text" class="form-control mw_option_field" name="paypalpro_apipassword" data-option-group="payments" value="<?php print get_option('paypalpro_apipassword', 'payments'); ?>">
</div>

<div class="form-group">
    <label class="control-label"><?php _e("Signature"); ?>:</label>
    <input type="text" class="form-control mw_option_field" name="paypalpro_apisignature" data-option-group="payments" value="<?php print get_option('paypalpro_apisignature', 'payments'); ?>">
</div>

<a class="btn btn-link px-0" href="http://www.youtube.com/watch?v=oCivDxMpUcs" target="_blank"><?php _e('Learn more about setting up you'); ?> PayPal Pro Account</a>
