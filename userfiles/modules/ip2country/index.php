<? //include_once($config['path_to_module'].'functions.php'); ?>
<?
	$ip = USER_IP;
if(isset($params) and isset($params['ip'])){
	
	$ip = $params['ip'];

}
 
$ip2country = ip2country($ip);
d($ip2country);
 // $menu_name = get_option('menu_name', $params['id']);

 