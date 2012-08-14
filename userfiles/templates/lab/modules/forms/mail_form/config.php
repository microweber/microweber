<?

$config = array();
$config['name'] = "Mailform module";
$config['author'] = "Microweber";
$config['description'] = "A simple contact form. Use it you you want to receive emails from your site.";
$config['website'] = "http://microweber.com";
$config['no_cache'] = true;
$config['ui'] = true;




$config['params']['email']['name'] = "Receive emails at";
$config['params']['email']['help'] = "Enter one or more emails on which to receive the form";
$config['params']['email']['type'] = "text";
$config['params']['email']['default'] = "your@email.com, my@email.com,";
$config['params']['email']['param'] = "email";
