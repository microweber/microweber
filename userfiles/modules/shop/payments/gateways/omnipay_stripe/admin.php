<?php only_admin_access(); ?>


<label class="mw-ui-label">Public Api Key: </label>

<input type="text" class="mw-ui-field mw_option_field" name="stripe_api_key"
       placeholder="" data-option-group="payments"
       value="<?php print get_option('stripe_api_key', 'payments'); ?>">


<label class="mw-ui-label">Secret Api Key: </label>

<input type="text" class="mw-ui-field mw_option_field" name="stripe_secret_api_key"
       placeholder="" data-option-group="payments"
       value="<?php print get_option('stripe_secret_api_key', 'payments'); ?>">

    <ul class="mw-ui-inline-list">
        <li><label class="mw-ui-label"><?php _e("Checkout or Direct"); ?>:</label></li>

        <li><label class="mw-ui-check">
                <input name="stripe_checkout" class="mw_option_field"    data-option-group="payments"  value="y"  type="radio"  <?php if(get_option('stripe_checkout', 'payments') == 'y'): ?> checked="checked" <?php endif; ?> >
                <span></span><span><?php _e("Yes"); ?></span></label></li>

        <li><label class="mw-ui-check">
                <input name="stripe_checkout" class="mw_option_field"    data-option-group="payments"  value="n"  type="radio"  <?php if(get_option('stripe_checkout', 'payments') != 'y'): ?> checked="checked" <?php endif; ?> >
                <span></span><span><?php _e("No"); ?></span></label></li>
    </ul>

    <ul class="mw-ui-inline-list">
        <li><label class="mw-ui-label"><?php _e("Test mode"); ?>:</label></li>

        <li><label class="mw-ui-check">
                <input name="stripe_testmode" class="mw_option_field"    data-option-group="payments"  value="y"  type="radio"  <?php if(get_option('stripe_testmode', 'payments') == 'y'): ?> checked="checked" <?php endif; ?> >
                <span></span><span><?php _e("Yes"); ?></span></label></li>

        <li><label class="mw-ui-check">
                <input name="stripe_testmode" class="mw_option_field"    data-option-group="payments"  value="n"  type="radio"  <?php if(get_option('stripe_testmode', 'payments') != 'y'): ?> checked="checked" <?php endif; ?> >
                <span></span><span><?php _e("No"); ?></span></label></li>
    </ul>
