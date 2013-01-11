    <label class="mw-ui-label">Test mode:</label>
 

 <label class="mw-ui-check">
    <input name="paypalexpress_testmode" class="mw_option_field"    data-option-group="payments"  value="y"  type="radio"  <? if(get_option('paypalexpress_testmode', 'payments') != 'n'): ?> checked="checked" <? endif; ?> >
    <span></span>Yes</label>
    
     <label class="mw-ui-check">
    <input name="paypalexpress_testmode" class="mw_option_field"    data-option-group="payments"  value="n"  type="radio"  <? if(get_option('paypalexpress_testmode', 'payments') == 'n'): ?> checked="checked" <? endif; ?> >
    <span></span>No</label>
    
    
    
<label class="mw-ui-label">Paypal username: </label>
<input type="text" class="mw-ui-field mw_option_field" name="paypalexpress_username"    data-option-group="payments"  value="<? print get_option('paypalexpress_username', 'payments'); ?>" >
