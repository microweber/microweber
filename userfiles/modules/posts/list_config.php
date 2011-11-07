<? 

$config = array();
$config['name'] = "Posts list";
$config['author'] = "Microweber";
$config['no_cache'] = true;

$config['params']['category']['name'] = "Select category";
$config['params']['category']['help'] = "Select category to show content from";
$config['params']['category']['type'] = "category_selector";
$config['params']['category']['default'] = "0";
$config['params']['category']['param'] = "category";


$config['params']['no_results_text']['name'] = "No results text";
$config['params']['no_results_text']['help'] = "Text to show when nothing is found.";
$config['params']['no_results_text']['type'] = "text";
$config['params']['no_results_text']['default'] = "Nothing found";
$config['params']['no_results_text']['param'] = "no_results_text";




$config['params']['limit']['name'] = "Limit";
$config['params']['limit']['help'] = "Select limit of posts to show";
$config['params']['limit']['type'] = "number";
$config['params']['limit']['default'] = "30";
$config['params']['limit']['param'] = "limit";

