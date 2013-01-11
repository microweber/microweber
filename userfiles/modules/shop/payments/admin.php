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
$payment_modules = modules_list("cache_group=modules/global&dir_name={$here}");
// d($payment_modules);
?>

<div class="vSpace"></div>
<?
/**
 *
 */

?>
<div class="mw-o-box mw-set-payment-options" style="background: #F7F7F7;" >
  <label class="mw-ui-label">Currency</label>
  <input name="currency" class="mw-ui-field mw_option_field" data-option-group="payments"  value="<? print get_option('currency', 'payments') ?>"  type="text" />
  <div class="vSpace"></div>
  <label class="mw-ui-label">Currency Sign</label>
  <input name="currency_sign" class="mw-ui-field mw_option_field"    data-option-group="payments"  value="<? print get_option('currency_sign', 'payments') ?>"  type="text" />
  <div class="vSpace"></div>
  <label class="mw-ui-label">Send email on new order:</label>
  <label class="mw-ui-check">
    <input name="order_email_enabled" class="mw_option_field"    data-option-group="payments"  value="y"  type="radio"  <? if(get_option('order_email_enabled', 'orders') == 'y'): ?> checked="checked" <? endif; ?> >
    <span></span>Yes</label>
  <label class="mw-ui-check">
    <input name="order_email_enabled" class="mw_option_field"     data-option-group="payments"  value="n"  type="radio"  <? if(get_option('order_email_enabled', 'orders') != 'y'): ?> checked="checked" <? endif; ?> >
    <span></span>No</label>
  <label class="mw-ui-label">Email subject</label>
  <input name="order_email_subject" class="mw-ui-field mw_option_field"    data-option-group="orders"  value="<? print get_option('order_email_subject', 'orders') ?>"  type="text" />
  <label class="mw-ui-label">Email content</label>
  <textarea class="mw-ui-field mw_option_field"   data-option-group="orders"  value="<? print get_option('order_email_content', 'orders') ?>"></textarea>
  <label class="mw-ui-label">From email</label>
  <input name="order_email_from" class="mw-ui-field mw_option_field"    data-option-group="orders"  value="<? print get_option('order_email_from', 'orders') ?>"  type="text" />
  <label class="mw-ui-label">From name</label>
  <input name="order_email_name" class="mw-ui-field mw_option_field"    data-option-group="orders"  value="<? print get_option('order_email_name', 'orders') ?>"  type="text" />
  <h1>Payment providers </h1>
  <? if(isarr($payment_modules )): ?>
  <? foreach($payment_modules  as $payment_module): ?>
  <h2><? print $payment_module['name'] ?></h2>  
   
  <label class="mw-ui-label">Enabled:</label>
  <label class="mw-ui-check">
    <input name="payment_gw_<? print $payment_module['module'] ?>" class="mw_option_field"    data-option-group="payments"  value="y"  type="radio"  <? if(get_option('payment_gw_'.$payment_module['module'], 'payments') == 'y'): ?> checked="checked" <? endif; ?> >
    <span></span>Yes</label>
  <label class="mw-ui-check">
    <input name="payment_gw_<? print $payment_module['module'] ?>" class="mw_option_field"     data-option-group="payments"  value="n"  type="radio"  <? if(get_option('payment_gw_'.$payment_module['module'], 'payments') != 'y'): ?> checked="checked" <? endif; ?> >
    <span></span>No</label>
  <div class="mw-set-payment-gw-options" >
    <module type="<? print $payment_module['module'] ?>" view="admin" />
  </div>
  <? endforeach ; ?>
  <? endif; ?>
</div>
<div class="vSpace"></div>
