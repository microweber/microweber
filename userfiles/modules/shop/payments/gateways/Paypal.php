<script type="text/javascript">

function paypal_checkout_callback(data,selector){
	alert(data);
	$(selector).empty().append(data);	 
 
}

 
</script>
Paypal Express checkout