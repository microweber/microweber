<? 

$config = array();
$config['name'] = "Orders module";
$config['author'] = "Microweber";
$config['no_cache'] = true;
$config['ui'] = true;


/*$config['params']['title']['name'] = "Box title";
$config['params']['title']['help'] = "Set a title";
$config['params']['title']['type'] = "text";
$config['params']['title']['default'] = "Categories";
$config['params']['title']['param'] = "title";
*/


$config['options']['order_complete_email']['name'] = "Complete orders email subject";
$config['options']['order_complete_email']['help'] = "Complete orders email";
$config['options']['order_complete_email']['type'] = "text";
$config['options']['order_complete_email']['default'] = "Thank you for your order";
$config['options']['order_complete_email']['param'] = "order_complete_email";
$config['options']['order_complete_email']['group'] = "orders";
$config['options']['order_complete_email']['module'] = "orders";


$config['options']['order_complete_email_body']['name'] = "Complete orders email";
$config['options']['order_complete_email_body']['help'] = "Complete orders email";
$config['options']['order_complete_email_body']['type'] = "text";
$config['options']['order_complete_email_body']['default'] = "
We have successfully  accepted your order from the website {SITE_URL}  

Thank you! 

We will contact you with more information soon.

A detailed invoice can be viewed at:
{SITE_URL}

If you have any questions about how long your order will take, please peruse our shipping policies.";


$config['options']['order_complete_email_body']['param'] = "order_complete_email_body";
$config['options']['order_complete_email_body']['group'] = "orders";
$config['options']['order_complete_email_body']['module'] = "orders";





 

 