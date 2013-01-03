<?php

api_expose('mw_install_new_from_remote');

function mw_install_new_from_remote($updates) {
    $a = is_admin();
    if ($a == false) {
        error('Must be admin!');
    }

    $new_updates = array();
    if (isset($updates['data'])) {
        $u = urldecode($updates['data']);
        $u = unserialize($u);

        if ($u['action'] == 'install') {
            $update = array();

            if (isset($u['name'])) {
                $update[$u['type']][$u['name']] = $u['url'];
                $new_updates = $update;

                mw_apply_updates($new_updates);
            }
        }
    }
}

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
        // d($updates);
        // d($what_nex);
        if (isset($updates[$what_nex])) {

            foreach ($updates[$what_nex] as $key => $value) {

                $loc_fn = url_title($value) . '.zip';
                $loc_fn_d = $down_dir2 . $loc_fn;

                if (!is_file($loc_fn_d)) {
                    $loc_fn_d1 = url_download($value, false, $loc_fn_d);
                }
                if (is_file($loc_fn_d)) {
                    $to_be_unzipped[$what_nex][$key] = $loc_fn_d;
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
                $unzip_loc = MW_ROOTPATH;
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

                foreach ($value as $key2 => $value2) {
                    $value2 = normalize_path($value2, 0);

                    $unzip = new Unzip();
                    $a = $unzip->extract($value2, $unzip_loc);
                    $unzip->close();
                    $unzipped = array_merge($a, $unzipped);
                    //  d($unzipped);
                    //  d($unzip_loc);
                    // d($value2);

                    if ($key == 'modules') {
                        install_module($key2);
                    }
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

    $serv = mw_get_update_serv();

    $p = url_download($serv, $data);

    $h = site_hostname();
    $iudates = $p = (json_decode($p, true));
    $lic_path = DBPATH_FULL . 'lic' . DS . $h . DS;
    if (!is_dir($lic_path)) {
        mkdir_recursive($lic_path);
    }
    if (isset($iudates["license_check"]) and isset($iudates["license_check"]["modules"])) :
        $lic_modified = false;
        foreach ($iudates["license_check"]["modules"] as $item) :
            if (isset($item['host']) and $item['host'] == $h) {
                $var_lic = $item;
                $var_lic = encrypt_var($var_lic, $h);

                $lic_file = $lic_path . $item['module'] . '.php';
                $lic_file_d = dirname($lic_file);
                if (!is_dir($lic_file_d)) {
                    mkdir_recursive($lic_file_d);
                }
                $content = CACHE_CONTENT_PREPEND . $var_lic;
                // var_dump ( $cache_file, $content );
                try {
                    $cache = file_put_contents($lic_file, $content);
                    $lic_modified = true;
                } catch (Exception $e) {

                }

                //                d($var_lic);
                //                $var_lic = decode_var($var_lic, $h);
                //                d($var_lic);
            }
        endforeach;

        if ($lic_modified == true) {
            cache_clean_group('updates');
            cache_clean_group('modules');
            cache_clean_group('elements');
        }
    endif;

    return ($p);
}

function mw_get_update_serv() {
    $servs = explode(' ', MW_UPDATE_SERV);
    $servs_return = array();
    if (!empty($servs)) {
        $servs = array_trim($servs);
        foreach ($servs as $value) {
            if ($value != '') {
                $servs_return[] = $value;
            }
        }
    }
    if (!empty($servs_return)) {
        shuffle($servs_return);
        return $servs_return[0];
    }
}
