<?php

//To Enable custom routes please rename to this file to routes.php
//edit your routes bellow

//you can route by creating anonymous functions of the controller;
$controller->myroute_test = function () {
    echo "Hello world!";
};

//you can route by widldcard;
$controller->functions['test/route/*'] = function () {
    echo "You can use wildcards!";
};

//you can route to your custom code
$controller->functions['mw_unit_test'] = function () {
    $url_params = url_params();
    if (!isset($url_params[1])) {
        $method = 'index';
    } else {
        $method = $url_params[1];
    }

    $my_controller = new MwExample();
    if (method_exists($my_controller, $method)) {

        $my_controller->$method();
    } elseif (method_exists($my_controller, 'index')) {

        $my_controller->index();
    }
    return $my_controller;

};


?>