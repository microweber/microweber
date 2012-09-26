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
            $update[$u['type']][] = $u['url'];
            $new_updates = $update;

            mw_apply_updates($new_updates);
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
                  //  $unzip->close();
                    $unzipped = array_merge($a,$unzipped);
                     d($unzipped);
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

    return (json_decode($p, true));
}

function check_license($licensekey, $localkey = "") {
    $whmcsurl = "http://microweber.com/members/";
    $licensing_secret_key = "abc123"; # Unique value, should match what is set in the product configuration for MD5 Hash Verification
    $check_token = time() . md5(mt_rand(1000000000, 9999999999) . $licensekey);
    $checkdate = date("Ymd"); # Current date
    $usersip = isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : $_SERVER['LOCAL_ADDR'];
    $localkeydays = 15; # How long the local key is valid for in between remote checks
    $allowcheckfaildays = 5; # How many days to allow after local key expiry before blocking access if connection cannot be made
    $localkeyvalid = false;
    if ($localkey) {
        $localkey = str_replace("\n", '', $localkey); # Remove the line breaks
        $localdata = substr($localkey, 0, strlen($localkey) - 32); # Extract License Data
        $md5hash = substr($localkey, strlen($localkey) - 32); # Extract MD5 Hash
        if ($md5hash == md5($localdata . $licensing_secret_key)) {
            $localdata = strrev($localdata); # Reverse the string
            $md5hash = substr($localdata, 0, 32); # Extract MD5 Hash
            $localdata = substr($localdata, 32); # Extract License Data
            $localdata = base64_decode($localdata);
            $localkeyresults = unserialize($localdata);
            $originalcheckdate = $localkeyresults["checkdate"];
            if ($md5hash == md5($originalcheckdate . $licensing_secret_key)) {
                $localexpiry = date("Ymd", mktime(0, 0, 0, date("m"), date("d") - $localkeydays, date("Y")));
                if ($originalcheckdate > $localexpiry) {
                    $localkeyvalid = true;
                    $results = $localkeyresults;
                    $validdomains = explode(",", $results["validdomain"]);
                    if (!in_array($_SERVER['SERVER_NAME'], $validdomains)) {
                        $localkeyvalid = false;
                        $localkeyresults["status"] = "Invalid";
                        $results = array();
                    }
                    $validips = explode(",", $results["validip"]);
                    if (!in_array($usersip, $validips)) {
                        $localkeyvalid = false;
                        $localkeyresults["status"] = "Invalid";
                        $results = array();
                    }
                    if ($results["validdirectory"] != dirname(__FILE__)) {
                        $localkeyvalid = false;
                        $localkeyresults["status"] = "Invalid";
                        $results = array();
                    }
                }
            }
        }
    }
    if (!$localkeyvalid) {
        $postfields["licensekey"] = $licensekey;
        $postfields["domain"] = $_SERVER['SERVER_NAME'];
        $postfields["ip"] = $usersip;
        $postfields["dir"] = dirname(__FILE__);
        if ($check_token)
            $postfields["check_token"] = $check_token;
        if (function_exists("curl_exec")) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $whmcsurl . "modules/servers/licensing/verify.php");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $data = curl_exec($ch);
            curl_close($ch);
        } else {
            $fp = fsockopen($whmcsurl, 80, $errno, $errstr, 5);
            if ($fp) {
                $querystring = "";
                foreach ($postfields AS $k => $v) {
                    $querystring .= "$k=" . urlencode($v) . "&";
                }
                $header = "POST " . $whmcsurl . "modules/servers/licensing/verify.php HTTP/1.0\r\n";
                $header.="Host: " . $whmcsurl . "\r\n";
                $header.="Content-type: application/x-www-form-urlencoded\r\n";
                $header.="Content-length: " . @strlen($querystring) . "\r\n";
                $header.="Connection: close\r\n\r\n";
                $header.=$querystring;
                $data = "";
                @stream_set_timeout($fp, 20);
                @fputs($fp, $header);
                $status = @socket_get_status($fp);
                while (!@feof($fp) && $status) {
                    $data .= @fgets($fp, 1024);
                    $status = @socket_get_status($fp);
                }
                @fclose($fp);
            }
        }
        if (!$data) {
            $localexpiry = date("Ymd", mktime(0, 0, 0, date("m"), date("d") - ($localkeydays + $allowcheckfaildays), date("Y")));
            if ($originalcheckdate > $localexpiry) {
                $results = $localkeyresults;
            } else {
                $results["status"] = "Invalid";
                $results["description"] = "Remote Check Failed";
                return $results;
            }
        } else {
            preg_match_all('/<(.*?)>([^<]+)<\/\\1>/i', $data, $matches);
            $results = array();
            foreach ($matches[1] AS $k => $v) {
                $results[$v] = $matches[2][$k];
            }
        }
        if ($results["md5hash"]) {
            if ($results["md5hash"] != md5($licensing_secret_key . $check_token)) {
                $results["status"] = "Invalid";
                $results["description"] = "MD5 Checksum Verification Failed";
                return $results;
            }
        }
        if ($results["status"] == "Active") {
            $results["checkdate"] = $checkdate;
            $data_encoded = serialize($results);
            $data_encoded = base64_encode($data_encoded);
            $data_encoded = md5($checkdate . $licensing_secret_key) . $data_encoded;
            $data_encoded = strrev($data_encoded);
            $data_encoded = $data_encoded . md5($data_encoded . $licensing_secret_key);
            $data_encoded = wordwrap($data_encoded, 80, "\n", true);
            $results["localkey"] = $data_encoded;
        }
        $results["remotecheck"] = true;
    }
    unset($postfields, $data, $matches, $whmcsurl, $licensing_secret_key, $checkdate, $usersip, $localkeydays, $allowcheckfaildays, $md5hash);
    return $results;
}
