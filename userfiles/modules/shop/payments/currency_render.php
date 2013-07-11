<?php

$curencies = currencies_list_paypal();
//the  $currencies array now have a list of supported currencies supported by pal






$cur = get_option('currency', 'payments');  
//$num = rand(50,1000).'.'.rand(10,100);;
$num = 1.00;
?>
<?php if (!in_array(strtoupper($cur), $curencies)): ?>
<?php $payment_currency = get_option('payment_currency', 'payments');  ?>
<?php $payment_currency_rate = get_option('payment_currency_rate', 'payments'); 
if($payment_currency_rate != false){
 $payment_currency_rate = str_replace(',','.',$payment_currency_rate);
 $payment_currency_rate = floatval( $payment_currency_rate);

}
 ?>
<?php if(isarr($curencies )): ?>

<h2>Accept payments in currency</h2>
<div class="mw-ui-select">
  <select name="payment_currency" class="mw-ui-field mw_option_field" data-option-group="payments" data-reload="mw_curr_rend">
    <?php foreach($curencies  as $item): ?>
    <option  value="<?php print $item ?>" <?php if($payment_currency == $item): ?> selected="selected" <?php endif; ?>><?php print $item ?></option>
    <?php endforeach ; ?>
  </select>
</div>
<br />

 <small>You must use one of the above currencies to accept payments</small>

<label class="mw-ui-label">Convert rate to from default currency to payment currency</label>
<input  name="payment_currency_rate" value="<?php print $payment_currency_rate; ?>"  id="payment_currency_rate_val_sugg"   type="text" class="mw-ui-field mw_option_field" data-option-group="payments" data-reload="mw_curr_rend" />
<?php $sugg  = currency_online_convert_rate($cur,$payment_currency); ?>
<?php  if($sugg  != false): ?>
<br />
<small>Suggested: <?php print $sugg  ?> <a class="mw-ui-link" href="javascript:$('#payment_currency_rate_val_sugg').val(<?php print $sugg  ?>).change(); void(0);">[use]</a></small>
<?php endif; ?>
<?php endif; ?>
<?php endif; ?>
<div class="vSpace"></div>
<label class="mw-ui-label">Example of how the price will be diplayed.</label>
<input  value="<?php print ( currency_format($num, $cur)); ?>" disabled  type="text" class="mw-ui-invisible-field" />
<?php if (isset($payment_currency) and !in_array(strtoupper($cur), $curencies) ): ?>
<label class="mw-ui-label">Equals to (rate: <?php print  $payment_currency_rate ?> or <?php print ( currency_format(1, $cur)); ?>=<?php print ( currency_format(1*$payment_currency_rate, $payment_currency)); ?> )</label>
<input  value="<?php print ( currency_format($num*$payment_currency_rate, $payment_currency)); ?>" disabled  type="text" class="mw-ui-invisible-field" />
<?php endif; ?>
<div class="vSpace"></div>
