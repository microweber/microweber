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

    $request['id'] = 'blog';
    $request['paging_param'] = 'paging_number_page';
    $request['paging_number_page'] = $params['page'];
    $request['limit'] = get_option('data-limit', $params['data-id']);
    $request['return_as_array'] = 1;
    $request['order_by'] = get_option('data-order-by', $params['data-id']);

    $controller = new content\controllers\Front();
    $output = $controller->index($request, [
        'module'=>'posts'
    ]);

    return $output;

});