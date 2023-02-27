<?php


$posted_fields = array();
$flds_from_data = array();
if (isset($place_order['posted_fields'])) {
    $flds_from_data = $place_order['posted_fields'];
    unset($place_order['posted_fields']);
}

if (isset($place_order['posted_data'])) {
    $data = $place_order['posted_data'];
    unset($place_order['posted_data']);
}
if (isset($place_order['mw_payment_fields']) and $place_order['mw_payment_fields']) {

    extract($place_order['mw_payment_fields'], EXTR_PREFIX_SAME, "dup");
    unset($place_order['mw_payment_fields']);
}


if ($flds_from_data) {
    foreach ($flds_from_data as $value) {
        if (isset($data[$value]) and ($data[$value]) != false) {
            $place_order[$value] = $data[$value];
            $posted_fields[$value] = $data[$value];
        }
    }
}
