<?php
only_admin_access();


$display = new \Microweber\SiteStats\Controllers\Admin();

if (isset($params['view']) and method_exists($display, $params['view'])) {
    $view = $params['view'];
    return $display->$view($params);
}

return $display->index($params);
