<?php only_admin_access(); ?>
<ul class="mw-ui-inline-list">
<li><label class="mw-ui-label"><?php _e("Test mode"); ?>:</label></li>
 

 <li><label class="mw-ui-check">
    <input name="epayexpress_testmode" class="mw_option_field"    data-option-group="payments"  value="y"  type="radio"  <?php if(get_option('epayexpress_testmode', 'payments') == 'y'): ?> checked="checked" <?php endif; ?> >
    <span></span><span><?php _e("Yes"); ?></span></label></li>

     <li><label class="mw-ui-check">
    <input name="epayexpress_testmode" class="mw_option_field"    data-option-group="payments"  value="n"  type="radio"  <?php if(get_option('epayexpress_testmode', 'payments') != 'y'): ?> checked="checked" <?php endif; ?> >
    <span></span><span><?php _e("No"); ?></span></label></li>
</ul>
    

<label class="mw-ui-label"><?php _e("Epay Account ID (KIN)"); ?>: </label>


<input type="text" class="mw-ui-field mw_option_field" name="epayexpress_username"  placeholder="epay@example.com"   data-option-group="payments"  value="<?php print get_option('epayexpress_username', 'payments'); ?>" >

<label class="mw-ui-label"><?php _e("Enter succesful order url"); ?>: </label>

<input type="text" class="mw-ui-field mw_option_field" name="epayexpress_success"  placeholder="https://www.epay.bg/?p=thanks"   data-option-group="payments"  value="<?php print get_option('epayexpress_success', 'payments'); ?>" >

<label class="mw-ui-label"><?php _e("Enter CANCELED order url"); ?>: </label>

<input type="text" class="mw-ui-field mw_option_field" name="epayexpress_failed"  placeholder="https://www.epay.bg/?p=cancel"   data-option-group="payments"  value="<?php print get_option('epayexpress_faild', 'payments'); ?>" >