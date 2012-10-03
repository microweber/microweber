<?


document_ready('stats_append_image');

function stats_append_image($layout) {
	
	//<table(?=[^>]*class="details")[^>]*>
//$selector = '<body>';
 $selector = '/<\/body>/si';

 $layout = modify_html($layout, $selector, 'stats_append_image() : image here from stats module ' , 'prepend');

//   $layout = modify_html($layout, $selector = '.editor_wrapper', 'append', 'ivan');
    //$layout = modify_html2($layout, $selector = '<div class="editor_wrapper">', '');
    return $layout;
}



if(!defined('STATS_MODULE_NAME')){
	define("STATS_MODULE_NAME", 'site_stats');
	
}


function mw_install_stats_module($config = false){
	$this_dir = dirname(__FILE__);
	
	$sql= $this_dir. DS.'install.sql';
	$cfg= $this_dir. DS.'config.php';
	 
	
	$is_installed = db_table_exist(TABLE_PREFIX.'stats_users_online');
	//d($is_installed);
	if($is_installed == false){
$install = 	import_sql_from_file($sql);
		return true;
		
 	
		
	} elseif(is_array($is_installed) and !empty($is_installed)) {
		return true;
	} else {
		
	return false;	
	}
	//d($install);
	 
}
 
 
 
 function mw_uninstall_stats_module(){
	 $table = TABLE_PREFIX.'stats_users_online';
	$q = "DROP TABLE IF EXISTS {$table}; ";
	d($q);
	
	db_q($q );
	
	
 }