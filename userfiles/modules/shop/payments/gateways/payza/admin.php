<?php only_admin_access(); ?>
    <div class="m-b-20">
        <img src="<?php print $config['url_to_module'] ?>payza.png" style="max-width: 140px;"/>
    </div>

    <div class="m-b-10">
        <label class="mw-ui-label">Payza username: </label>
        <input type="text" class="mw-ui-field mw_option_field block-field" name="payza_username"
               placeholder="" data-option-group="payments"
               value="<?php print get_option('payza_username', 'payments'); ?>">
    </div>
    <ul class="mw-ui-inline-list">
        <li><label class="mw-ui-label p-10 bold"><?php _e("Test mode"); ?>:</label></li>

        <li><label class="mw-ui-check">
                <input name="payza_testmode" class="mw_option_field" data-option-group="payments" value="y" type="radio" <?php if (get_option('payza_testmode', 'payments') == 'y'): ?> checked="checked" <?php endif; ?> >
                <span></span><span><?php _e("Yes"); ?></span></label></li>

        <li><label class="mw-ui-check">
                <input name="payza_testmode" class="mw_option_field" data-option-group="payments" value="n" type="radio" <?php if (get_option('payza_testmode', 'payments') != 'y'): ?> checked="checked" <?php endif; ?> >
                <span></span><span><?php _e("No"); ?></span></label></li>
    </ul>


<?php //print api_url('checkout_ipn?payment_gw=shop/payments/gateways/payza') ?>