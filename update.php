<?

define('MW_BARE_BONES', 1);

include ('index.php');
if (!defined('MW_WHM_SERV')) {
    define('MW_WHM_SERV', 'http://microweber.com/members/');
}

if (!defined('MW_WHM_SERV')) {
    define('MW_WHM_SERV', 'http://microweber.com/members/');
}


if (!defined('MW_WHM_SERV_USER')) {
    define('MW_WHM_SERV_USER', 'Admin');
}
if (!defined('MW_WHM_SERV_PASS')) {
    define('MW_WHM_SERV_PASS', 'otivamnabali123z');
}
$downloads_dir = ROOTPATH . DS . 'download' . DS;

$seg = url_segment(1);

set_time_limit(0);

$root_dir_files_to_copy = array();
$root_dir_files_to_copy[] = 'index.php';
$root_dir_files_to_copy[] = 'bootstrap.php';
$root_dir_files_to_copy[] = '.htaccess';

if ($seg != false and $seg == 'download') {

    $segs = url_segment();
    $download_dir_get = array();
    for ($i = 2; $i < count($segs); $i++) {

        $download_dir_get[] = $segs[$i];
    }

    if (!empty($download_dir_get)) {
        $download_dir_get = implode(DS, $download_dir_get);
        $download_dir_get = str_replace('..', '', $download_dir_get);

        $download_dir_get = urldecode($download_dir_get);

        $download_dir_get0 = ROOTPATH . DS . 'download' . DS;
        $download_dir_get1 = ROOTPATH . DS . $download_dir_get;


        $download_dir_get1 = str_replace('..', '', $download_dir_get1);
        $download_dir_get1 = normalize_path($download_dir_get1, false);




        $seg_latest = url_segment(2);
        if ($seg_latest != false and ($seg_latest == 'latest' or $seg_latest == 'latest.zip')) {
            $download_dir_get_latest = $download_dir_get0 . 'microweber-' . MW_VERSION . '.zip';
            if (!is_file($download_dir_get_latest)) {
                $download_dir_get_v_dir2 = $download_dir_get0 . 'microweber-' . MW_VERSION . DS;
                $path_to_zip_dir = $download_dir_get_v_dir2;

                if (!is_dir($download_dir_get_v_dir2)) {
                    mkdir_recursive($download_dir_get_v_dir2);

                    $files_to_copy = $root_dir_files_to_copy;


                    if (!empty($files_to_copy)) {
                        foreach ($files_to_copy as $files_to_copy_item) {
                            $fn = basename($files_to_copy_item);
                            $fn2 = $download_dir_get_v_dir2 . $fn;

                            copy($files_to_copy_item, $fn2);
                        }
                    }








                    $new_app_p = $download_dir_get_v_dir2 . DS . APPPATH;
                    $new_app_p = normalize_path($new_app_p, 1);
                    if (!is_dir($new_app_p)) {
                        mkdir_recursive($new_app_p);
                    }

                    copy_directory(APPPATH_FULL, $new_app_p);

                    $crm_f = $new_app_p . DS . 'config.php';
                    if (is_file($crm_f)) {
                        unlink($crm_f);
                    }


                    $new_app_p = $download_dir_get_v_dir2 . DS . USERFILES_DIRNAME;
                    $new_app_p = normalize_path($new_app_p, 1);
                    if (!is_dir($new_app_p)) {
                        mkdir_recursive($new_app_p);
                    }

                    copy_directory(ROOTPATH . DS . USERFILES_DIRNAME, $new_app_p);
                }
                chdir($path_to_zip_dir);

                $dir = getcwd();

                Zip($dir, $download_dir_get_latest);

                //  d($dir);
            }
            if (is_file($download_dir_get_latest)) {
                $download_dir_get1 = $download_dir_get_latest;
            }
        }

        if ($seg_latest != false and ($seg_latest == 'latest-module')) {
            $seg_latest_m = url_segment();

            $what = array_slice($seg_latest_m, 4);
            $seg_latest_m_s = implode(DS, $what);
            $seg_latest_m_s1 = implode('-', $what);

            $downloads_dir_md = $downloads_dir . 'modules' . DS . $seg_latest_m_s;


            $files_to_copy_item_src = $downloads_dir_md = normalize_path($downloads_dir_md, 1);
            if (!is_dir($downloads_dir_md)) {
                mkdir_recursive($downloads_dir_md);
            }

            $version_zip = $downloads_dir_md . $seg_latest_m_s1 . '-latest.zip';
            if (!is_file($version_zip)) {
                // d($version_zip);




                $md_orig = MODULES_DIR . $seg_latest_m_s;
                $md_orig = normalize_path($md_orig, false);
                $md_orig_f = $md_orig . '.php';

                if (is_dir($md_orig)) {
                    $files_to_copy_item_srczas = $downloads_dir_md;
                    if (!is_dir($files_to_copy_item_srczas)) {
                        mkdir($files_to_copy_item_srczas);
                    }
                    copy_directory($md_orig, $files_to_copy_item_srczas);
                    $md_orig = $files_to_copy_item_src;
                    //print $md_orig;
                }
                if (is_file($md_orig_f)) {
                    $files_to_copy = array();
                    $files_to_copy[] = $md_orig_f;
                    $md_orig_f1 = $md_orig . '_config.php';
                    if (is_file($md_orig_f)) {
                        $files_to_copy[] = $md_orig_f1;
                    }

                    $md_orig_f1 = $md_orig . '.png';
                    if (is_file($md_orig_f)) {
                        $files_to_copy[] = $md_orig_f1;
                    }
                    if (!empty($files_to_copy)) {
                        foreach ($files_to_copy as $files_to_copy_item) {
                            $fn = basename($files_to_copy_item);
                            $fn2 = $files_to_copy_item_src . $fn;
                            //d($fn2);
                            copy($files_to_copy_item, $fn2);
                        }
                        $md_orig = $files_to_copy_item_src;
                    }
                    //d($files_to_copy);
                }



                if (is_dir($md_orig)) {
                    Zip($md_orig, $version_zip);
                    //print $md_orig;
                }
            }



            if (is_file($version_zip)) {
                $download_dir_get1 = $version_zip;
            }
        }

//latest.zip



        if (is_file($download_dir_get1)) {
            $fnb = basename($download_dir_get1);
            //header('Content-disposition: attachment; filename=' . $fnb);
            //header('Content-type: application/zip');

            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . $fnb);
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($download_dir_get1));
            ob_clean();
            flush();
            readfile($download_dir_get1);
            exit;

            //readfile($download_dir_get1);
            //exit();
            //d($download_dir_get1);
        } else {
            error('Invalid file');
        }
    }
} else {
    $req = $_REQUEST;
    asort($req);
    $data = array();
    foreach ($req as $k => $v) {
        if ($k == 'mw_version') {
            if (floatval(MW_VERSION) > floatval($v)) {
                $data['mw_version'] = MW_VERSION;
            }
        }
        if ($k == 'templates') {

            $t = templates_list();
            foreach ($req[$k] as $key => $value) {
                if (isset($value['name'])) {

                    foreach ($t as $keyt => $valuet) {
                        if ($valuet['name'] == $value['name']) {
                            if (isset($valuet['version']) and floatval($valuet['version']) > floatval($value['version'])) {
                                //d($valuet);
                                $data['templates'] = $valuet;
                            }
                            //
                        }
                    }
                }
            }
        }
        if ($k == 'modules') {

            $t = modules_list();

            foreach ($req[$k] as $key => $value) {
                if (isset($value['module'])) {

                    foreach ($t as $keyt => $valuet) {
                        $valuet['module'] = normalize_path($valuet['module']);
                        if ($valuet['module'] == normalize_path($value['module'])) {
                            //d($valuet);


                            if (isset($valuet['version']) and floatval($valuet['version']) > floatval($value['version'])) {

                                $data['modules'][] = $valuet;
                            }
                            //
                        }
                    }
                }
            }
        }
        if ($k == 'elements') {
            $t = get_elements();
            foreach ($req[$k] as $key => $value) {
                //d($value);
                if (isset($value['module'])) {

                    foreach ($t as $keyt => $valuet) {
                        $valuet['module'] = normalize_path($valuet['module']);
                        if ($valuet['module'] == normalize_path($value['module'])) {
                            if (isset($valuet['version']) and floatval($valuet['version']) > floatval($value['version'])) {

                                $data['elements'][] = $valuet;
                            }
                            //
                        }
                    }
                }
            }
            //$data['elements'] = $t;
        }
    }
    if (!is_dir($downloads_dir)) {
        mkdir($downloads_dir);
    }
    if (isset($data['mw_version'])) {

        $version_zip = $downloads_dir . 'microweber-' . $data['mw_version'] . '.zip';

        $downloads_dir_mw_v = $downloads_dir . $data['mw_version'] . DS . APPPATH;
        $downloads_dir_mw_v_z = $downloads_dir . $data['mw_version'] . DS;
        if (!is_dir($downloads_dir_mw_v)) {
            mkdir_recursive($downloads_dir_mw_v);
        }

        if (!is_file($version_zip)) {

            $d124 = ROOTPATH . DS . APPPATH;
            $d124 = normalize_path($d124, 0);

            copy_directory($d124, $downloads_dir_mw_v);



            $files_to_copy = $root_dir_files_to_copy;


            if (!empty($files_to_copy)) {
                foreach ($files_to_copy as $files_to_copy_item) {
                    $fn = basename($files_to_copy_item);
                    $fn2 = $downloads_dir_mw_v_z . $fn;

                    copy($files_to_copy_item, $fn2);
                }
                $md_orig = $files_to_copy_item_src;
            }
            $crm_f = $downloads_dir_mw_v . DS . 'config.php';
            if (is_file($crm_f)) {
                unlink($crm_f);
            }

            Zip($downloads_dir_mw_v_z, $version_zip);
        }

        $p = normalize_path($version_zip, false);
        $p = dirToUrl($p);
        $data['mw_new_version_download'] = $p;
    }
    $next_stuff = 'modules';
    if (isset($data[$next_stuff])) {
        $i = 0;
        foreach ($data[$next_stuff] as $item) {
            $downloads_dir_md = $downloads_dir . 'modules' . DS . $item['module'];
            $files_to_copy_item_src = $downloads_dir . 'modules' . DS . $item['module'] . DS . 'src' . DS . $item['version'] . DS;
            $files_to_copy_item_src = normalize_path($files_to_copy_item_src, 1);
            if (!is_dir($downloads_dir_md_src)) {
                mkdir_recursive($downloads_dir_md_src);
            }
            if (!is_dir($files_to_copy_item_src)) {
                mkdir_recursive($files_to_copy_item_src);
            }

            $version_zip = $downloads_dir_md . $item['version'] . '.zip';

            if (!is_file($version_zip)) {

                $md_orig = MODULES_DIR . $item['module'];
                $md_orig = normalize_path($md_orig, false);
                $md_orig_f = $md_orig . '.php';

                if (is_dir($md_orig)) {
                    $files_to_copy_item_srczas = $files_to_copy_item_src . DS . $item['module'];
                    if (!is_dir($files_to_copy_item_srczas)) {
                        mkdir($files_to_copy_item_srczas);
                    }
                    copy_directory($md_orig, $files_to_copy_item_srczas);
                    $md_orig = $files_to_copy_item_src;
                    //print $md_orig;
                }
                if (is_file($md_orig_f)) {
                    $files_to_copy = array();
                    $files_to_copy[] = $md_orig_f;
                    $md_orig_f1 = $md_orig . '_config.php';
                    if (is_file($md_orig_f)) {
                        $files_to_copy[] = $md_orig_f1;
                    }

                    $md_orig_f1 = $md_orig . '.png';
                    if (is_file($md_orig_f)) {
                        $files_to_copy[] = $md_orig_f1;
                    }
                    if (!empty($files_to_copy)) {
                        foreach ($files_to_copy as $files_to_copy_item) {
                            $fn = basename($files_to_copy_item);
                            $fn2 = $files_to_copy_item_src . $fn;
                            //d($fn2);
                            copy($files_to_copy_item, $fn2);
                        }
                        $md_orig = $files_to_copy_item_src;
                    }
                    //d($files_to_copy);
                }

                if (is_dir($md_orig)) {
                    Zip($md_orig, $version_zip);
                    //print $md_orig;
                }

                //Zip(APPPATH_FULL, $version_zip);
            }

            if (is_file($version_zip)) {

                $p = normalize_path($version_zip, false);
                $p = dirToUrl($p);
                //$data['mw_new_version_download'] = $p;
                $data[$next_stuff][$i]['mw_new_version_download'] = $p;
            }
            $i++;
        }
    }

    $next_stuff = 'elements';
    if (isset($data[$next_stuff])) {
        $i = 0;
        foreach ($data[$next_stuff] as $item) {
            $downloads_dir_md = $downloads_dir . 'elements' . DS . $item['module'];
            $files_to_copy_item_src = $downloads_dir . 'elements' . DS . $item['module'] . DS . 'src' . DS . $item['version'] . DS;
            $files_to_copy_item_src = normalize_path($files_to_copy_item_src, 1);
            if (!is_dir($downloads_dir_md_src)) {
                mkdir_recursive($downloads_dir_md_src);
            }
            if (!is_dir($files_to_copy_item_src)) {
                mkdir_recursive($files_to_copy_item_src);
            }

            $version_zip = $downloads_dir_md . $item['version'] . '.zip';

            if (!is_file($version_zip)) {

                $md_orig = ELEMENTS_DIR . $item['module'];
                $md_orig = normalize_path($md_orig, false);
                $md_orig_f = $md_orig . '.php';

                if (is_dir($md_orig)) {

                    $files_to_copy_item_srczas = $files_to_copy_item_src . DS . $item['module'];
                    if (!is_dir($files_to_copy_item_srczas)) {
                        mkdir($files_to_copy_item_srczas);
                    }
                    copy_directory($md_orig, $files_to_copy_item_srczas);
                    $md_orig = $files_to_copy_item_src;
                }
                if (is_file($md_orig_f)) {
                    $files_to_copy = array();
                    $files_to_copy[] = $md_orig_f;
                    $md_orig_f1 = $md_orig . '_config.php';
                    if (is_file($md_orig_f)) {
                        $files_to_copy[] = $md_orig_f1;
                    }

                    $md_orig_f1 = $md_orig . '.png';
                    if (is_file($md_orig_f)) {
                        $files_to_copy[] = $md_orig_f1;
                    }
                    if (!empty($files_to_copy)) {
                        foreach ($files_to_copy as $files_to_copy_item) {
                            $fn = basename($files_to_copy_item);
                            $fn2 = $files_to_copy_item_src . $fn;
                            //d($fn2);
                            copy($files_to_copy_item, $fn2);
                        }
                        $md_orig = $files_to_copy_item_src;
                    }
                    //d($files_to_copy);
                }

                if (is_dir($md_orig)) {
                    Zip($md_orig, $version_zip);
                    //print $md_orig;
                }

                //Zip(APPPATH_FULL, $version_zip);
            }

            if (is_file($version_zip)) {

                $p = normalize_path($version_zip, false);
                $p = dirToUrl($p);
                //$data['mw_new_version_download'] = $p;
                $data[$next_stuff][$i]['mw_new_version_download'] = $p;
            }
            $i++;
        }
    }


    $data['license_check'] = array();




    //  $shake = whm_mw_command('action=handshake');
    $shake = array();
    $shake['shake'] = true;
    if (isset($req['modules'])) {
        $shake = $shake['shake'];
        if (isset($_SERVER['HTTP_REFERER'])) {
            extract(parse_url($_SERVER['HTTP_REFERER']));
            $referer = $scheme . '://' . str_replace('www.', null, $host);
        }



        $data['temp'] = array();
        $postfields_all = array();
        $postfields_all['postfields_all'] = array();
        foreach ($req['modules'] as $item) {

            $postfields = array();
            $postfields["action"] = "validate_license";
            $postfields["valid_domain"] = $referer;
            $postfields["name"] = $item['module'];
            $postfields_all['postfields_all'][] = $postfields;
            //    $postfields["shake"] = $shake;
        }

        $lic_resp = whm_mw_command($postfields_all);
        if ($lic_resp != false and is_array($lic_resp)) {
            foreach ($lic_resp as $value) {
                if (isset($value['error']) and ($value['error'] == 'no_license_found') and isset($value['module'])) {

                    $data['license_check']['modules'][$value['module']] = $value;
                } elseif (isset($value['domainstatus'])) {

                    $data['license_check']['modules'][] = $value;
                } else {
                    $data['temp'][] = $value;
                }
            }
        }






        exit(print(json_encode($data)));
    } else {
       // $data = array();
       // $data["error"] = "cant_connect";

        exit(print(json_encode($data)));
    }
}
//d($data);
?>
<?

/**
 * whm_get_user
 * whm_get_user
 * @link whm_get_user
 * @param resource $ch
 * @return resource a new cURL handle.
 */
function whm_mw_command($params = false) {
    $params2 = array();
    if ($params == false) {
        $params = array();
    }
    if (is_string($params)) {
        $params = parse_str($params, $params2);
        $params = $params2;
    }
    $str = '?';
    if (!empty($params)) {
        $str .= http_build_query($params);
    }
    $u = MW_WHM_SERV . 'mw.php' . $str;
    $s = url_download($u);

    return json_decode($s, true);
}

/**
 * whm_get_user
 * whm_get_user
 * @link whm_get_user
 * @param resource $ch
 * @return resource a new cURL handle.
 */
function whm_command($params = false, $use_custom_api = false) {

    $params2 = array();
    if ($params == false) {
        $params = array();
    }
    if (is_string($params)) {
        $params = parse_str($params, $params2);
        $params = $params2;
    }
    if ($use_custom_api == false) {
        $url = MW_WHM_SERV . "includes/api.php"; # URL to WHMCS API file
    } else {
        $url = $use_custom_api; # URL to WHMCS API file
    }

    $username = MW_WHM_SERV_USER; # Admin username goes here
    $password = MW_WHM_SERV_PASS; # Admin password goes here
    $postfields = $params;
    $postfields["username"] = $username;
    $postfields["password"] = md5($password);

    $postfields["responsetype"] = "json";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 100);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
    $data = curl_exec($ch);
    curl_close($ch);

    $results = json_decode($data, true);
    return $results;
    if ($results["result"] == "success") {
        # Result was OK!
    } else {
        # An error occured
        echo "The following error occured: " . $results["message"];
    }
}

function check_license($licensekey, $localkey = "") {
    $whmcsurl = MW_WHM_SERV;
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

function Zip($source, $destination) {
    if (!extension_loaded('zip') || !file_exists($source)) {
        error('The PHP Zip extension is required!');
    }

    $zip = new ZipArchive();
    if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
        return false;
    }

    $source = str_replace('\\', '/', realpath($source));
    $source = normalize_path($source);
    if (is_dir($source) === true) {
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

        foreach ($files as $file) {
            $file = str_replace('\\', '/', $file);
            $file = normalize_path($file, false);

            // Ignore "." and ".." folders
            if (in_array(substr($file, strrpos($file, DS) + 1), array('.', '..', '.git', '.gitignore')))
                continue;

            $file = realpath($file);

            if (is_dir($file) === true) {
                $rel_d = str_replace($source, '', $file);

                $zip->addEmptyDir($rel_d);
            } else if (is_file($file) === true) {
                $rel_d = str_replace($source, '', $file);


                $zip->addFromString($rel_d, file_get_contents($file));
            }
        }
    } else if (is_file($source) === true) {
        $zip->addFromString(basename($source), file_get_contents($source));
    }

    return $zip->close();
}

?>
<?php

/**
 * UnZip Class
 *
 * This class is based on a library I found at PHPClasses:
 * http://phpclasses.org/package/2495-PHP-Pack-and-unpack-files-packed-in-ZIP-archives.html
 *
 * The original library is a little rough around the edges so I
 * refactored it and added several additional methods -- Phil Sturgeon
 *
 * This class requires extension ZLib Enabled.
 *
 * @author		Alexandre Tedeschi
 * @author		Phil Sturgeon
 * @link		http://bitbucket.org/philsturgeon/codeigniter-unzip
 * @license        http://www.gnu.org/licenses/lgpl.html
 * @version     1.0.0
 */
class Unzip {

    private $compressed_list = array();
    // List of files in the ZIP
    private $central_dir_list = array();
    // Central dir list... It's a kind of 'extra attributes' for a set of files
    private $end_of_central = array();
    // End of central dir, contains ZIP Comments
    private $info = array();
    private $error = array();
    private $_zip_file = '';
    private $_target_dir = FALSE;
    private $apply_chmod = 0755;
    private $fh;
    private $zip_signature = "\x50\x4b\x03\x04";
    // local file header signature
    private $dir_signature = "\x50\x4b\x01\x02";
    // central dir header signature
    private $central_signature_end = "\x50\x4b\x05\x06";
    // ignore these directories (useless meta data)
    private $_skip_dirs = array('__MACOSX');
    // Rename target files with underscore case
    private $underscore_case = TRUE;
    private $_allow_extensions = NULL;

    // What is allowed out of the zip
    // --------------------------------------------------------------------

    /**
     * Constructor
     *
     * @access    Public
     * @param     string
     * @return    none
     */
    function __construct() {

    }

    // --------------------------------------------------------------------

    /**
     * Unzip all files in archive.
     *
     * @access    Public
     * @param     none
     * @return    none
     */
    public function extract($zip_file, $target_dir = NULL, $preserve_filepath = TRUE) {
        $this->_zip_file = $zip_file;
        $this->_target_dir = $target_dir ? $target_dir : dirname($this->_zip_file);

        if (!$files = $this->_list_files()) {
            $this->set_error('ZIP folder was empty.');
            return FALSE;
        }

        $file_locations = array();
        foreach ($files as $file => $trash) {
            $dirname = pathinfo($file, PATHINFO_DIRNAME);
            $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

            $folders = explode('/', $dirname);
            $out_dn = $this->_target_dir . '/' . $dirname;

            // Skip stuff in stupid folders
            if (in_array(current($folders), $this->_skip_dirs)) {
                continue;
            }

            // Skip any files that are not allowed
            if (is_array($this->_allow_extensions) && $extension && !in_array($extension, $this->_allow_extensions)) {
                continue;
            }

            if (!is_dir($out_dn) && $preserve_filepath) {
                $str = "";
                foreach ($folders as $folder) {
                    $str = $str ? $str . '/' . $folder : $folder;
                    if (!is_dir($this->_target_dir . '/' . $str)) {
                        $this->set_debug('Creating folder: ' . $this->_target_dir . '/' . $str);

                        if (!@mkdir($this->_target_dir . '/' . $str)) {
                            $this->set_error('Desitnation path is not writable.');
                            return FALSE;
                        }

                        // Apply chmod if configured to do so
                        $this->apply_chmod && chmod($this->_target_dir . '/' . $str, $this->apply_chmod);
                    }
                }
            }

            if (substr($file, -1, 1) == '/') {
                continue;
            }

            $file_locations[] = $file_location = $this->_target_dir . '/' . ($preserve_filepath ? $file : basename($file));

            $this->_extract_file($file, $file_location, $this->underscore_case);
        }

        return $file_locations;
    }

    // --------------------------------------------------------------------

    /**
     * What extensions do we want out of this ZIP
     *
     * @access    Public
     * @param     none
     * @return    none
     */
    public function allow($ext = NULL) {
        $this->_allow_extensions = $ext;
    }

    // --------------------------------------------------------------------

    /**
     * Show error messages
     *
     * @access    public
     * @param    string
     * @return    string
     */
    public function error_string($open = '<p>', $close = '</p>') {
        return $open . implode($close . $open, $this->error) . $close;
    }

    // --------------------------------------------------------------------

    /**
     * Show debug messages
     *
     * @access    public
     * @param    string
     * @return    string
     */
    public function debug_string($open = '<p>', $close = '</p>') {
        return $open . implode($close . $open, $this->info) . $close;
    }

    // --------------------------------------------------------------------

    /**
     * Save errors
     *
     * @access    Private
     * @param    string
     * @return    none
     */
    function set_error($string) {
        $this->error[] = $string;
    }

    // --------------------------------------------------------------------

    /**
     * Save debug data
     *
     * @access    Private
     * @param    string
     * @return    none
     */
    function set_debug($string) {
        $this->info[] = $string;
    }

    // --------------------------------------------------------------------

    /**
     * List all files in archive.
     *
     * @access    Public
     * @param     boolean
     * @return    mixed
     */
    private function _list_files($stop_on_file = FALSE) {
        if (sizeof($this->compressed_list)) {
            $this->set_debug('Returning already loaded file list.');
            return $this->compressed_list;
        }

        // Open file, and set file handler
        $fh = fopen($this->_zip_file, 'r');
        $this->fh = &$fh;

        if (!$fh) {
            $this->set_error('Failed to load file: ' . $this->_zip_file);
            return FALSE;
        }

        $this->set_debug('Loading list from "End of Central Dir" index list...');

        if (!$this->_load_file_list_by_eof($fh, $stop_on_file)) {
            $this->set_debug('Failed! Trying to load list looking for signatures...');

            if (!$this->_load_files_by_signatures($fh, $stop_on_file)) {
                $this->set_debug('Failed! Could not find any valid header.');
                $this->set_error('ZIP File is corrupted or empty');

                return FALSE;
            }
        }

        return $this->compressed_list;
    }

    // --------------------------------------------------------------------

    /**
     * Unzip file in archive.
     *
     * @access    Public
     * @param     string, boolean, boolean
     * @return    Unziped file.
     */
    private function _extract_file($compressed_file_name, $target_file_name = FALSE, $underscore_case = FALSE) {
        if (!sizeof($this->compressed_list)) {
            $this->set_debug('Trying to unzip before loading file list... Loading it!');
            $this->_list_files(FALSE, $compressed_file_name);
        }

        $fdetails = &$this->compressed_list[$compressed_file_name];

        if (!isset($this->compressed_list[$compressed_file_name])) {
            $this->set_error('File "<strong>$compressed_file_name</strong>" is not compressed in the zip.');
            return FALSE;
        }

        if (substr($compressed_file_name, -1) == '/') {
            $this->set_error('Trying to unzip a folder name "<strong>$compressed_file_name</strong>".');
            return FALSE;
        }

        if (!$fdetails['uncompressed_size']) {
            $this->set_debug('File "<strong>$compressed_file_name</strong>" is empty.');

            return $target_file_name ? file_put_contents($target_file_name, '') : '';
        }

        if ($underscore_case) {
            $pathinfo = pathinfo($target_file_name);
            $pathinfo['filename_new'] = preg_replace('/([^.a-z0-9]+)/i', '_', strtolower($pathinfo['filename']));
            $target_file_name = $pathinfo['dirname'] . '/' . $pathinfo['filename_new'] . '.' . strtolower($pathinfo['extension']);
        }

        fseek($this->fh, $fdetails['contents_start_offset']);
        $ret = $this->_uncompress(fread($this->fh, $fdetails['compressed_size']), $fdetails['compression_method'], $fdetails['uncompressed_size'], $target_file_name);

        if ($this->apply_chmod && $target_file_name) {
            chmod($target_file_name, 0755);
        }

        return $ret;
    }

    // --------------------------------------------------------------------

    /**
     * Free the file resource.
     *
     * @access    Public
     * @param     none
     * @return    none
     */
    public function close() {
        // Free the file resource
        if ($this->fh) {
            fclose($this->fh);
        }
    }

    // --------------------------------------------------------------------

    /**
     * Free the file resource Automatic destroy.
     *
     * @access    Public
     * @param     none
     * @return    none
     */
    public function __destroy() {
        $this->close();
    }

    // --------------------------------------------------------------------

    /**
     * Uncompress file. And save it to the targetFile.
     *
     * @access    Private
     * @param     Filecontent, int, int, boolean
     * @return    none
     */
    private function _uncompress($content, $mode, $uncompressed_size, $target_file_name = FALSE) {
        switch ($mode) {
            case 0 :
                return $target_file_name ? file_put_contents($target_file_name, $content) : $content;
            case 1 :
                $this->set_error('Shrunk mode is not supported... yet?');
                return FALSE;
            case 2 :
            case 3 :
            case 4 :
            case 5 :
                $this->set_error('Compression factor ' . ($mode - 1) . ' is not supported... yet?');
                return FALSE;
            case 6 :
                $this->set_error('Implode is not supported... yet?');
                return FALSE;
            case 7 :
                $this->set_error('Tokenizing compression algorithm is not supported... yet?');
                return FALSE;
            case 8 :
                // Deflate
                return $target_file_name ? file_put_contents($target_file_name, gzinflate($content, $uncompressed_size)) : gzinflate($content, $uncompressed_size);
            case 9 :
                $this->set_error('Enhanced Deflating is not supported... yet?');
                return FALSE;
            case 10 :
                $this->set_error('PKWARE Date Compression Library Impoloding is not supported... yet?');
                return FALSE;
            case 12 :
                // Bzip2
                return $target_file_name ? file_put_contents($target_file_name, bzdecompress($content)) : bzdecompress($content);
            case 18 :
                $this->set_error('IBM TERSE is not supported... yet?');
                return FALSE;
            default :
                $this->set_error('Unknown uncompress method: $mode');
                return FALSE;
        }
    }

    private function _load_file_list_by_eof(&$fh, $stop_on_file = FALSE) {
        // Check if there's a valid Central Dir signature.
        // Let's consider a file comment smaller than 1024 characters...
        // Actually, it length can be 65536.. But we're not going to support it.

        for ($x = 0; $x < 1024; $x++) {
            fseek($fh, -22 - $x, SEEK_END);

            $signature = fread($fh, 4);

            if ($signature == $this->central_signature_end) {
                // If found EOF Central Dir
                $eodir['disk_number_this'] = unpack("v", fread($fh, 2));
                // number of this disk
                $eodir['disk_number'] = unpack("v", fread($fh, 2));
                // number of the disk with the start of the central directory
                $eodir['total_entries_this'] = unpack("v", fread($fh, 2));
                // total number of entries in the central dir on this disk
                $eodir['total_entries'] = unpack("v", fread($fh, 2));
                // total number of entries in
                $eodir['size_of_cd'] = unpack("V", fread($fh, 4));
                // size of the central directory
                $eodir['offset_start_cd'] = unpack("V", fread($fh, 4));
                // offset of start of central directory with respect to the starting disk number
                $zip_comment_lenght = unpack("v", fread($fh, 2));
                // zipfile comment length
                $eodir['zipfile_comment'] = $zip_comment_lenght[1] ? fread($fh, $zip_comment_lenght[1]) : '';
                // zipfile comment

                $this->end_of_central = array('disk_number_this' => $eodir['disk_number_this'][1], 'disk_number' => $eodir['disk_number'][1], 'total_entries_this' => $eodir['total_entries_this'][1], 'total_entries' => $eodir['total_entries'][1], 'size_of_cd' => $eodir['size_of_cd'][1], 'offset_start_cd' => $eodir['offset_start_cd'][1], 'zipfile_comment' => $eodir['zipfile_comment'],);

                // Then, load file list
                fseek($fh, $this->end_of_central['offset_start_cd']);
                $signature = fread($fh, 4);

                while ($signature == $this->dir_signature) {
                    $dir['version_madeby'] = unpack("v", fread($fh, 2));
                    // version made by
                    $dir['version_needed'] = unpack("v", fread($fh, 2));
                    // version needed to extract
                    $dir['general_bit_flag'] = unpack("v", fread($fh, 2));
                    // general purpose bit flag
                    $dir['compression_method'] = unpack("v", fread($fh, 2));
                    // compression method
                    $dir['lastmod_time'] = unpack("v", fread($fh, 2));
                    // last mod file time
                    $dir['lastmod_date'] = unpack("v", fread($fh, 2));
                    // last mod file date
                    $dir['crc-32'] = fread($fh, 4);
                    // crc-32
                    $dir['compressed_size'] = unpack("V", fread($fh, 4));
                    // compressed size
                    $dir['uncompressed_size'] = unpack("V", fread($fh, 4));
                    // uncompressed size
                    $zip_file_length = unpack("v", fread($fh, 2));
                    // filename length
                    $extra_field_length = unpack("v", fread($fh, 2));
                    // extra field length
                    $fileCommentLength = unpack("v", fread($fh, 2));
                    // file comment length
                    $dir['disk_number_start'] = unpack("v", fread($fh, 2));
                    // disk number start
                    $dir['internal_attributes'] = unpack("v", fread($fh, 2));
                    // internal file attributes-byte1
                    $dir['external_attributes1'] = unpack("v", fread($fh, 2));
                    // external file attributes-byte2
                    $dir['external_attributes2'] = unpack("v", fread($fh, 2));
                    // external file attributes
                    $dir['relative_offset'] = unpack("V", fread($fh, 4));
                    // relative offset of local header
                    $dir['file_name'] = fread($fh, $zip_file_length[1]);
                    // filename
                    $dir['extra_field'] = $extra_field_length[1] ? fread($fh, $extra_field_length[1]) : '';
                    // extra field
                    $dir['file_comment'] = $fileCommentLength[1] ? fread($fh, $fileCommentLength[1]) : '';
                    // file comment
                    // Convert the date and time, from MS-DOS format to UNIX Timestamp
                    $binary_mod_date = str_pad(decbin($dir['lastmod_date'][1]), 16, '0', STR_PAD_LEFT);
                    $binary_mod_time = str_pad(decbin($dir['lastmod_time'][1]), 16, '0', STR_PAD_LEFT);
                    $last_mod_year = bindec(substr($binary_mod_date, 0, 7)) + 1980;
                    $last_mod_month = bindec(substr($binary_mod_date, 7, 4));
                    $last_mod_day = bindec(substr($binary_mod_date, 11, 5));
                    $last_mod_hour = bindec(substr($binary_mod_time, 0, 5));
                    $last_mod_minute = bindec(substr($binary_mod_time, 5, 6));
                    $last_mod_second = bindec(substr($binary_mod_time, 11, 5));

                    $this->central_dir_list[$dir['file_name']] = array('version_madeby' => $dir['version_madeby'][1], 'version_needed' => $dir['version_needed'][1], 'general_bit_flag' => str_pad(decbin($dir['general_bit_flag'][1]), 8, '0', STR_PAD_LEFT), 'compression_method' => $dir['compression_method'][1], 'lastmod_datetime' => mktime($last_mod_hour, $last_mod_minute, $last_mod_second, $last_mod_month, $last_mod_day, $last_mod_year), 'crc-32' => str_pad(dechex(ord($dir['crc-32'][3])), 2, '0', STR_PAD_LEFT) . str_pad(dechex(ord($dir['crc-32'][2])), 2, '0', STR_PAD_LEFT) . str_pad(dechex(ord($dir['crc-32'][1])), 2, '0', STR_PAD_LEFT) . str_pad(dechex(ord($dir['crc-32'][0])), 2, '0', STR_PAD_LEFT), 'compressed_size' => $dir['compressed_size'][1], 'uncompressed_size' => $dir['uncompressed_size'][1], 'disk_number_start' => $dir['disk_number_start'][1], 'internal_attributes' => $dir['internal_attributes'][1], 'external_attributes1' => $dir['external_attributes1'][1], 'external_attributes2' => $dir['external_attributes2'][1], 'relative_offset' => $dir['relative_offset'][1], 'file_name' => $dir['file_name'], 'extra_field' => $dir['extra_field'], 'file_comment' => $dir['file_comment'],);

                    $signature = fread($fh, 4);
                }

                // If loaded centralDirs, then try to identify the offsetPosition of the compressed data.
                if ($this->central_dir_list) {
                    foreach ($this->central_dir_list as $filename => $details) {
                        $i = $this->_get_file_header($fh, $details['relative_offset']);
                        $this->compressed_list[$filename]['file_name'] = $filename;
                        $this->compressed_list[$filename]['compression_method'] = $details['compression_method'];
                        $this->compressed_list[$filename]['version_needed'] = $details['version_needed'];
                        $this->compressed_list[$filename]['lastmod_datetime'] = $details['lastmod_datetime'];
                        $this->compressed_list[$filename]['crc-32'] = $details['crc-32'];
                        $this->compressed_list[$filename]['compressed_size'] = $details['compressed_size'];
                        $this->compressed_list[$filename]['uncompressed_size'] = $details['uncompressed_size'];
                        $this->compressed_list[$filename]['lastmod_datetime'] = $details['lastmod_datetime'];
                        $this->compressed_list[$filename]['extra_field'] = $i['extra_field'];
                        $this->compressed_list[$filename]['contents_start_offset'] = $i['contents_start_offset'];

                        if (strtolower($stop_on_file) == strtolower($filename)) {
                            break;
                        }
                    }
                }

                return true;
            }
        }
        return FALSE;
    }

    private function _load_files_by_signatures(&$fh, $stop_on_file = FALSE) {
        fseek($fh, 0);

        $return = FALSE;
        for (;;) {
            $details = $this->_get_file_header($fh);

            if (!$details) {
                $this->set_debug('Invalid signature. Trying to verify if is old style Data Descriptor...');
                fseek($fh, 12 - 4, SEEK_CUR);
                // 12: Data descriptor - 4: Signature (that will be read again)
                $details = $this->_get_file_header($fh);
            }

            if (!$details) {
                $this->set_debug('Still invalid signature. Probably reached the end of the file.');
                break;
            }

            $filename = $details['file_name'];
            $this->compressed_list[$filename] = $details;
            $return = true;

            if (strtolower($stop_on_file) == strtolower($filename)) {
                break;
            }
        }

        return $return;
    }

    private function _get_file_header(&$fh, $start_offset = FALSE) {
        if ($start_offset !== FALSE) {
            fseek($fh, $start_offset);
        }

        $signature = fread($fh, 4);

        if ($signature == $this->zip_signature) {
            // Get information about the zipped file
            $file['version_needed'] = unpack("v", fread($fh, 2));
            // version needed to extract
            $file['general_bit_flag'] = unpack("v", fread($fh, 2));
            // general purpose bit flag
            $file['compression_method'] = unpack("v", fread($fh, 2));
            // compression method
            $file['lastmod_time'] = unpack("v", fread($fh, 2));
            // last mod file time
            $file['lastmod_date'] = unpack("v", fread($fh, 2));
            // last mod file date
            $file['crc-32'] = fread($fh, 4);
            // crc-32
            $file['compressed_size'] = unpack("V", fread($fh, 4));
            // compressed size
            $file['uncompressed_size'] = unpack("V", fread($fh, 4));
            // uncompressed size
            $zip_file_length = unpack("v", fread($fh, 2));
            // filename length
            $extra_field_length = unpack("v", fread($fh, 2));
            // extra field length
            $file['file_name'] = fread($fh, $zip_file_length[1]);
            // filename
            $file['extra_field'] = $extra_field_length[1] ? fread($fh, $extra_field_length[1]) : '';
            // extra field
            $file['contents_start_offset'] = ftell($fh);

            // Bypass the whole compressed contents, and look for the next file
            fseek($fh, $file['compressed_size'][1], SEEK_CUR);

            // Convert the date and time, from MS-DOS format to UNIX Timestamp
            $binary_mod_date = str_pad(decbin($file['lastmod_date'][1]), 16, '0', STR_PAD_LEFT);
            $binary_mod_time = str_pad(decbin($file['lastmod_time'][1]), 16, '0', STR_PAD_LEFT);

            $last_mod_year = bindec(substr($binary_mod_date, 0, 7)) + 1980;
            $last_mod_month = bindec(substr($binary_mod_date, 7, 4));
            $last_mod_day = bindec(substr($binary_mod_date, 11, 5));
            $last_mod_hour = bindec(substr($binary_mod_time, 0, 5));
            $last_mod_minute = bindec(substr($binary_mod_time, 5, 6));
            $last_mod_second = bindec(substr($binary_mod_time, 11, 5));

            // Mount file table
            $i = array('file_name' => $file['file_name'], 'compression_method' => $file['compression_method'][1], 'version_needed' => $file['version_needed'][1], 'lastmod_datetime' => mktime($last_mod_hour, $last_mod_minute, $last_mod_second, $last_mod_month, $last_mod_day, $last_mod_year), 'crc-32' => str_pad(dechex(ord($file['crc-32'][3])), 2, '0', STR_PAD_LEFT) . str_pad(dechex(ord($file['crc-32'][2])), 2, '0', STR_PAD_LEFT) . str_pad(dechex(ord($file['crc-32'][1])), 2, '0', STR_PAD_LEFT) . str_pad(dechex(ord($file['crc-32'][0])), 2, '0', STR_PAD_LEFT), 'compressed_size' => $file['compressed_size'][1], 'uncompressed_size' => $file['uncompressed_size'][1], 'extra_field' => $file['extra_field'], 'general_bit_flag' => str_pad(decbin($file['general_bit_flag'][1]), 8, '0', STR_PAD_LEFT), 'contents_start_offset' => $file['contents_start_offset']);

            return $i;
        }
        return FALSE;
    }

}

?>