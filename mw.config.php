<?php

$mw_config = array ();
 
 

$mw_config ['site_url'] = 'http://192.168.0.2/Microweber'; //use slash at the end

 $mw_config ['system_folder'] = 'ci';
$mw_config ['application_folder'] = 'application';
 
$mw_config ['db_hostname'] = 'localhost';

$mw_config ['db_username'] = 'office';

$mw_config ['db_password'] = 'pecata123';  

 
$mw_config ['db_database'] = 'office';

ini_set('display_errors', '1');
 
error_reporting ( E_ALL & ~ E_NOTICE | E_STRICT );
?>