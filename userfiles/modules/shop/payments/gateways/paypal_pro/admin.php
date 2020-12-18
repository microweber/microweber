<?php only_admin_access(); ?>


<div class="m-b-10">
    <img src="<?php print $config['url_to_module'] ?>paypal_pro_inner.png" style="max-width: 140px;"/>
</div>

<ul class="mw-ui-inline-list">
    <li>
        <label class="mw-ui-label p-10 bold"><?php _e("Test mode"); ?>:</label>
    </li>
    <li>
        <label class="mw-ui-check">
            <input name="paypalpro_testmode" class="mw_option_field" data-option-group="payments" value="y" type="radio" <?php if (get_option('paypalpro_testmode', 'payments') == 'y'): ?> checked="checked" <?php endif; ?> >
            <span></span><span><?php _e("Yes"); ?></span>
        </label>
    </li>
    <li>
        <label class="mw-ui-check">
            <input name="paypalpro_testmode" class="mw_option_field" data-option-group="payments" value="n" type="radio" <?php if (get_option('paypalpro_testmode', 'payments') != 'y'): ?> checked="checked" <?php endif; ?> >
            <span></span><span><?php _e("No"); ?></span>
        </label>
    </li>
</ul>

<!--<label class="mw-ui-label">API Username:</label>
<input type="text" class="mw-ui-field mw_option_field" name="paypalpro_username"    data-option-group="payments"  value="<?php print get_option('paypalpro_username', 'payments'); ?>" >-->

<div class="mw-ui-row">

    <div class="mw-ui-col">
        <label class="mw-ui-label"><?php _e("API Username"); ?>:</label>
        <input type="text" class="mw-ui-field mw_option_field" name="paypalpro_apikey" data-option-group="payments" value="<?php print get_option('paypalpro_apikey', 'payments'); ?>">
        <label class="mw-ui-label"><?php _e("API Password"); ?>:</label>
        <input type="text" class="mw-ui-field mw_option_field" name="paypalpro_apipassword" data-option-group="payments" value="<?php print get_option('paypalpro_apipassword', 'payments'); ?>">
        <label class="mw-ui-label"><?php _e("Signature"); ?>:</label>
        <input type="text" class="mw-ui-field mw_option_field" name="paypalpro_apisignature" data-option-group="payments" value="<?php print get_option('paypalpro_apisignature', 'payments'); ?>">
    </div>
    <div class="mw-ui-col">

        <a class="mw-ui-btn" style="height: auto" href="http://www.youtube.com/watch?v=oCivDxMpUcs" target="_blank"> <?php _e('Learn more about setting up you'); ?><br/> <strong>PayPal Pro</strong> Account</a>

    </div>

</div>

