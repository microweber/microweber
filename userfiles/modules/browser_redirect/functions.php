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

api_expose_admin('browser_redirect_delete', function() {
    if (isset($_POST['id'])) {
        $id = (int) $_POST['id'];
        db_delete('browser_redirects', $id);
    }
});

api_expose_admin('browser_redirect_save', function () {

    if (!isset($_POST['redirect_from_url']) || empty(trim($_POST['redirect_from_url']))) {
        return array('error'=>'Redirect from url cannot be empty.');
    }

    if (!isset($_POST['redirect_to_url']) || empty(trim($_POST['redirect_to_url']))) {
        return array('error'=>'Redirect to url cannot be empty.');
    }

    if (!isset($_POST['error_code']) || empty(trim($_POST['error_code']))) {
        return array('error'=>'Select error code.');
    }

    if (!isset($_POST['redirect_browsers']) || empty(trim($_POST['redirect_browsers']))) {
        return array('error'=>'Please select, redirect browsers.');
    }

    $save = array();
    if (!empty($_POST['redirect_browsers']) && is_array($_POST['redirect_browsers'])) {
        $save['redirect_browsers'] = implode(', ', $_POST['redirect_browsers']);
    }
    $save['error_code'] = trim($_POST['error_code']);
    $save['redirect_to_url'] = trim($_POST['redirect_to_url']);
    $save['redirect_from_url'] = trim($_POST['redirect_from_url']);

    if (isset($_POST['id'])) {
        $save['id'] = (int) trim($_POST['id']);
    }

    $id = db_save('browser_redirects', $save);

    return array('success'=>'The browser redirect is saved.', 'id'=>$id);

});

/*
event_bind('mw.front', function() {

    $userAgent = false;

    if (isset($_SERVER['HTTP_USER_AGENT'])) {
        $userAgent = htmlentities($_SERVER['HTTP_USER_AGENT'], ENT_QUOTES, 'UTF-8');
    }



    var_dump($userAgent);
    die();

});*/