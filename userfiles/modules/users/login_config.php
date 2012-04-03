<? 

$config = array();
$config['name'] = "Login module";
$config['author'] = "Microweber";
$config['description'] = "A simple login";
$config['no_cache'] = true;
$config['ui'] = true;




$config['params']['enable_registration']['name'] = "Enable registration new user reg";
$config['params']['enable_registration']['help'] = "Enable registration new user reg";
$config['params']['enable_registration']['type'] = "dropdown";
$config['params']['enable_registration']['values'] = "yes,no";
$config['params']['enable_registration']['default'] = "yes";
$config['params']['enable_registration']['param'] = "enable_registration";
