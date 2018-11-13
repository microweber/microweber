<?php

only_admin_access();

use Microweber\View;



include __DIR__ . "/_admin_data.php";



$item = array();
if(isset($params['edit_id'])){
    $shipping_to_country = mw('shop\shipping\gateways\country\shipping_to_country');
    $item = $shipping_to_country->get('single=1&id='.$params['edit_id']);
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
