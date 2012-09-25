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
$to_save = $_GET;

if (isset($to_save)) {
    if (isset($to_save['submit']) and isset($to_save['db_type'])) {
        $f = INCLUDES_PATH . 'install' . DIRECTORY_SEPARATOR . 'config.base.php';
        $save_config = file_get_contents($f);

        if (($to_save['db_type']) == 'sqlite') {
            if (isset($to_save['db_file'])) {
                if ($to_save['db_file'] == 'db_file_new') {
                    $temtxt = 'db_';
                    if (function_exists('gethostname')) {
                        $temtxt = $temtxt . gethostname();
                    }


                    $dbf1 = INCLUDES_PATH . 'install' . DIRECTORY_SEPARATOR . 'default.db';
                    $new_db = $temtxt . '_' . date("Ymd-his") . '_.db';
                    $dbf2_full = ROOTPATH . DS . DBPATH . DS;
                    $dbf2_full = normalize_path($dbf2_full, 1);
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
        $to_save['IS_INSTALLED'] = 'yes';






        foreach ($to_save as $k => $v) {
            $save_config = str_ireplace('{' . $k . '}', $v, $save_config);
        }
        $cfg = APPPATH . 'config.php';



        file_put_contents($cfg, $save_config);
        clearcache();
        $done = true;
        $f = INCLUDES_PATH . 'install' . DIRECTORY_SEPARATOR . 'done.php';
        include($f);
        exit();
        //  var_dump($save_config);
    }



    $f = INCLUDES_PATH . 'install' . DIRECTORY_SEPARATOR . 'main.php';
    include($f);
}
