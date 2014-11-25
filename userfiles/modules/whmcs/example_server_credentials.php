<?php
# Please rename example_server_credentials.php to server_credentials.php 


$whm_config = array();
$whm_config["url"] =  "http://microweber.com/members/includes/api.php"; # URL to WHMCS API file
$whm_config["mw_api_url"] =  "http://microweber.com/members/mw.php"; # URL to MW API file
$whm_config["username"] = 'api';
$whm_config["password"] = 'api_pass';
 
 
$whm_config["autoauthkey"] = 'myoathkey'; //see http://docs.whmcs.com/AutoAuth
$whm_config["autoauthkey_login_url"] =  "http://mysite.com/members/dologin.php"; # URL to MW API file
