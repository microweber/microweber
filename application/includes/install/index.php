<?php

defined('T') or die();
$installed = MW_IS_INSTALLED;

if ($installed != false) {
	if (function_exists('is_admin') and is_admin() == false) {
		exit('Must be admin');
	}
}

$done = false;
$to_save = $_REQUEST;

if (isset($_POST['IS_INSTALLED'])) {

	if (isset($to_save['IS_INSTALLED'])) {
		$f = INCLUDES_PATH . 'install' . DIRECTORY_SEPARATOR . 'config.base.php';
		$save_config = file_get_contents($f);

		if (isset($to_save['custom_dsn'])) {
			if (trim($to_save['custom_dsn']) != '') {
				$to_save['dsn'] = $to_save['custom_dsn'];
			}
		}

		if (isset($to_save['test'])) {

		}

		//$to_save['IS_INSTALLED'] = 'yes';

		$save_config_orig = $save_config;
		foreach ($to_save as $k => $v) {
			$save_config = str_ireplace('{' . $k . '}', $v, $save_config);
		}
		$cfg = MW_CONFIG_FILE;
		//var_dump( $cfg);

		/*  file_put_contents($cfg, $save_config);
		 clearcache();
		 clearstatcache();
		 sleep(2);*/

		if (isset($to_save['IS_INSTALLED']) and $to_save['IS_INSTALLED'] == 'no') {
			$temp_db = array(
			//'dsn' => 'mysql:host=localhost;port=3306;dbname=mw_install',
			// 'dsn' => 'sqlite:db/default.db',
			'host' => $to_save['DB_HOST'], 'dbname' => $to_save['dbname'], 'user' => $to_save['DB_USER'], 'pass' => $to_save['DB_PASS']);

			//var_dump(MW_IS_INSTALLED);

			$qs = "SELECT '' AS empty_col";
			//var_dump($qs);
			$qz = db_query($qs, $cache_id = false, $cache_group = false, $only_query = false, $temp_db);
			if (isset($qz['error'])) {
				var_dump($qz);
				print('error');
			} else {
				ini_set('memory_limit', '512M');
				set_time_limit(0);

				$save_config = $save_config_orig;
				$to_save['IS_INSTALLED'] = 'no';
				foreach ($to_save as $k => $v) {
					$save_config = str_ireplace('{' . $k . '}', $v, $save_config);
				}
				// d($save_config);
				clearstatcache();
				clearcache();

				file_put_contents($cfg, $save_config);

				include_once (MW_APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'users.php');
				include_once (MW_APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'options.php');
				exec_action('mw_db_init_options');
				exec_action('mw_db_init_users');
				include_once (MW_APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'modules.php');
				exec_action('mw_db_init_default');
				exec_action('mw_db_init_modules');
				exec_action('mw_scan_for_modules');
 
				$save_config = $save_config_orig;
				$to_save['IS_INSTALLED'] = 'yes';
				foreach ($to_save as $k => $v) {
					$save_config = str_ireplace('{' . $k . '}', $v, $save_config);
				}

				file_put_contents($cfg, $save_config);

				// mw_create_default_content('install');
				print('done');

			}

			exit();

			//var_dump($_REQUEST);
			//$l = db_query_log(true);
			//var_dump($l);
		} else {
			$done = true;
			$f = INCLUDES_PATH . 'install' . DIRECTORY_SEPARATOR . 'done.php';
			include ($f);
			exit();
		}

		//  var_dump($save_config);
	}

}

if (!isset($to_save['IS_INSTALLED'])) {
	$cfg = MW_CONFIG_FILE;

	$data = false;
	if (is_file($cfg)) {
		$data =
		include ($cfg);
		//
	}

	$f = INCLUDES_PATH . 'install' . DIRECTORY_SEPARATOR . 'main.php';
	include ($f);
}
