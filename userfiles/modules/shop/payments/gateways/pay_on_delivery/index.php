<?php

$pay_on_delivery_show_msg = (get_option('pay_on_delivery_show_msg', 'payments')) == 'y';
$pay_on_delivery_msg = get_option('pay_on_delivery_msg', 'payments');
?>
<?php if($pay_on_delivery_show_msg == true): ?>

<div>
  <p class="alert alert-warning"><small>
    <?php if($pay_on_delivery_msg == true): ?>
    <?php print $pay_on_delivery_msg; ?>
    <?php else: ?>
    <strong> *
    <?php _e("Note"); ?>
    </strong>
    <?php _e("Your shopping cart will be emptied when you complete the order"); ?>
    <?php endif; ?>
    </small> </p>
</div>
<?php endif; ?>
