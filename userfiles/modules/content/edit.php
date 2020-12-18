<?php



 
 

if (!isset($params)) {
    $params = array();
}


$manager = new content\controllers\Edit();

if (isset($params['view'])) {
    if (method_exists($manager, $params['view'])) {
        $m = $params['view'];
        return $manager->$m($params);
    }
}
return $manager->index($params);


