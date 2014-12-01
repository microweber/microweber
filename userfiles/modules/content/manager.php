<?php

if (!isset($params)) {
    $params = array();
}

$manager = new content\controllers\Manager();

if (isset($params['view'])) {
    if (method_exists($manager, $params['view'])) {
        return $manager->$params['view']($params);
    }
}
return $manager->index($params);







