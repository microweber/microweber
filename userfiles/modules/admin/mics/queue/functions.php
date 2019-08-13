<?php

event_bind('mw.admin.footer', function ($item) {

    $check_queue = new \Microweber\Utils\QueueJob();
    if ($check_queue->size()) {
        echo '<div type="admin/mics/queue/queue_process" class="mw-lazy-load-module"></div>';
    }

});
