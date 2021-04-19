<?php
use MicroweberPackages\View\View;

must_have_access();

$shipping_standard = mw('shop\shipping\gateways\standard\shipping_standard');

$data = $data_orig = $shipping_standard->get();
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
