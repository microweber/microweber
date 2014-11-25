<?php

namespace Microweber\content;


if (!isset($params)) {
    $params = array();
}

$manager = new controllers\Manager();

if (isset($params['view'])) {
    if (method_exists($manager, $params['view'])) {
        return $manager->$params['view']($params);
    }
}
return $manager->index($params);







