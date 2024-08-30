<?php

if (!user_can_access('module.site_stats.index')) {
    return;
}

$display = new \MicroweberPackages\Modules\SiteStats\Controllers\Admin();

if (isset($params['view']) and method_exists($display, $params['view'])) {
    $view = $params['view'];
    return $display->$view($params);
}

return $display->index($params);
