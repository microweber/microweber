<script type="text/javascript">
    function paypal_checkout_callback(data,selector){
    	alert('paypal_checkout_callback');
    	$(selector).empty().append(data);
    }
</script>
<?php

$paypal_is_test = (mw('option')->get('paypalexpress_testmode', 'payments')) == 'y';

?>

<div class="edit" rel="page" field="paypal_checkout_html">
    <p><b><?php _e("Thank you for your order"); ?>.</b></p>
    <p><?php _e("Please complete your order"); ?>. </p>
    <p class="alert alert-warning"><small style="font-size: x-small"><strong> *<?php _e("Note"); ?> </strong><?php _e("Your shopping cart will be emptied when you complete the order"); ?></small> </p>
</div>




<?php if($paypal_is_test == true and is_admin()): ?>
  <small><?php print mw('format')->notif("You are using Paypal Express in test mode!"); ?></small>
<?php endif; ?>