<?php
api_expose_admin('fullpage-cache-open-iframe', function ($params) {

    $pageOpen = app()->url_manager->download(site_url());

    echo $pageOpen;
    
});
