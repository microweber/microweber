<?php
if (!user_can('module.comments.index')) {
    return;
}

$display = new \Microweber\Comments\Controllers\Admin();

return $display->manage($params);
