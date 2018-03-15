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

    if (isset($item['is_active']) and 0 == intval($item['is_active'])) {
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

$view_file = __DIR__ . DS . 'views/admin_add_shipping.php';
$view = new View($view_file);
$view->assign('params', $params);
$view->assign('config', $config);
$view->assign('countries', $countries);
$view->assign('countries_used', $countries_used);
$view->assign('has_data', $has_data);
print $view->display();


$view_file = __DIR__ . DS . 'views/admin_table_list.php';
$view = new View($view_file);
$view->assign('params', $params);
$view->assign('config', $config);
$view->assign('countries', $countries);
$view->assign('countries_used', $countries_used);
$view->assign('data', $data_active);
$view->assign('data_key', 'data_active');
$view->assign('active_or_disabled', 'active');

print $view->display();

$view_file = __DIR__ . DS . 'views/admin_table_list.php';
$view = new View($view_file);
$view->assign('params', $params);
$view->assign('config', $config);
$view->assign('countries', $countries);
$view->assign('countries_used', $countries_used);

$view->assign('data', $data_disabled);
$view->assign('data_key', 'data_disabled');
$view->assign('active_or_disabled', 'disabled');
print $view->display();



