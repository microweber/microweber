<?php

if (defined("INI_SYSTEM_CHECK_DISABLED") == false) {
    define("INI_SYSTEM_CHECK_DISABLED", ini_get('disable_functions'));
}

defined('T') or die();
$installed = MW_IS_INSTALLED;

if ($installed != false) {
    if (function_exists('is_admin') and is_admin() == false) {
        exit('Must be admin');
    }
}


function __mw_install_log($text)
{
    if (defined('CACHEDIR_ROOT')) {
        if (!is_dir(CACHEDIR_ROOT)) {
            mkdir(CACHEDIR_ROOT);
        }
    }
    $log_file = CACHEDIR_ROOT . DIRECTORY_SEPARATOR . 'install_log.txt';
    if (!is_file($log_file)) {
        @touch($log_file);

    }
    if (is_file($log_file)) {
        if($text == 'done'){
            @file_put_contents($log_file, "[".date('H:i:s')."] "."\t".$text."<br>\n\r");

        } else {
            @file_put_contents($log_file, "[".date('H:i:s')."] "."\t".$text."<br>\n\r",FILE_APPEND);

        }
    }

}


$done = false;
$to_save = $_REQUEST;

if (isset($_POST['IS_INSTALLED'])) {
    __mw_install_log('Starting install');

    if (isset($to_save['IS_INSTALLED'])) {
        $f = INCLUDES_PATH . 'install' . DIRECTORY_SEPARATOR . 'config.base.php';
        $save_config = file_get_contents($f);
        __mw_install_log('Copying default config file');
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

        if (isset($to_save['IS_INSTALLED']) and $to_save['IS_INSTALLED'] != 'yes') {
            __mw_install_log('Testing database settings');

            if ($to_save['DB_PASS'] == '') {
                $temp_db = array('type' => $to_save['DB_TYPE'], 'host' => $to_save['DB_HOST'], 'dbname' => $to_save['dbname'], 'user' => $to_save['DB_USER']);
            } else {
                $temp_db = array('type' => $to_save['DB_TYPE'], 'host' => $to_save['DB_HOST'], 'dbname' => $to_save['dbname'], 'user' => $to_save['DB_USER'], 'pass' => $to_save['DB_PASS']);
            }


            // if($to_save['DB_USER'] == 'root'){

            // 				$new_db = $to_save['dbname'];
            // 				$query_make_db="CREATE DATABASE IF NOT EXISTS $new_db";
            // 				$qz = db_query($query_make_db, $cache_id = false, $cache_group = false, $only_query = false, $temp_db);
            // 			if (isset($qz['error'])) {
            // 						//	var_dump($qz);
            // 							print('Error with the database creation! ');
            // 						}

            // }


            $qs = "SELECT '' AS empty_col";
            //var_dump($qs);
            mw_var('temp_db', $temp_db);
            $qz = db_query($qs, $cache_id = false, $cache_group = false, $only_query = false, $temp_db);

            if (isset($qz['error'])) {
                __mw_install_log('Database Settings Error');

                _e("Error with the database connection or database probably does not exist!");
                exit();
            } else {

                if (!strstr(INI_SYSTEM_CHECK_DISABLED, 'ini_set')) {
                    ini_set('memory_limit', '512M');
                    ini_set("set_time_limit", 0);
                    __mw_install_log('Increasing server memory');
                }
                if (!strstr(INI_SYSTEM_CHECK_DISABLED, 'set_time_limit')) {
                    set_time_limit(0);
                }


                $save_config = $save_config_orig;
                $to_save['IS_INSTALLED'] = 'no';
                foreach ($to_save as $k => $v) {
                    $save_config = str_ireplace('{' . $k . '}', $v, $save_config);
                }
                // 

                $default_htaccess_file = MW_ROOTPATH . '.htaccess';
				
				
				
                $to_add_htaccess = true;
                if (is_file($default_htaccess_file)) {
                    $default_htaccess_file_c = file_get_contents($default_htaccess_file);
                    if (strstr($default_htaccess_file_c, 'mw htaccess')) {
                        $to_add_htaccess = false;
                    }
                }
				
				
				
				
				
				
                if ($to_add_htaccess == true) {
                    $f_htaccess = INCLUDES_PATH . 'install' . DIRECTORY_SEPARATOR . 'htaccess_mw.txt';
                    if (is_file($f_htaccess)) {
                        $f_htaccess_file_c = file_get_contents($f_htaccess);
                        if (strstr($f_htaccess_file_c, 'mw htaccess')) {
                            if (isset($_SERVER['SCRIPT_NAME'])) {
                                $dnht = dirname($_SERVER['SCRIPT_NAME']);
                            } else if (isset($_SERVER['PHP_SELF'])) {
                                $dnht = dirname($_SERVER['PHP_SELF']);
                            }


                            if (isset($dnht)) {
                                $dnht = str_replace('\\', '/', $dnht);
                                $dnht = str_replace(' ', '%20', $dnht);
                                if ($dnht != '/' and $dnht != DIRECTORY_SEPARATOR) {
                                    // $f_htaccess_file_c = str_ireplace('/your_sub_folder/', $dnht, $f_htaccess_file_c);

                                    $f_htaccess_file_c = str_ireplace('#RewriteBase /your_sub_folder/', 'RewriteBase ' . $dnht . '/', $f_htaccess_file_c);


                                }
                            }

                            __mw_install_log('Adding .htaccess');
                            file_put_contents($default_htaccess_file, $f_htaccess_file_c, FILE_APPEND);
                        }
                    }

                }
                 
				 if(isset($_SERVER["SERVER_SOFTWARE"])){
					 
					$sSoftware = strtolower( $_SERVER["SERVER_SOFTWARE"] );
					if ( stripos($sSoftware, "microsoft-iis") !== false or stristr($sSoftware, "microsoft-iis") !== false ){
						__mw_install_log($_SERVER["SERVER_SOFTWARE"]);
					     $default_webconfig_iis_file = MW_ROOTPATH . 'Web.config';
						 
							$to_add_webconfig_iis = true;
							if (is_file($default_webconfig_iis_file)) {
								$default_htaccess_file_c = file_get_contents($default_webconfig_iis_file);
								if (strstr($default_htaccess_file_c, '<action type="Rewrite" url="index.php" />')) {
									$to_add_webconfig_iis = false;
								}
							}
							
							
							
							__mw_install_log('Web.config check '. $to_add_webconfig_iis);
							
							
							
							if ($to_add_webconfig_iis == true) {
                    $f_htaccess = INCLUDES_PATH . 'install' . DIRECTORY_SEPARATOR . 'Web.config.txt';
                    if (is_file($f_htaccess)) {
						 $f_htaccess_c = file_get_contents($f_htaccess);
                       __mw_install_log('Adding Web.config');
                      file_put_contents($default_webconfig_iis_file,$f_htaccess_c, FILE_APPEND);
                    }

                }
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
					}
				 }
				
				
				
				
				
				
				
				
				
				
				
				
				
				__mw_install_log('Writing config file');
                file_put_contents($cfg, $save_config);
                __mw_install_log('Clearing cache');
                clearstatcache();
                clearcache();
                _reload_c();

                __mw_install_log('Initializing users');
                include_once (MW_APPPATH_FULL . 'functions' . DIRECTORY_SEPARATOR . 'users.php');
                __mw_install_log('Initializing options');

                include_once (MW_APPPATH_FULL . 'functions' . DIRECTORY_SEPARATOR . 'options.php');
                exec_action('mw_db_init_options');
                exec_action('mw_db_init_users');

                include_once (MW_APPPATH_FULL . 'functions' . DIRECTORY_SEPARATOR . 'modules.php');
                __mw_install_log('Creating default database tables');
                exec_action('mw_db_init_default');

                __mw_install_log('Creating modules database tables');
                exec_action('mw_db_init_modules');
                __mw_install_log('Loading modules');

                exec_action('mw_scan_for_modules');

                $save_config = $save_config_orig;
                $to_save['IS_INSTALLED'] = 'yes';
                foreach ($to_save as $k => $v) {
                    $save_config = str_ireplace('{' . $k . '}', $v, $save_config);
                }

                file_put_contents($cfg, $save_config);
                clearstatcache();
                _reload_c();
                __mw_install_log('Finalizing config file');


                if (isset($to_save['with_default_content'])) {
                    $default_content_folder = INCLUDES_PATH . 'install' . DIRECTORY_SEPARATOR;
                    $default_content_file = $default_content_folder . 'mw_default_content.zip';
                    if (is_file($default_content_file)) {
                        __mw_install_log('Installing default content');


                        define("MW_NO_DEFAULT_CONTENT", true);

                        $restore = new \mw\utils\Backup();
                        $restore->backups_folder = $default_content_folder;
                        $restore->backup_file = 'mw_default_content.zip';
                        ob_start();
                        $rest = $restore->exec_restore();


                        //mw_post_update();

                        ob_get_clean();
                        __mw_install_log('Default content is installed');

                        // exec_action('mw_scan_for_modules');
                        //d($to_save['with_default_content']);
                    }

                }


                // mw_create_default_content('install');
                print('done');
                __mw_install_log('done');

            }
            @session_write_close();
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
__mw_install_log('Preparing to install');
    $f = INCLUDES_PATH . 'install' . DIRECTORY_SEPARATOR . 'main.php';
    include ($f);
}
