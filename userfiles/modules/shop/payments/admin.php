 Payment providers 
<script  type="text/javascript">
$(document).ready(function(){
mw.options.form('.mw-set-payment-options');
 
});
</script>
<?
 

 ?>
<div class="mw-set-payment-options" >
  <h2>Paypal Express checkout</h2>
  Enabled:
  <label class="mw-ui-check">
    <input name="payment_gw_PaypalExpress" class="mw_option_field"    data-option-group="payments"  value="y"  type="radio"  <? if(option_get('payment_gw_PaypalExpress', 'payments') == 'y'): ?> checked="checked" <? endif; ?> >
    <span></span>Yes</label>
  <label class="mw-ui-check">
    <input name="payment_gw_PaypalExpress" class="mw_option_field"     data-option-group="payments"  value="n"  type="radio"  <? if(option_get('payment_gw_PaypalExpress', 'payments') != 'y'): ?> checked="checked" <? endif; ?> >
    <span></span>No</label>
  <br />
  Paypal username:
  <input type="text" class="mw-ui-field mw_option_field" name="payments_PaypalExpress_username"    data-option-group="payments"  value="<? print option_get('payments_PaypalExpress_username', 'payments'); ?>" >
  <br />
  <br />
  <br />
  <br />
  <br />
  <h2>PayPal Website Payments Pro</h2>
  Enabled:
  <label class="mw-ui-check">
    <input name="payment_gw_Paypal" class="mw_option_field"    data-option-group="payments"  value="y"  <? if(option_get('payment_gw_Paypal', 'payments') == 'y'): ?> checked="checked" <? endif; ?>  type="radio">
    <span></span>Yes</label>
  <label class="mw-ui-check">
    <input name="payment_gw_Paypal" class="mw_option_field"     data-option-group="payments"  value="n"   <? if(option_get('payment_gw_Paypal', 'payments') != 'y'): ?> checked="checked" <? endif; ?>  type="radio">
    <span></span>No</label>
  <br />
  Paypal pro username:
  <input type="text" class="mw-ui-field mw_option_field" name="payments_Paypal_username"     data-option-group="payments" value="<? print option_get('payments_Paypal_username', 'payments'); ?>">
  <br />
  Paypal pro password:
  <input type="text" class="mw-ui-field mw_option_field" name="payments_Paypal_password"     data-option-group="payments" value="<? print option_get('payments_Paypal_password', 'payments'); ?>">
  <br />
  Paypal pro api signature:
  <input type="text" class="mw-ui-field mw_option_field" name="payments_Paypal_signature"     data-option-group="payments" value="<? print option_get('payments_Paypal_signature', 'payments'); ?>">
  <br />
</div>
