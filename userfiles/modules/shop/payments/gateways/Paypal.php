<script type="text/javascript">

function paypal_checkout_callback(data,selector){
	alert('paypal_checkout_callback');
	$(selector).empty().append(data);

}


</script>
<? 

$paypal_is_test = (get_option('paypalexpress_testmode', 'payments')) == 'y';
 
?>
<p><b>Thank you for your order.</b>  </p>

<p>You will be redirected to PayPal's website. </p>


Your shopping cart will be emptied
<? if($paypal_is_test == true and is_admin()): ?>
<small><? print mw_warn("You are using Paypal Express in test mode!"); ?></small>
<? endif; ?>