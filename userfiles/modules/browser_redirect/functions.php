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
function get_browser_redirects()
{
    $filter = array();
    $filter['limit'] = 100;

    return db_get('browser_redirects', $filter);
}

event_bind('mw.front', function() {

    $userAgent = false;

    if (isset($_SERVER['HTTP_USER_AGENT'])) {
        $userAgent = htmlentities($_SERVER['HTTP_USER_AGENT'], ENT_QUOTES, 'UTF-8');
    }



    var_dump($userAgent);
    die();

});