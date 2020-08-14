<?php
if (!user_can_access('module.comments.index')) {
    return;
}

$display = new \Microweber\Comments\Controllers\Admin();

return $display->manage($params);
