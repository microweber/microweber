<?php
 
$controller = new mvc_example_blade\Controller();

if(isset($params['view']) and method_exists($controller,$params['view'])){
    $view = $params['view'];
    print $controller->$view($params);
} else {
    print $controller->index($params);
}

