<?php

api_expose_admin('digital_download_get');
function digital_download_get($params) {


    $download_url = get_option('download_url', $params['id']);
    if (!$download_url) {
        $download_url = '';
    }

    $email = $params['email'];
    if ($email) {
        
    }

    return [
        'download_url' => $download_url
    ];

}
