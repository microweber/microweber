<?php
must_have_access();
use MicroweberPackages\View\View;

include __DIR__ . "/_admin_data.php";

$item = array();
if (isset($params['edit_id'])) {
    $shipping_express = mw('shop\shipping\gateways\express\shipping_express');
    $item = $shipping_express->get('single=1&id=' . $params['edit_id']);
}

$view_file = __DIR__ . DS . 'views/item_edit.php';
$view = new View($view_file);
$view->assign('params', $params);

$view->assign('config', $config);
$view->assign('countries', $countries);
$view->assign('countries_used', $countries_used);
$view->assign('has_data', $has_data);
$view->assign('item', $item);


print $view->display();
