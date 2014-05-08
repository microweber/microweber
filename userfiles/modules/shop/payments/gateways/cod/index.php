<script type="text/javascript">
    function cod_checkout_callback(data,selector){
    	//alert('cod_checkout_callback');
    	$(selector).empty().append(data);
    }
</script>
<?php

$cod_is_test = (get_option('codexpress_testmode', 'payments')) == 'y';
$kin = get_option('codexpress_username', 'payments');
?>

<div>
    <p class="alert alert-warning"><small><strong> *<?php _e("Note"); ?> </strong><?php _e("Your shopping cart will be emptied when you complete the order"); ?></small> </p>
</div>




<?php if($cod_is_test == true and is_admin()): ?>
 <?php print notif("You are using cod Express in test mode!"); ?>
<?php endif; ?>