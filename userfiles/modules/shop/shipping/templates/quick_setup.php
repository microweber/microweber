<?php
$checkout_session = session_get('checkout');

if (isset($checkout_session['shipping_gw'])) {
    try {
        echo app()->shipping_manager->driver($checkout_session['shipping_gw'])->quickSetup($checkout_session);
    } catch (\Exception $e) {
        //
    }
}
?>
