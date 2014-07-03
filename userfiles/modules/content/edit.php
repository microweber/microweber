<?php



namespace Microweber\Content;


if ($_SERVER['REMOTE_ADDR'] != "fe80::1058:77fe:80b6:de9f") {
    return include 'edit_old.php';
}

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


