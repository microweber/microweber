<? 

$config = array();
$config['name'] = "Categories";
$config['author'] = "Microweber";
$config['no_cache'] = true;
$config['ui'] = true;


$config['params']['title']['name'] = "Box title";
$config['params']['title']['help'] = "Set a title";
$config['params']['title']['type'] = "text";
$config['params']['title']['default'] = "Categories";
$config['params']['title']['param'] = "title";



$config['params']['content_subtype_value']['name'] = "Main category";
$config['params']['content_subtype_value']['help'] = "Choose a category to build the navigation tree";
$config['params']['content_subtype_value']['type'] = "category_selector";
$config['params']['content_subtype_value']['default'] = "";
$config['params']['content_subtype_value']['param'] = "content_subtype_value";


 