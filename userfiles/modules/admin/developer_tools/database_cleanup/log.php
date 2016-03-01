<?php

$job = mw('admin\developer_tools\database_cleanup\Worker')->run();

if (is_array($job) and !empty($job) and isset($job['success'])){
    print $job['success'];
}
