<?php

//To Enable custom routes please rename routes.example.php to routes.php
//edit your routes bellow

//you can route by creating anonymous functions of the controller;
$controller->hello_world = function () {
    echo "Hello world!";
};

  

$controller->functions['test/route/*'] = function () {
    echo "You can use wildcards!";
};
 $controller->functions['test/api/user_login*'] = function () {
    echo "My user_login";
};
 

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