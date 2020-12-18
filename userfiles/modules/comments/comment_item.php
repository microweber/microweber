<?php
must_have_access();

$display = new \Microweber\Comments\Controllers\Admin();

return $display->comment_item($params);
