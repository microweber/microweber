<?php

if (MW_IS_INSTALLED == true) {
	if (!defined('MW_TABLE_PREFIX')) {
		$pre = c('table_prefix');

		define('MW_TABLE_PREFIX', $pre);

	}

} elseif (isset($_POST['table_prefix'])) {

	if (!defined('MW_TABLE_PREFIX')) {

		define('MW_TABLE_PREFIX', strip_tags($_POST['table_prefix']));

	}

} else {


	if (!defined('MW_TABLE_PREFIX') and !isset($_REQUEST['autoinstall'])) {

		define('MW_TABLE_PREFIX', null);

	} else if (!defined('MW_TABLE_PREFIX')) {
        $pre = c('table_prefix');

        define('MW_TABLE_PREFIX', $pre);

    }


}

include_once (MW_APPPATH_FULL . 'functions' . DIRECTORY_SEPARATOR . 'url.php');
include_once (MW_APPPATH_FULL . 'functions' . DIRECTORY_SEPARATOR . 'api.php');

include_once (MW_APPPATH_FULL . 'functions' . DIRECTORY_SEPARATOR . 'utils.php');
/*
 if (isset($_COOKIE['debug'])) {
 include_once (MW_APPPATH_FULL . 'functions' . DIRECTORY_SEPARATOR . 'cache2.php');
 } else {
 include_once (MW_APPPATH_FULL . 'functions' . DIRECTORY_SEPARATOR . 'cache.php');

 }*/

include_once (MW_APPPATH_FULL . 'functions' . DIRECTORY_SEPARATOR . 'cache.php');

include_once (MW_APPPATH_FULL . 'functions' . DIRECTORY_SEPARATOR . 'users.php');
include_once (MW_APPPATH_FULL . 'functions' . DIRECTORY_SEPARATOR . 'db.php');
include_once (MW_APPPATH_FULL . 'functions' . DIRECTORY_SEPARATOR . 'options.php');

$c_id = 'mw_init_all';
//$cache_content_init = cache_get_content($c_id, 'db');
$cache_content_init = static_option_get('is_installed', 'mw_system');
 
//$cache_content_init = MW_IS_INSTALLED;
if (MW_IS_INSTALLED == true) {
	if ($cache_content_init != 'yes') {
		exec_action('mw_db_init_default');
		exec_action('mw_db_init_options');
		exec_action('mw_db_init_users');
	}
	$curent_time_zone = get_option('time_zone', 'website');
	if ($curent_time_zone != false and $curent_time_zone != '') {
		$default_time_zone = date_default_timezone_get();

		if ($default_time_zone != $curent_time_zone) {

			date_default_timezone_set($curent_time_zone);
		}

	}
	//d($curent_time_zone);
}
//	exec_action('mw_db_init_options');
//include_once (MW_APPPATH_FULL . 'functions' . DIRECTORY_SEPARATOR . 'ui.php');
//include_once (MW_APPPATH_FULL . 'functions' . DIRECTORY_SEPARATOR . 'common.php');

include_once (MW_APPPATH_FULL . 'functions' . DIRECTORY_SEPARATOR . 'custom_fields.php');

include_once (MW_APPPATH_FULL . 'functions' . DIRECTORY_SEPARATOR . 'content.php');

include_once (MW_APPPATH_FULL . 'functions' . DIRECTORY_SEPARATOR . 'categories.php');

include_once (MW_APPPATH_FULL . 'functions' . DIRECTORY_SEPARATOR . 'menus.php');
include_once (MW_APPPATH_FULL . 'functions' . DIRECTORY_SEPARATOR . 'templates.php');
include_once (MW_APPPATH_FULL . 'functions' . DIRECTORY_SEPARATOR . 'media.php');
include_once (MW_APPPATH_FULL . 'functions' . DIRECTORY_SEPARATOR . 'modules.php');
//include_once (MW_APPPATH_FULL . 'functions' . DIRECTORY_SEPARATOR . 'messages.php');

if (MW_IS_INSTALLED == true) {

	if ($cache_content_init != 'yes') {
		exec_action('mw_db_init_modules');
	}

}
include_once (MW_APPPATH_FULL . 'functions' . DIRECTORY_SEPARATOR . 'shop.php');

include_once (MW_APPPATH_FULL . 'functions' . DIRECTORY_SEPARATOR . 'history.php');
include_once (MW_APPPATH_FULL . 'functions' . DIRECTORY_SEPARATOR . 'language.php');
include_once (MW_APPPATH_FULL . 'functions' . DIRECTORY_SEPARATOR . 'forms.php');
include_once (MW_APPPATH_FULL . 'functions' . DIRECTORY_SEPARATOR . 'updates.php');
//include_once (MW_APPPATH_FULL . 'functions' . DIRECTORY_SEPARATOR . 'fx.php');
// require (MW_APPPATH_FULL. 'functions' . DIRECTORY_SEPARATOR . 'users.php');
// require (MW_APPPATH_FULL. 'functions' . DIRECTORY_SEPARATOR . 'dashboard.php');
// require (MW_APPPATH_FULL. 'functions' . DIRECTORY_SEPARATOR . 'cart.php');
include_once (MW_APPPATH_FULL . 'functions' . DIRECTORY_SEPARATOR . 'parser.php');
// require (MW_APPPATH_FULL. 'functions' . DIRECTORY_SEPARATOR . 'forms.php');
if (defined('MW_IS_INSTALLED') and MW_IS_INSTALLED == true) {
	$module_functions = get_all_functions_files_for_modules();
	if ($module_functions != false) {
		if (is_array($module_functions)) {
			foreach ($module_functions as $item) {
			//	if (is_file($item)) {

					include_once ($item);
				//}
			}
		}
	}
	if (MW_IS_INSTALLED == true) {

		if (($cache_content_init) == false) {
			exec_action('mw_db_init');
			//cache_save('true', $c_id, 'db');

			$installed = array();
			$installed['option_group'] = ('mw_system');
			$installed['option_key'] = ('is_installed');
			$installed['option_value'] = 'yes';
			static_option_save($installed);

		}

		//exec_action('mw_cron');
	}
}
//exec_action('mw_db_init');
/*
 require (MW_APPPATH_FULL. 'classes' . DIRECTORY_SEPARATOR . 'AggregateAutoloader.php');

 $loader = new AggregateAutoloader;
 $loader -> addLibrary('Modules', MODULES_DIR);
 $loader -> addLibrary('modules', MODULES_DIR);
 $loader -> register();*/

// d($module_functions);
