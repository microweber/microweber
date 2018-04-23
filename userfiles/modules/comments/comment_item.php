<?php


only_admin_access();



$display = new \Microweber\Comments\Controllers\Admin();


return $display->comment_item($params);




