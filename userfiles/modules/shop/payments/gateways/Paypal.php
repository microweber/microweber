<script type="text/javascript">

function paypal_checkout_callback(data,selector){
	alert('paypal_checkout_callback');
	$(selector).empty().append(data);

}


</script>
<? 

$paypal_is_test = (get_option('paypalexpress_testmode', 'payments')) == 'y';
 
?>
<small><b>Thank you for your order.</b> <br>
You will be redirected to PayPal's website.<br>
Your shopping cart will be emptied</small>
<? if($paypal_is_test == true and is_admin()): ?>
<small><? print mw_warn("You are using Paypal Express in test mode!"); ?></small>
<? endif; ?>