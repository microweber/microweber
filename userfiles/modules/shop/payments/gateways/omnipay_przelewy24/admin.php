<?php only_admin_access(); ?>


<label class="mw-ui-label">Merchant Id: </label>


<input type="text" class="mw-ui-field mw_option_field" name="przelewy24_merchant_id"
       placeholder="" data-option-group="payments"
       value="<?php print get_option('przelewy24_merchant_id', 'payments'); ?>">



 

 
<label class="mw-ui-label">CRC key: </label>


<input type="text" class="mw-ui-field mw_option_field" name="przelewy24_crc"
       placeholder="" data-option-group="payments"
       value="<?php print get_option('przelewy24_crc', 'payments'); ?>">
       

<ul class="mw-ui-inline-list">
<li><label class="mw-ui-label"><?php _e("Test mode"); ?>:</label></li>
 

 <li><label class="mw-ui-check">
    <input name="przelewy24_testmode" class="mw_option_field"    data-option-group="payments"  value="y"  type="radio"  <?php if(get_option('przelewy24_testmode', 'payments') == 'y'): ?> checked="checked" <?php endif; ?> >
    <span></span><span><?php _e("Yes"); ?></span></label></li>

     <li><label class="mw-ui-check">
    <input name="przelewy24_testmode" class="mw_option_field"    data-option-group="payments"  value="n"  type="radio"  <?php if(get_option('przelewy24_testmode', 'payments') != 'y'): ?> checked="checked" <?php endif; ?> >
    <span></span><span><?php _e("No"); ?></span></label></li>
</ul>