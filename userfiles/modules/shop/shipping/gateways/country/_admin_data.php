<?php


use Microweber\View;


only_admin_access();


$shipping_to_country = mw('shop\shipping\gateways\country\shipping_to_country');


$data = $data_orig = $shipping_to_country->get();
if ($data == false) {
    $data = array();
}

$countries_used = array();
//$data[] = array();

$countries = mw()->forms_manager->countries_list();

if (is_array($countries)) {
    asort($countries);
}
if (!is_array($countries)) {
    $countries = mw()->forms_manager->countries_list(1);
} else {
    array_unshift($countries, "Worldwide");
}


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
