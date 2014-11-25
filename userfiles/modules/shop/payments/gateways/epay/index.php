<script type="text/javascript">
    function epay_checkout_callback(data,selector){
    	//alert('epay_checkout_callback');
    	$(selector).empty().append(data);
    }
</script>
<?php

$epay_is_test = (get_option('epayexpress_testmode', 'payments')) == 'y';
$kin = get_option('epayexpress_username', 'payments');
?>

<div>
    <p class="alert alert-warning"><small><strong> *<?php _e("Note"); ?> </strong><?php _e("Your shopping cart will be emptied when you complete the order"); ?></small> </p>
</div>




<?php if($epay_is_test == true and is_admin()): ?>
 <?php print notif("You are using epay Express in test mode!"); ?>
<?php endif; ?>