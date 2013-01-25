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
	if (!defined('MW_TABLE_PREFIX')) {

		define('MW_TABLE_PREFIX', null);

	}
}

include_once (MW_APPPATH_FULL . 'functions' . DIRECTORY_SEPARATOR . 'url.php');
include_once (MW_APPPATH_FULL . 'functions' . DIRECTORY_SEPARATOR . 'api.php');

include_once (MW_APPPATH_FULL . 'functions' . DIRECTORY_SEPARATOR . 'utils.php');
include_once (MW_APPPATH_FULL . 'functions' . DIRECTORY_SEPARATOR . 'cache.php');
include_once (MW_APPPATH_FULL . 'functions' . DIRECTORY_SEPARATOR . 'users.php');
include_once (MW_APPPATH_FULL . 'functions' . DIRECTORY_SEPARATOR . 'db.php');
include_once (MW_APPPATH_FULL . 'functions' . DIRECTORY_SEPARATOR . 'options.php');

if (MW_IS_INSTALLED == true) {
	exec_action('mw_db_init_default');
	exec_action('mw_db_init_options');
	exec_action('mw_db_init_users');
}
//	exec_action('mw_db_init_options');
include_once (MW_APPPATH_FULL. 'functions' . DIRECTORY_SEPARATOR . 'ui.php');

include_once (MW_APPPATH_FULL. 'functions' . DIRECTORY_SEPARATOR . 'content.php');
include_once (MW_APPPATH_FULL. 'functions' . DIRECTORY_SEPARATOR . 'custom_fields.php');

include_once (MW_APPPATH_FULL. 'functions' . DIRECTORY_SEPARATOR . 'categories.php');

include_once (MW_APPPATH_FULL. 'functions' . DIRECTORY_SEPARATOR . 'menus.php');
include_once (MW_APPPATH_FULL. 'functions' . DIRECTORY_SEPARATOR . 'templates.php');
include_once (MW_APPPATH_FULL. 'functions' . DIRECTORY_SEPARATOR . 'media.php');
include_once (MW_APPPATH_FULL. 'functions' . DIRECTORY_SEPARATOR . 'modules.php');
if (MW_IS_INSTALLED == true) {
	exec_action('mw_db_init_modules');
}

include_once (MW_APPPATH_FULL. 'functions' . DIRECTORY_SEPARATOR . 'history.php');
include_once (MW_APPPATH_FULL. 'functions' . DIRECTORY_SEPARATOR . 'language.php');
include_once (MW_APPPATH_FULL. 'functions' . DIRECTORY_SEPARATOR . 'forms.php');
include_once (MW_APPPATH_FULL. 'functions' . DIRECTORY_SEPARATOR . 'updates.php');
include_once (MW_APPPATH_FULL. 'functions' . DIRECTORY_SEPARATOR . 'fx.php');

// require (MW_APPPATH_FULL. 'functions' . DIRECTORY_SEPARATOR . 'users.php');
// require (MW_APPPATH_FULL. 'functions' . DIRECTORY_SEPARATOR . 'dashboard.php');
// require (MW_APPPATH_FULL. 'functions' . DIRECTORY_SEPARATOR . 'cart.php');
include_once (MW_APPPATH_FULL. 'functions' . DIRECTORY_SEPARATOR . 'parser.php');
// require (MW_APPPATH_FULL. 'functions' . DIRECTORY_SEPARATOR . 'forms.php');
if (defined('MW_IS_INSTALLED') and MW_IS_INSTALLED == true) {
	$module_functions = get_all_functions_files_for_modules();
	if ($module_functions != false) {
		if (is_array($module_functions)) {
			foreach ($module_functions as $item) {
				if (is_file($item)) {
					include_once ($item);
				}
			}
		}
	}
	if (MW_IS_INSTALLED == true) {
		exec_action('mw_db_init');
		//exec_action('mw_cron');
	}
}

/*
 require (MW_APPPATH_FULL. 'classes' . DIRECTORY_SEPARATOR . 'AggregateAutoloader.php');

 $loader = new AggregateAutoloader;
 $loader -> addLibrary('Modules', MODULES_DIR);
 $loader -> addLibrary('modules', MODULES_DIR);
 $loader -> register();*/

// d($module_functions);
