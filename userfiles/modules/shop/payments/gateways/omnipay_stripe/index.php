<?php

$stripe_is_checkout = (get_option('stripe_checkout', 'payments')) == 'y';

if($stripe_is_checkout == 'y') {
?>
	<div>
		<p class="alert alert-info"><small><strong> *<?php _e("Note"); ?> </strong>Payment details will be taken via the Stripe Checkout</small> </p>
	</div>

<?php
} else {

	include(dirname(__DIR__).DS.'lib'.DS.'omnipay'.DS.'cc_form_fields.php');

}