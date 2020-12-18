<?php

if (!isset($params)) {
    $params = array();
}

$set_content_type_from_opt = false;
 

if (isset($params['parent-module-id'])) {
	$set_content_type_from_opt = get_option('data-content-type', $params['parent-module-id']);

}


 

if (isset($params['content_type'])) {

} elseif (isset($params['content-type'])) {
    $params['content_type'] = $params['content-type'];
} else if ($set_content_type_from_opt != false and  $set_content_type_from_opt != '' and  $set_content_type_from_opt != '') {
    $set_content_type = $set_content_type_from_opt;
    $params['content_type'] = $set_content_type;
}

 
$manager = new content\controllers\Manager();

if (isset($params['view'])) {
    if (method_exists($manager, $params['view'])) {
        $m = $params['view'];
        return $manager->$m($params);
    }
}
return $manager->index($params);







