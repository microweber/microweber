<?php

include __DIR__ . '/functions/sender_functions.php';
include __DIR__ . '/functions/template_functions.php';
include __DIR__ . '/functions/subscriber_functions.php';
include __DIR__ . '/functions/campaign_functions.php';
include __DIR__ . '/functions/list_functions.php';


event_bind('website.privacy_settings', function () {
    print '<module type="newsletter/privacy_settings" />';
});

