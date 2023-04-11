<?php

/*

type: layout

name: Default

description: Default

*/
?>

<?php  if($shipping_collection_instructions){ ?>

<div class="card mb-4">
    <div class="checkout-v2-shipping-collection card-body">
        <?php
        print $shipping_collection_instructions;
        ?>
    </div>
</div>

<?php } ?>
