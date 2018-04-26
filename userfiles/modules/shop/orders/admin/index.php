<?php


$manager = new shop\orders\controllers\Admin();
if (isset($params['order-type']) and $params['order-type'] == 'carts') {
    $params['view'] = 'abandoned_carts';
}
if (isset($params['view'])) {
    if (method_exists($manager, $params['view'])) {
        $m = $params['view'];
        return $manager->$m($params);
    }
}
return $manager->index($params);



