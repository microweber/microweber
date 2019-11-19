<?php
/**
 * Dev: Bozhidar Slaveykov
 * Emai: bobi@microweber.com
 * Date: 11/18/2019
 * Time: 10:26 AM
 */

$config = array();
$config['name'] = "Browser Redirect";
$config['link'] = "https://microweber.com";
$config['description'] = "Redirecting website by browser.";
$config['author'] = "Bozhidar Slaveykov";
$config['author_website'] = "http://microweber.com/";
$config['ui'] = false;
$config['ui_admin'] = true;
// $config['categories'] = "other";
$config['position'] = "100";
$config['version'] = 0.01;

$config['tables'] = array(
    'browser_redirects' => array(
        'id' => 'integer',
        'redirect_from_url' => 'string',
        'redirect_to_url' => 'string',
        'redirect_code' => 'string',
        'redirect_browsers' => 'text',
        'active' => 'integer',
        'created_by' => 'integer',
        'created_at' => 'dateTime',
    )
);