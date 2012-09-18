<?php

api_expose('mw_apply_updates');

function mw_apply_updates($updates) {
    $to_be_unzipped = array();
    $a = is_admin();
    if ($a == false) {
        error('Must be admin!');
    }
    $down_dir = CACHEDIR_ROOT . 'downloads' . DS;
    if (!is_dir($down_dir)) {
        mkdir_recursive($down_dir);
    }
    if (isset($updates['mw_new_version_download'])) {
        $loc_fn = url_title($updates['mw_new_version_download']) . '.zip';
        $loc_fn_d = $down_dir . $loc_fn;
        if (!is_file($loc_fn_d)) {
            $loc_fn_d1 = url_download($updates['mw_new_version_download'], false, $loc_fn_d);
        }
        if (is_file($loc_fn_d)) {
            $to_be_unzipped['root'][] = $loc_fn_d;
        }
        // d($loc_fn_d);
    }


    $what_next = array('modules', 'elements');



    foreach ($what_next as $what_nex) {
        $down_dir2 = $down_dir . $what_nex . DS;
        if (!is_dir($down_dir2)) {
            mkdir_recursive($down_dir2);
        }
        if (isset($updates[$what_nex])) {
            foreach ($updates[$what_nex] as $key => $value) {
                // d($value);
                $loc_fn = url_title($value) . '.zip';
                $loc_fn_d = $down_dir2 . $loc_fn;

                if (!is_file($loc_fn_d)) {
                    $loc_fn_d1 = url_download($value, false, $loc_fn_d);
                }
                if (is_file($loc_fn_d)) {
                    $to_be_unzipped[$what_nex][] = $loc_fn_d;
                }
            }
        }
    }
    $unzipped = array();
    if (!empty($to_be_unzipped)) {
        set_time_limit(0);
        foreach ($to_be_unzipped as $key => $value) {
            $unzip_loc = false;
            if ($key == 'root') {
                $unzip_loc = ROOTPATH;
            }

            if ($key == 'modules') {
                $unzip_loc = MODULES_DIR;
            }

            if ($key == 'elements') {
                $unzip_loc = ELEMENTS_DIR;
            }
           // $unzip_loc = CACHEDIR_ROOT;

            if ($unzip_loc != false and is_array($value) and !empty($value)) {
                $unzip_loc = normalize_path($unzip_loc);
                if (!is_dir($unzip_loc)) {
                    mkdir_recursive($unzip_loc);
                }
                foreach ($value as $value2) {
                    $value2 = normalize_path($value2, 0);


                    $unzip = new Unzip();
                    $a = $unzip->extract($value2, $unzip_loc);
                    $unzipped = array_merge($unzipped, $a);
                    //d($a);
                    //  d($unzip_loc);
                    // d($value2);
                }
            }
        }
    }
    clearcache();
    return $unzipped;
}

function mw_check_for_update() {

    $a = is_admin();
    if ($a == false) {
        error('Must be admin!');
    }

    $data = array();
    $data['mw_version'] = MW_VERSION;



    $t = templates_list();
    $data['templates'] = $t;

    $t = modules_list();
    $data['modules'] = $t;


    $t = get_elements();
    $data['elements'] = $t;


    $serv = 'http://update.microweber.com/update.php';
    $p = url_download($serv, $data);
     print $p;
    return (json_decode($p, true));
}