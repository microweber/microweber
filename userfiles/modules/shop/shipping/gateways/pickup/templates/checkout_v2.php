<?php

/*

type: layout

name: Default

description: Default

*/
?>

<?php  if($shipping_pickup_instructions){ ?>

    MANQK: userfiles\modules\shop\shipping\gateways\pickup\templates\checkout_v2.php
<?php

print $shipping_pickup_instructions;
?>

<?php } ?>
