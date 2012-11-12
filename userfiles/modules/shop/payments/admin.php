 Payment providers 
<script  type="text/javascript">
 mw.require('options.js');
 </script> 
<script  type="text/javascript">

$(document).ready(function(){
mw.options.form('.mw-set-payment-options');
 
});
</script>
<?
$here = dirname(__FILE__).DS.'gateways'.DS;
$payment_modules = modules_list("&dir_name={$here}");
 
?>
<div class="mw-set-payment-options" >
  <? if(isarr($payment_modules )): ?>
  <? foreach($payment_modules  as $payment_module): ?>
  <h2><? print $payment_module['name'] ?></h2>
  Enabled:
  <label class="mw-ui-check">
    <input name="payment_gw_<? print $payment_module['module'] ?>" class="mw_option_field"    data-option-group="payments"  value="y"  type="radio"  <? if(option_get('payment_gw_'.$payment_module['module'], 'payments') == 'y'): ?> checked="checked" <? endif; ?> >
    <span></span>Yes</label>
  <label class="mw-ui-check">
    <input name="payment_gw_<? print $payment_module['module'] ?>" class="mw_option_field"     data-option-group="payments"  value="n"  type="radio"  <? if(option_get('payment_gw_'.$payment_module['module'], 'payments') != 'y'): ?> checked="checked" <? endif; ?> >
    <span></span>No</label>
  <div class="mw-set-payment-gw-options" >
    <module type="shop/payments/gateways/<? print $payment_module['module'] ?>" view="admin" />
  </div>
  <? endforeach ; ?>
  <? endif; ?>
</div>
