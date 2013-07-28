<?php
/*!
 * HybridAuth
 * http://hybridauth.sourceforge.net | http://github.com/hybridauth/hybridauth
 * (c) 2009-2012, HybridAuth authors | http://hybridauth.sourceforge.net/licenses.html
 */

// ----------------------------------------------------------------------------------------
//	HybridAuth Config file: http://hybridauth.sourceforge.net/userguide/Configuration.html
// ----------------------------------------------------------------------------------------
$enable_user_fb_registration = get_option('enable_user_fb_registration', 'users') == 'y';
$fb_app_id = get_option('fb_app_id', 'users');
$fb_app_secret = get_option('fb_app_secret', 'users');


$enable_user_google_registration = get_option('enable_user_google_registration', 'users') == 'y';
$google_app_id = get_option('google_app_id', 'users');
$google_app_secret = get_option('google_app_secret', 'users');


$enable_user_github_registration = get_option('enable_user_github_registration', 'users') == 'y';
$github_app_id = get_option('github_app_id', 'users');
$github_app_secret = get_option('github_app_secret', 'users');



$enable_user_twitter_registration = get_option('enable_user_twitter_registration', 'users') == 'y';
$twitter_app_id = get_option('twitter_app_id', 'users');
$twitter_app_secret = get_option('twitter_app_secret', 'users');
 
 
 
$enable_user_windows_live_registration = get_option('enable_user_windows_live_registration', 'users') == 'y';
$windows_live_app_id = get_option('windows_live_app_id', 'users');
$windows_live_app_secret = get_option('windows_live_app_secret', 'users');
 
 
$enable_user_yahoo_registration = get_option('enable_user_yahoo_registration', 'users') == 'y';
$yahoo_app_id = get_option('yahoo_app_id', 'users');
$yahoo_app_secret = get_option('yahoo_app_secret', 'users');
 
 
 
 
 
//$base_url_here = dirname(__FILE__);
//$base_url_here = dir2url($base_url_here).'/index.php';

$base_url_here =api_url('social_login_process');

$providers = array();
$providers['Facebook'] = array();
$providers['Facebook']["enabled"] = $enable_user_fb_registration;
$providers['Facebook']["keys"] = array("id" => $fb_app_id, "secret" => $fb_app_secret);
$providers['Google'] = array();
$providers['Google']["enabled"] = $enable_user_google_registration;
$providers['Google']["keys"] = array("id" => $google_app_id, "secret" => $google_app_secret);
$providers['Github'] = array();
$providers['Github']["enabled"] = $enable_user_github_registration;
$providers['Github']["keys"] = array("id" => $github_app_id, "secret" => $github_app_secret);
$providers['Twitter'] = array();
$providers['Twitter']["enabled"] = $enable_user_twitter_registration;
$providers['Twitter']["keys"] = array("key" => $twitter_app_id, "secret" => $twitter_app_secret);
$providers['Live'] = array();
$providers['Live']["enabled"] = $enable_user_windows_live_registration;
$providers['Live']["keys"] = array("id" => $windows_live_app_id, "secret" => $windows_live_app_secret);

$providers['Yahoo'] = array();
$providers['Yahoo']["enabled"] = $enable_user_yahoo_registration;
$providers['Yahoo']["keys"] = array("key" => $yahoo_app_id, "secret" => $yahoo_app_secret);

 
$config = array("base_url" => $base_url_here, "providers" => $providers, "debug_mode" => false, "debug_file" => MW_CACHE_ROOT_DIR.'fb.txt');

return $config;
