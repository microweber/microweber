<?php
if (!user_can_access('module.comments.index')) {
    return;
}

$display = new \Microweber\Comments\Controllers\Admin();

if (isset($params['view']) and method_exists($display, $params['view'])) {
    $view = $params['view'];
    return $display->$view($params);
}

return $display->index($params);




 