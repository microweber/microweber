<script type="text/javascript">

function paypal_checkout_callback(data,selector){
	alert('paypal_checkout_callback');
	$(selector).empty().append(data);

}


</script>
<small><b>Thank you for your order.</b> <br>
You will be redirected to PayPal's website.<br>
Your shopping cart will be emptied</small>