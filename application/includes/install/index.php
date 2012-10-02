<?php

defined('T') or die();
$installed = MW_IS_INSTALLED;

if ($installed != false) {
    if (is_admin() == true) {

    } else {
        exit('Microweber seems to be already installed!');
    }
}
 
$done = false;
$to_save = $_REQUEST;
 
if (isset($to_save)) {
    if (isset($to_save['IS_INSTALLED'])) {
        $f = INCLUDES_PATH . 'install' . DIRECTORY_SEPARATOR . 'config.base.php';
        $save_config = file_get_contents($f);

        if (isset($to_save['db_type']) and ($to_save['db_type']) == 'sqlite') {
            if (isset($to_save['db_file'])) {
                if ($to_save['db_file'] == 'db_file_new') {
                    $temtxt = 'db_';
                    if (function_exists('gethostname')) {
                        $temtxt = $temtxt . gethostname();
                    }


                    $dbf1 = INCLUDES_PATH . 'install' . DIRECTORY_SEPARATOR . 'default.db';
                    $new_db = $temtxt . '_' . date("Ymd-his") . '_.db';
					 $dbf2_full = ROOTPATH. DS.DBPATH . DS ;
					  $dbf2_full = normalize_path( $dbf2_full, 1);
                    $dbf2 = $dbf2_full . $new_db;
                    if (!is_dir($dbf2_full)) {
                        mkdir_recursive($dbf2_full);
                    }
					//d($dbf2_full);
                    if (copy($dbf1, $dbf2)) {
                        $to_save['db_file'] = $new_db;
                        $to_save['dsn'] = 'sqlite:' . DBPATH . $new_db;
                    }
                } else {
                    $to_save['dsn'] = 'sqlite:' . DBPATH . $to_save['db_file'];
                }
            }
        }



        if (isset($to_save['custom_dsn'])) {
            if (trim($to_save['custom_dsn']) != '') {
                $to_save['dsn'] = $to_save['custom_dsn'];
            }
        }
		
		
		
		if (isset($to_save['test'])) {
		
			
		}
			
        //$to_save['IS_INSTALLED'] = 'yes';





 $save_config_orig =  $save_config ;
        foreach ($to_save as $k => $v) {
            $save_config = str_ireplace('{' . $k . '}', $v, $save_config);
        }
        $cfg = APPPATH_FULL . 'config.php';
		//var_dump( $cfg);
 


       /*  file_put_contents($cfg, $save_config);
        clearcache();
		clearstatcache();
		sleep(2);*/
		
		
		if (isset($to_save['IS_INSTALLED']) and $to_save['IS_INSTALLED'] == 'no') {
			$temp_db = array(
        //'dsn' => 'mysql:host=localhost;port=3306;dbname=mw_install',
        // 'dsn' => 'sqlite:db/default.db',
        'host' => $to_save['DB_HOST'],
        'dbname' => $to_save['dbname'],
        'user' => $to_save['DB_USER'],
        'pass' => $to_save['DB_PASS']
    );
			
			//var_dump(MW_IS_INSTALLED);
			
			$qs = "SELECT '' AS empty_col";
			//var_dump($qs);
			$qz = db_query($qs,  $cache_id = false, $cache_group = false, $only_query = false, $temp_db );
			if(isset($qz['error'])){
				var_dump($qz); 
			//	var_dump('asdasdasdasd'); 
				
			} else {
				ini_set('memory_limit', '512M');
set_time_limit ( 0 );








$dbms_schema =  INCLUDES_PATH . 'install' . DIRECTORY_SEPARATOR . 'default.sql';;

$sql_query = fread(fopen($dbms_schema, 'r'), filesize($dbms_schema)) or die('problem ');
$sql_query = sql_remove_remarks($sql_query);

$sql_query = sql_remove_comments($sql_query);
$sql_query = split_sql_file($sql_query, ';');
 

$i=1;
foreach($sql_query as $sql){ 
	//$sql = str_ireplace('{dbname}', $to_save['dbname'], $sql); 
 $qz = db_q($sql, $temp_db);
 // var_dump($qz);  
}
			}
			  $save_config  =  $save_config_orig;
			   $to_save['IS_INSTALLED'] = 'yes';
			  foreach ($to_save as $k => $v) {
            $save_config = str_ireplace('{' . $k . '}', $v, $save_config);
        }
			 
			
			          file_put_contents($cfg, $save_config);
clearstatcache();
clearcache();
			 
			 
		  print ('done');
	exit();
	 
			
			//var_dump($_REQUEST);
			//$l = db_query_log(true);
			//var_dump($l);
		} else {
			     $done = true;
		   $f = INCLUDES_PATH . 'install' . DIRECTORY_SEPARATOR . 'done.php';
    include($f);
	exit();
		}
		
		
   
        //  var_dump($save_config);
    }

 if (!isset($to_save['IS_INSTALLED'])) {
	  $f = INCLUDES_PATH . 'install' . DIRECTORY_SEPARATOR . 'main.php';
    include($f);
 }

   
}
