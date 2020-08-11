<?php
only_has_access();
$display = new \Microweber\Comments\Controllers\Admin();
return $display->comments_list($params);