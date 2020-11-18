<?php

namespace MicroweberPackages\Order\Http\Controllers;



use Microweber\View;

class AdminOrdersController
{
    public function admin($params = false)
    {
        var_dump($params);


    }
    public function index($params = false)
    {
        return 'SDasdsad';
       // dump($_REQUEST);
      //dump(1111111111);
      // dump( modules_path().'shop/orders/manage.php');
        $view  = new View(normalize_path(modules_path().'shop/orders/manage.php',false));
        $view->assign('params',$params);
        return $view->display(true);
     //  return include (normalize_path(modules_path().'shop/orders/manage.php',false));
    }
}