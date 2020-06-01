<?php

function antzz()
{
    exit('antzz is here!!!');
}




event_bind('mw.cart.checkout.order_paid', function ($order) {

    file_put_contents(__DIR__.'/debug.txt', print_r($order,1) . PHP_EOL . PHP_EOL, FILE_APPEND);
 });