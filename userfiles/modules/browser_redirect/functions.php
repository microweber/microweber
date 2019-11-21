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

function get_browser_redirects($onlyActive = false)
{
    $filter = array();
    $filter['limit'] = 100;
    if ($onlyActive) {
        $filter['active'] = 1;
    }

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

    if (!isset($_POST['redirect_code']) || empty(trim($_POST['redirect_code']))) {
        return array('error'=>'Select redirect code.');
    }

    if (!isset($_POST['redirect_browsers']) || empty($_POST['redirect_browsers'])) {
        return array('error'=>'Please select, redirect browsers.');
    }

    $save = array();
    if (!empty($_POST['redirect_browsers']) && is_array($_POST['redirect_browsers'])) {
        $save['redirect_browsers'] = implode(',', $_POST['redirect_browsers']);
    }

    if (isset($_POST['active']) && trim($_POST['active']) == 'y') {
        $save['active'] = 1;
    } else {
        $save['active'] = 0;
    }

    $save['redirect_code'] = trim($_POST['redirect_code']);
    $save['redirect_to_url'] = trim($_POST['redirect_to_url']);
    $save['redirect_from_url'] = trim($_POST['redirect_from_url']);

    if (isset($_POST['id'])) {
        $save['id'] = (int) trim($_POST['id']);
    }

    $id = db_save('browser_redirects', $save);

    return array('success'=>'The browser redirect is saved.', 'id'=>$id);

});


event_bind('mw.pageview', function() {

    $redirectBrowsers = array();
    $redirectCode = false;
    $redirectUrl = false;
    $startRedirecting = false;
    $urlSegment = mw()->url_manager->string();
    $userAgent = false;
    $browserName = false;
    $redirects = get_browser_redirects(true);

    if (empty($redirects) && !is_array($redirects)) {
        return;
    }

    if (isset($_SERVER['HTTP_USER_AGENT'])) {
        $userAgent = htmlentities($_SERVER['HTTP_USER_AGENT'], ENT_QUOTES, 'UTF-8');
    }
    if ($userAgent) {
        $browserName = get_browser_name($userAgent);
    }

    foreach ($redirects as $redirect) {

        $detectedSegment = false;

        if($redirect['redirect_from_url'] == "*" && $urlSegment !== $redirect['redirect_to_url']) {
            $detectedSegment = true;
        }

        if($redirect['redirect_from_url'] == "/" && $urlSegment == '') {
            $detectedSegment = true;
        }

        if("/" .$redirect['redirect_from_url'] == $urlSegment) {
            $detectedSegment = true;
        }

        if($redirect['redirect_from_url'] == $urlSegment) {
            $detectedSegment = true;
        }

        if($redirect['redirect_from_url'] == "/".$urlSegment) {
            $detectedSegment = true;
        }

        if ($detectedSegment) {
            $redirectCode = $redirect['redirect_code'];
            $redirectUrl = $redirect['redirect_to_url'];
            $redirectBrowsers = explode(',', $redirect['redirect_browsers']);
            break;
        }
    }

    if (empty($redirectBrowsers) && !is_array($redirectBrowsers)) {
        return;
    }

    if ($browserName && in_array($browserName, $redirectBrowsers)) {
        $startRedirecting = true;
    }

    if ($startRedirecting && $redirectUrl) {
        header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
        if ($redirectCode) {
            header('HTTP/1.1 ' . $redirectCode);
        }
        header('Location: ' . $redirectUrl);
        exit;
    }

    return;
});

function get_browser_name($userAgent)
{
    $t = strtolower($userAgent);
    $t = " " . $t;

    if (strpos($t, 'opera') || strpos($t, 'opr/')) return 'opera';
    elseif (strpos($t, 'edge')) return 'microsoft_edge';
    elseif (strpos($t, 'chrome')) return 'chrome';
    elseif (strpos($t, 'safari')) return 'safari';
    elseif (strpos($t, 'firefox')) return 'firefox';
    elseif (strpos($t, 'msie') || strpos($t, 'trident/7')) return 'internet_explorer';

    return 'unknown';
}