<?php

$controller = new Microweber\rating\Controller($app);


if (isset($params['action']) and method_exists($controller, $params['action'])) {
    $controller->$params['action']($params);
} else {
    $controller->index($params);

}