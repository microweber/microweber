<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 2/13/2020
 * Time: 11:22 AM
 */

api_expose('posts/get', function($params) {

    $request = array();

    if (isset($params['tags']) && !empty($params['tags'])) {
      $request['tags'] = $params['tags'];
    }
    $request['id'] = $params['id'];
    $request['paging_param'] = 'paging_number_page';
    $request['paging_number_page'] = $params['page'];
    $request['limit'] = get_option('data-limit', $params['data-id']);
    $request['return_as_array'] = 1;
    $orderBy = get_option('data-order-by', $params['data-id']);
    if ($orderBy) {
        $request['order_by'] = $orderBy;
    }

    $controller = new content\controllers\Front();
    $output = $controller->index($request, [
        'module'=>'posts'
    ]);

    return $output;

});