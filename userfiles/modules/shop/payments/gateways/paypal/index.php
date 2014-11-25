<script type="text/javascript">
    function paypal_checkout_callback(data,selector){
    	//alert('paypal_checkout_callback');
    	$(selector).empty().append(data);
    }
</script>
<?php

$paypal_is_test = (get_option('paypalexpress_testmode', 'payments')) == 'y';

?>

<div>
    <p class="alert alert-warning"><small><strong> *<?php _e("Note"); ?> </strong><?php _e("Your shopping cart will be emptied when you complete the order"); ?></small> </p>
</div>




<?php if($paypal_is_test == true and is_admin()): ?>
 <?php print notif("You are using Paypal Express in test mode!"); ?>
<?php endif; ?>