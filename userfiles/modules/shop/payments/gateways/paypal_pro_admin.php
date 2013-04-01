<div class="mw-notification mw-warning">
  <div> Unfortunately, Paypal Website Payments Pro will not work for countries other than the United Kingdom, United States and Canada. </div>
</div>
<img src="<? print $config['url_to_module'] ?>paypal_pro_inner.png" />
<ul class="mw-ui-inline-selector">
  <li>
    <label class="mw-ui-label">Test mode:</label>
  </li>
  <li>
    <label class="mw-ui-check">
      <input name="paypalpro_testmode" class="mw_option_field"    data-option-group="payments"  value="y"  type="radio"  <? if(get_option('paypalpro_testmode', 'payments') != 'n'): ?> checked="checked" <? endif; ?> >
      <span></span><span>Yes</span></label>
  </li>
  <li>
    <label class="mw-ui-check">
      <input name="paypalpro_testmode" class="mw_option_field"    data-option-group="payments"  value="n"  type="radio"  <? if(get_option('paypalpro_testmode', 'payments') == 'n'): ?> checked="checked" <? endif; ?> >
      <span></span><span>No</span></label>
  </li>
</ul>
<!--<label class="mw-ui-label">API Username:</label>
<input type="text" class="mw-ui-field mw_option_field" name="paypalpro_username"    data-option-group="payments"  value="<? print get_option('paypalpro_username', 'payments'); ?>" >-->
<label class="mw-ui-label">API Username:</label>
<input type="text" class="mw-ui-field mw_option_field" name="paypalpro_apikey"    data-option-group="payments"  value="<? print get_option('paypalpro_apikey', 'payments'); ?>" >
<label class="mw-ui-label">API Password:</label>
<input type="text" class="mw-ui-field mw_option_field" name="paypalpro_apipassword"    data-option-group="payments"  value="<? print get_option('paypalpro_apipassword', 'payments'); ?>" >
<label class="mw-ui-label">Signature:</label>
<input type="text" class="mw-ui-field mw_option_field" name="paypalpro_apisignature"    data-option-group="payments"  value="<? print get_option('paypalpro_apisignature', 'payments'); ?>" >
