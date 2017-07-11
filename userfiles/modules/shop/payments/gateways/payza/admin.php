<?php only_admin_access(); ?>

<label class="mw-ui-label">Payza username: </label>
<input type="text" class="mw-ui-field mw_option_field" name="payza_username"
       placeholder="" data-option-group="payments"
       value="<?php print get_option('payza_username', 'payments'); ?>">


    <ul class="mw-ui-inline-list">
        <li><label class="mw-ui-label"><?php _e("Test mode"); ?>:</label></li>


        <li><label class="mw-ui-check">
                <input name="payza_testmode" class="mw_option_field"    data-option-group="payments"  value="y"  type="radio"  <?php if(get_option('payza_testmode', 'payments') == 'y'): ?> checked="checked" <?php endif; ?> >
                <span></span><span><?php _e("Yes"); ?></span></label></li>

        <li><label class="mw-ui-check">
                <input name="payza_testmode" class="mw_option_field"    data-option-group="payments"  value="n"  type="radio"  <?php if(get_option('payza_testmode', 'payments') != 'y'): ?> checked="checked" <?php endif; ?> >
                <span></span><span><?php _e("No"); ?></span></label></li>
    </ul>



<?php //print api_url('checkout_ipn?payment_gw=shop/payments/gateways/payza') ?>