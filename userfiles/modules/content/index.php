<?php
$params['global'] = 1;
$config['module'] = 'posts';
$set_content_type = 'post';
$set_content_type_from_opt = get_option('data-content-type', $params['id']);
if (isset($params['content_type'])) {

} elseif (isset($params['content-type'])) {
    $params['content_type'] = $params['content-type'];
} else if ($set_content_type_from_opt != false and  $set_content_type_from_opt != '') {
    $set_content_type = $set_content_type_from_opt;
    $params['content_type'] = $set_content_type;
}


$controller = new content\controllers\Front();
return $controller->index($params,$config);
 
//include($config['path_to_module'] . '../posts/index.php');
 
 