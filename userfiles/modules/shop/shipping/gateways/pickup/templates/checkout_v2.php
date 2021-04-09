<?php

/*

type: layout

name: Default

description: Default

*/
?>

<?php  if($shipping_pickup_instructions){ ?>

<div class="card">
    <div class="checkout-v2-shipping-pickup card-body">
        <?php
        print $shipping_pickup_instructions;
        ?>
    </div>
</div>

<?php } ?>
