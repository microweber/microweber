<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 2/13/2020
 * Time: 11:22 AM
 */

/*api_expose('posts/get', function($params) {

    $limit = 5;

    // $params['tags'] = 'hrdhrdhrd';
    $params['id'] = 'blog';
    $params['paging_param'] = 'pg';
    $params['pg'] = $params['page'];

    if (!isset($params['limit'])) {
        $params['limit'] = $limit;
    }

    $params['return_as_array'] = 1;
    $params['order_by'] = 'id asc';

    $controller = new content\controllers\Front();
    $output = $controller->index($params, [
        'module'=>'posts'
    ]);

    header("Content-Type: application/json;charset=utf-8");

    return $output;

});*/