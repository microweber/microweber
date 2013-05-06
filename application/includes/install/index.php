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
		if (!isset($to_save['DB_TYPE'])) {
		$to_save['DB_TYPE'] = 'mysql';
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
			
			if($to_save['DB_PASS'] == ''){
				$temp_db = array('type' => $to_save['DB_TYPE'],'host' => $to_save['DB_HOST'], 'dbname' => $to_save['dbname'], 'user' => $to_save['DB_USER']);
			} else {
				$temp_db = array('type' => $to_save['DB_TYPE'],'host' => $to_save['DB_HOST'], 'dbname' => $to_save['dbname'], 'user' => $to_save['DB_USER'], 'pass' => $to_save['DB_PASS']);
			}
			

			 
 
			$qs = "SELECT '' AS empty_col";
			//var_dump($qs);
			$qz = db_query($qs, $cache_id = false, $cache_group = false, $only_query = false, $temp_db);
			 
			if (isset($qz['error'])) {
			//	var_dump($qz);
				print('Error with the database connection or database probably does not exist!');
			} else {
				ini_set('memory_limit', '512M');
				set_time_limit(0);

				$save_config = $save_config_orig;
				$to_save['IS_INSTALLED'] = 'no';
				foreach ($to_save as $k => $v) {
					$save_config = str_ireplace('{' . $k . '}', $v, $save_config);
				}
				 // d($save_config);
				
				$default_htaccess_file = MW_ROOTPATH .  '.htaccess';
				$to_add_htaccess = true;
				if(is_file($default_htaccess_file)){
				$default_htaccess_file_c = file_get_contents($default_htaccess_file);	
				 if(strstr($default_htaccess_file_c, 'mw htaccess')){
					 $to_add_htaccess = false;
				 }
				}
				if($to_add_htaccess  == true){
				$f_htaccess = INCLUDES_PATH . 'install' . DIRECTORY_SEPARATOR . 'htaccess_mw.txt';
					if(is_file($f_htaccess)){
					$f_htaccess_file_c = file_get_contents($f_htaccess);	
					 if(strstr($f_htaccess_file_c, 'mw htaccess')){
						 if(isset($_SERVER['SCRIPT_NAME'])){
							 $dnht = dirname($_SERVER['SCRIPT_NAME']);
						 } else if(isset($_SERVER['PHP_SELF'])){
							 $dnht = dirname($_SERVER['PHP_SELF']);
						 }
							 
							 
						if(isset($dnht)){ 
							 $dnht = str_replace('\\', '/', $dnht);
							 $dnht = str_replace(' ', '%20', $dnht);
							 if($dnht != '/' and $dnht !=DIRECTORY_SEPARATOR){
								// $f_htaccess_file_c = str_ireplace('/your_sub_folder/', $dnht, $f_htaccess_file_c);

							 $f_htaccess_file_c = str_ireplace('#RewriteBase /your_sub_folder/', 'RewriteBase '.$dnht.'/', $f_htaccess_file_c);

								 
							 }
						 }
						 
						  
						 file_put_contents($default_htaccess_file, $f_htaccess_file_c, FILE_APPEND);
					 }
					}
						
				}

				file_put_contents($cfg, $save_config);
			
				clearstatcache();
				clearcache();
				 _reload_c();
				
				
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
 				_reload_c();
				
				
				
				if (isset($to_save['with_default_content'])) {
						$default_content_folder = INCLUDES_PATH . 'install' . DIRECTORY_SEPARATOR;
						$default_content_file = $default_content_folder . 'mw_default_content.zip';  
          				if(is_file($default_content_file)){
							$restore = new \mw\utils\Backup;
							$restore->backups_folder =$default_content_folder;
							$restore->backup_file ='mw_default_content.zip';
							ob_start();
							$rest = $restore->exec_restore();
						    ob_get_clean();
						//d($to_save['with_default_content']);
						}
					
				}
			 
				
				
				
				
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
