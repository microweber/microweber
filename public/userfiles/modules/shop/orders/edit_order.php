<?php


$manager = new shop\orders\controllers\Admin();

if (isset($params['view'])) {
    if (method_exists($manager, $params['view'])) {
        $m = $params['view'];
        return $manager->$m($params);
    }
}
return $manager->edit_order($params);
