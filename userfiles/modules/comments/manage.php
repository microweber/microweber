<?php
if (!user_can_access('module.comments.index')) {
    return;

}


//return redirect(route('admin.comment.index'));

$display = new \Microweber\Comments\Controllers\Admin();

return $display->manage($params);
