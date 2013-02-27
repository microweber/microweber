<script type="text/javascript">

function paypal_checkout_callback(data,selector){
	alert('paypal_checkout_callback');
	$(selector).empty().append(data);	 
 
}

 
</script>
Paypal Express checkout