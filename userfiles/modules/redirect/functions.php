<?php
/**
 * Dev: Bozhidar Slaveykov
 * Emai: bobi@microweber.com
 * Date: 11/18/2019
 * Time: 10:26 AM
 */

function get_browsers_options()
{
    $browsers = array();
    $browsers['chrome'] = 'Google Chrome';
    $browsers['safari'] = 'Apple Safari';
    $browsers['opera'] = 'Opera';
    $browsers['firefox'] = 'Mozilla Firefox';
    $browsers['internet_explorer'] = 'Internet Explorer';
    $browsers['microsoft_edge'] = 'Microsoft Edge';

    return $browsers;
}

function redirect_if_not_supported()
{
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: http://www.example.com/');
    exit();
}
function get_browsers_redirect()
{
    $browsers = get_option('browsers_redirect', 'redirect');
    $browsers = explode(',', $browsers);

    return $browsers;
}