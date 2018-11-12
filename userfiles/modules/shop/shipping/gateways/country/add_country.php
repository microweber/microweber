<?php


use Microweber\View;


include __DIR__ . "/_admin_data.php";



only_admin_access();


$view_file = __DIR__ . DS . 'views/item_edit.php';
$view = new View($view_file);
$view->assign('params', $params);
$view->assign('is_new', 1);
$view->assign('config', $config);
$view->assign('countries', $countries);
$view->assign('countries_used', $countries_used);
$view->assign('has_data', $has_data);
print $view->display();
