<?php

include (MW_APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'url.php');
include (MW_APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'db.php');
if (MW_IS_INSTALLED == true) {

	include (MW_APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'api.php');

	include (MW_APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'utils.php');
	include (MW_APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'ui.php');
	include (MW_APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'users.php');

	include (MW_APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'cache.php');

	include (MW_APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'content.php');
	include (MW_APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'categories.php');

	include (MW_APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'options.php');
	exec_action('mw_db_init_options');
	include (MW_APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'menus.php');
	include (MW_APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'templates.php');
	include (MW_APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'media.php');
	include (MW_APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'modules.php');
	include (MW_APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'history.php');
	include (MW_APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'language.php');
	include (MW_APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'forms.php');
	include (MW_APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'updates.php');
	include (MW_APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'fx.php');

	// require (MW_APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'users.php');
	// require (MW_APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'dashboard.php');
	// require (MW_APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'cart.php');
	include (MW_APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'parser.php');
	// require (MW_APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'forms.php');
	if (defined('MW_IS_INSTALLED') and MW_IS_INSTALLED == true) {
		$module_functions = get_all_functions_files_for_modules();
		if ($module_functions != false) {
			if (is_array($module_functions)) {
				foreach ($module_functions as $item) {
					if (is_file($item)) {
						include ($item);
					}
				}
			}
		}

		exec_action('mw_db_init');
	}
}
// d($module_functions);
