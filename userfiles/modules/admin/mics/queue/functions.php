<?php

event_bind('mw.admin.footer', function ($item) {
	echo '<div type="admin/mics/queue/queue_process" class="mw-lazy-load-module"></div>';
});
