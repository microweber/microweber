<?php
use MicroweberPackages\View\View;

must_have_access();

$shipping_express = mw('shop\shipping\gateways\express\shipping_express');

$data = $data_orig = $shipping_express->get();
if ($data == false) {
    $data = array();
}

$countries_used = array();

$countries = mw()->forms_manager->countries_list_from_json();

asort($countries);

$data_active = array();
$data_disabled = array();

foreach ($data as $item) {

    if (!$item['is_active']) {
        $data_disabled[] = $item;
    } else {
        $data_active[] = $item;
    }


    if (isset($item['shipping_country'])) {
        $countries_used[] = ($item['shipping_country']);
    }
}

$has_data = false;

if(!empty($data_active) OR !empty($data_disabled)){
    $has_data = true;
}
