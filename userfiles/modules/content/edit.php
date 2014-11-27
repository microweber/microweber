<?php



namespace Microweber\content;
include_once(__DIR__.DS.'controllers'.DS.'Edit.php');
 

if (!isset($params)) {
    $params = array();
}


$manager = new controllers\Edit();

if (isset($params['view'])) {
    if (method_exists($manager, $params['view'])) {
        return $manager->$params['view']($params);
    }
}
return $manager->index($params);


