
<ul class="mw-ui-inline-selector">
<li><label class="mw-ui-label"><?php _e("Test mode"); ?>:</label></li>
 

 <li><label class="mw-ui-check">
    <input name="paypalexpress_testmode" class="mw_option_field"    data-option-group="payments"  value="y"  type="radio"  <?php if(mw('option')->get('paypalexpress_testmode', 'payments') != 'n'): ?> checked="checked" <?php endif; ?> >
    <span></span><span><?php _e("Yes"); ?></span></label></li>

     <li><label class="mw-ui-check">
    <input name="paypalexpress_testmode" class="mw_option_field"    data-option-group="payments"  value="n"  type="radio"  <?php if(mw('option')->get('paypalexpress_testmode', 'payments') == 'n'): ?> checked="checked" <?php endif; ?> >
    <span></span><span><?php _e("No"); ?></span></label></li>
</ul>
    

<label class="mw-ui-label"><?php _e("Paypal username"); ?>: </label>


<input type="text" class="mw-ui-field mw_option_field" name="paypalexpress_username"  placeholder="paypal@example.com"   data-option-group="payments"  value="<?php print mw('option')->get('paypalexpress_username', 'payments'); ?>" >
