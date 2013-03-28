
<ul class="mw-ui-inline-selector">
<li><label class="mw-ui-label">Test mode:</label></li>
 

 <li><label class="mw-ui-check">
    <input name="paypalexpress_testmode" class="mw_option_field"    data-option-group="payments"  value="y"  type="radio"  <? if(get_option('paypalexpress_testmode', 'payments') != 'n'): ?> checked="checked" <? endif; ?> >
    <span></span><span>Yes</span></label></li>

     <li><label class="mw-ui-check">
    <input name="paypalexpress_testmode" class="mw_option_field"    data-option-group="payments"  value="n"  type="radio"  <? if(get_option('paypalexpress_testmode', 'payments') == 'n'): ?> checked="checked" <? endif; ?> >
    <span></span><span>No</span></label></li>
</ul>
    

<label class="mw-ui-label">Paypal username: </label>
<input type="text" class="mw-ui-field mw_option_field" name="paypalexpress_username"  placeholder="paypal@example.com"   data-option-group="payments"  value="<? print get_option('paypalexpress_username', 'payments'); ?>" >
