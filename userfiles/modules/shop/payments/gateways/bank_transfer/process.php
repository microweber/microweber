<?php

$html = _e('Thank you for your order', true);

if (get_option('bank_transfer_show_instructions', 'payments') == 'y') {
    $html .= _e("<br /><br />Follow payment instructions", true);
    $html .= '<br />' . get_option('bank_transfer_instructions', 'payments');
}

$twig = new \Twig_Environment(new \Twig_Loader_String());
$html = $twig->render($html, array('order_id' => $place_order['id']));

$place_order['order_completed'] = 1;
$place_order['is_paid'] = 0;
$place_order['success'] = $html;